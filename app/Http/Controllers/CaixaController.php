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

    //Abrir modal do caixa disponivel

    function abrirCaixa(Request $request)
    {    
        $user_auth    = Auth::user()->id;
        $data         = $request->all();

        $caixa_aberto =  Caixa::where('user_abertura_id', $user_auth)
        ->first();
      
        if ($caixa_aberto != null)
            return response()->json(['cx_aberto' => 'VOCÊ JÁ POSSUI O CAIXA ' . $caixa_aberto->nro_caixa_id . ' ABERTO.']);
        
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
}
