<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\NumeroCaixa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CaixaController extends Controller
{
    function index()
    {
        return view('caixas.index'); 
    }

    function getCaixas()
    {   
        $numero_caixas          = NumeroCaixa::get();
        $total_caixa_disponivel = NumeroCaixa::where('user_id', null)->count();
        $output                 = '';
      
        foreach ($numero_caixas as $nro_caixa)
        {   
            $bg_color     = $nro_caixa->user_id == null ? 'bg-success' : 'bg-danger'; 
            $h5           = $nro_caixa->user_id == null ? 'CAIXA DISPONÍVEL' : 'CAIXA ABERTO';
            $info         = $nro_caixa->user_id == null ? 'Clique para abrir este caixa.' : 'Clique para mais informações';
            $funcao_caixa = $nro_caixa->user_id == null ? 'descricaoAberturaCaixa(this)' : 'descricaoCaixaAberto(this, '.$nro_caixa->user_id.')';
            $caixa_links  = $nro_caixa->user_id == null ? 'abrir_caixa_link' : 'caixa_aberto_link';

            $output .=
            '<a class="small-box '.$bg_color.' '.$caixa_links.'" id="'.$nro_caixa->descricao.'" onclick="'.$funcao_caixa.'">
                <div class="inner" style="text-align:center;">
                    <div class="caixa_header">
                        <h3>'.$nro_caixa->descricao.'</h3>
                    </div>
                    <div class="caixa_body">
                        <span>
                            <h5>'.$h5.'</h5>
                            <small><i>'.$info.'</i></small>
                        </span>
                    </div>
                </div>
            </a>';
        }

        $data = array(
            'output'                 => $output,
            'total_caixa_disponivel' => $total_caixa_disponivel
        );
        
        return response()->json($data);
    }

    function opAbreCaixa()
    {
        $user_auth    = Auth::user()->id;
        $caixa_aberto = Caixa::where('user_abertura_id', $user_auth)
        ->first();

        if ($caixa_aberto != null)
            return redirect('/pdv');

        $caixas = NumeroCaixa::select('descricao')
        ->where('user_id', null)
        ->get();

        $caixa_count = $caixas->count();
       
        if ($caixa_count == 0)
        {
            Auth::logout();
            return redirect()->back()->with('error', 'Nenhum caixa disponível no momento.');
        }
            
        return view('caixas.operador_abre_caixa', compact('caixas'));
    }

    //Modal de informações do caixa aberto

    function getCaixaAberto($id)
    {
        $caixa_aberto_query = Caixa::join('users', 'users.id', '=', 'caixas.user_abertura_id')
        ->where('caixas.user_abertura_id', $id)
        ->first();
        
        $user_nome         = $caixa_aberto_query->name;
        $data_caixa_aberto = date("d/m/Y", strtotime($caixa_aberto_query->data_abertura));
        $horario_abertura  = $caixa_aberto_query->horario_abertura;
        $valor_abertura    = number_format($caixa_aberto_query->valor_abertura,2,",",".");
        $total_caixa       = number_format($caixa_aberto_query->total_caixa,2,",",".");
    

        $data = array(
            'usuario_nome'      => $user_nome,
            'data_caixa_aberto' => $data_caixa_aberto, 
            'horario_abertura'  => $horario_abertura, 
            'valor_abertura'    => $valor_abertura,
            'total_caixa'       => $total_caixa
        );

        return response()->json($data);
    }

    //Abrir modal do caixa disponivel

    function abrirCaixa(Request $request)
    {    
        $user_auth    = Auth::user()->id;
        $data         = $request->all();

        $caixa_aberto =  Caixa::where('user_abertura_id', $user_auth)
        ->first();
      
        if ($caixa_aberto != null)
            return response()->json(['cx_aberto' => 'VOCÊ JÁ POSSUI O CAIXA 0' . $caixa_aberto->nro_caixa_id . ' ABERTO.']);
        
        $validator = Validator::make($data, [
            'valor_abertura_caixa' => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
        ],
        [
            'valor_abertura_caixa.required' => 'Campo "Valor de fundo" deve ser preenchido.',
            'valor_abertura_caixa.regex'    => 'Digitar o valor correto no campo "Valor de fundo".',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        $valor_abertura_caixa_formatado = str_replace('.', '', $request->valor_abertura_caixa);
        $valor_abertura_caixa_formatado = str_replace(',', '.', $valor_abertura_caixa_formatado);

        NumeroCaixa::where('descricao', $request->numero_caixa)->update([
            "user_id" => $user_auth,
        ]); 
    
        $numero_caixa = NumeroCaixa::select('numero')
        ->where('descricao', $request->numero_caixa)->first();
    
        Caixa::create([
            "nro_caixa_id"     => $numero_caixa->numero,
            "user_abertura_id" => $user_auth,
            "data_abertura"    => date('Y:m:d'),
            "horario_abertura" => date('H:i:s'),
            "valor_abertura"   => $valor_abertura_caixa_formatado,
            "total_caixa"      => $valor_abertura_caixa_formatado,
            "status"           => 1
        ]);  

        return response()->json(['message' => 'CAIXA ABERTO!']);
        
    }

    function abrirCaixaOp(Request $request)
    {    
        $user_auth    = Auth::user()->id;
        $data         = $request->all();

        $caixa_aberto =  Caixa::where('user_abertura_id', $user_auth)
        ->first();
      
        if ($caixa_aberto != null)
            return redirect('/pdv');
        
        $validator = Validator::make($data, [
            'valor_abertura_caixa' => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/',
        ],
        [
            'valor_abertura_caixa.required' => 'Campo "Valor de fundo" deve ser preenchido.',
            'valor_abertura_caixa.regex'    => 'Digitar o valor correto no campo "Valor de fundo".',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->all()
            ]);
        }

        $valor_abertura_caixa_formatado = str_replace('.', '', $request->valor_abertura_caixa);
        $valor_abertura_caixa_formatado = str_replace(',', '.', $valor_abertura_caixa_formatado);

        NumeroCaixa::where('descricao', $request->numero_caixa)->update([
            "user_id" => $user_auth,
        ]); 
    
        $numero_caixa = NumeroCaixa::select('numero')
        ->where('descricao', $request->numero_caixa)->first();
    
        Caixa::create([
            "nro_caixa_id"     => $numero_caixa->numero,
            "user_abertura_id" => $user_auth,
            "data_abertura"    => date('Y:m:d'),
            "horario_abertura" => date('H:i:s'),
            "valor_abertura"   => $valor_abertura_caixa_formatado,
            "total_caixa"      => $valor_abertura_caixa_formatado,
            "status"           => 1
        ]);    
    }

    function fechaCaixa()
    {
        $user_auth_id = Auth::user()->id;
        $data = [];

        $caixa_info = NumeroCaixa::join('caixas', 'numero_caixas.id', '=', 'caixas.nro_caixa_id')
        ->join('venda_cupoms', 'numero_caixas.id', '=', 'venda_cupoms.caixa_id')
        ->select('venda_cupoms.id AS venda_cupom_id','numero_caixas.descricao', 'caixas.valor_abertura', 'caixas.valor_vendido', 'caixas.sangria', 'caixas.suplemento', 'caixas.total_caixa')
        ->where('caixas.user_abertura_id', $user_auth_id)
        ->where('caixas.status', '=', 1)
        ->first();
        
        $conta_fechamentos = NumeroCaixa::join('venda_cupoms', 'numero_caixas.id', '=', 'venda_cupoms.caixa_id')
        ->join('caixas', 'numero_caixas.id', '=', 'caixas.nro_caixa_id')
        ->selectRaw('venda_cupoms.forma_pagamento, SUM(venda_cupoms.total_venda) as total_venda_fechamento')
        ->where('numero_caixas.user_id', $user_auth_id)
        ->where('caixas.status', '=', 1)
        ->groupBy('venda_cupoms.forma_pagamento')
        ->get();


        foreach($conta_fechamentos as $conta_fechamento)
        {
            array_push($data, $conta_fechamento->forma_pagamento); 
        }

        for($i = 0; $i < count($data); $i++)
        {
           if($data[$i] == 01)
            $data[$i] = "Dinheiro";
            if($data[$i] == 02)
            $data[$i] = "Cheque";
            if($data[$i] == 03)
            $data[$i] = "Carttão de Crédito";
            if($data[$i] == 04)
            $data[$i] = "Carttão de Débito";
            if($data[$i] == 05)
            $data[$i] = "PIX";
        }

    //    foreach($data as $key => $tst)
    //    {
    //        if ($key == 0)
    //        {
           
    //          $data[$key] = "Dinheiro";
           
    //        }
    //         if ($key == 1)
    //        {
    //         $data[$key] = "Cheque";
    //        }
    //        if ($key == 1)
    //        {
    //         $data[$key] = "Cheque";
    //        }
           
          
    //    }
    //    print_r($data);
        return view('caixas.fechamento_caixa', compact('caixa_info', 'conta_fechamentos', 'data'));
        
    }

}
