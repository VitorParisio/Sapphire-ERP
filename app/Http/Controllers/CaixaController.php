<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ItemVenda;
use App\Models\NumeroCaixa;
use Illuminate\Http\Request;
use App\Models\ContaFechamento;
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
            $info         = $nro_caixa->user_id == null ? 'Clique para abrir este caixa' : 'Clique para mais informações';
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
        $caixa_aberto = NumeroCaixa::where('user_id', $user_auth)
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

    function getCaixaAberto($id)
    {
        $caixa_aberto_query = Caixa::join('users', 'users.id', '=', 'caixas.user_abertura_id')
        ->where('caixas.user_abertura_id', $id)
        ->where('caixas.status', 1)
        ->first();
        
        $user_nome         = $caixa_aberto_query->name;
        $data_caixa_aberto = date("d/m/Y", strtotime($caixa_aberto_query->data_abertura));
        $horario_abertura  = $caixa_aberto_query->horario_abertura;
        $sangria           = number_format($caixa_aberto_query->sangria,2,",",".");
        $suplemento        = number_format($caixa_aberto_query->suplemento,2,",",".");
        $valor_abertura    = number_format($caixa_aberto_query->valor_abertura,2,",",".");
        $total_caixa       = number_format($caixa_aberto_query->total_caixa,2,",",".");
    
        $data = array(
            'usuario_nome'      => $user_nome,
            'data_caixa_aberto' => $data_caixa_aberto, 
            'horario_abertura'  => $horario_abertura, 
            'suplemento'        => $suplemento,
            'sangria'           => $sangria,
            'valor_abertura'    => $valor_abertura,
            'total_caixa'       => $total_caixa
        );

        return response()->json($data);
    }

    function abrirCaixa(Request $request)
    {    
        $user_auth    = Auth::user()->id;
        $data         = $request->all();

        $caixa_aberto = NumeroCaixa::where('user_id', $user_auth)
        ->first();
      
        if ($caixa_aberto != null)
            return response()->json(['cx_aberto' => 'VOCÊ JÁ POSSUI O CAIXA 0' . $caixa_aberto->numero . ' ABERTO.']);
        
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
    
        $numero_caixa = NumeroCaixa::select('id')
        ->where('descricao', $request->numero_caixa)->first();
    
        Caixa::create([
            "nro_caixa_id"     => $numero_caixa->id,
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
        $user_auth  = Auth::user()->id;
        $data       = $request->all();

        $caixa_aberto = NumeroCaixa::where('user_id', $user_auth)
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
    
        $numero_caixa = NumeroCaixa::select('id')
        ->where('descricao', $request->numero_caixa)->first();
    
        Caixa::create([
            "nro_caixa_id"     => $numero_caixa->id,
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
        $data         = [];

        $caixa_info = NumeroCaixa::join('caixas', 'numero_caixas.id', '=', 'caixas.nro_caixa_id')
        ->select('caixas.id as caixa_id', 'numero_caixas.descricao', 'caixas.valor_abertura', 'caixas.valor_vendido', 'caixas.sangria', 'caixas.suplemento', 'caixas.total_caixa')
        ->where('caixas.user_abertura_id', $user_auth_id)
        ->where('caixas.status', 1)
        ->first();
       
        $conta_fechamentos = Caixa::join('venda_cupoms', 'caixas.id', '=', 'venda_cupoms.caixa_id')
        ->join('numero_caixas', 'numero_caixas.id', '=', 'caixas.nro_caixa_id')
        ->selectRaw('venda_cupoms.forma_pagamento, SUM(venda_cupoms.total_venda) as total_venda_fechamento')
        ->where('caixas.id', $caixa_info->caixa_id)
        ->where('caixas.status', 1)
        ->groupBy('venda_cupoms.forma_pagamento')
        ->get();

        $itens_vendidos = ItemVenda::join('caixas', 'caixas.id', '=', 'item_vendas.caixa_id')
        ->join('products', 'products.id', '=', 'item_vendas.product_id')
        ->selectRaw('products.nome, SUM(item_vendas.qtd) as item_qtd, products.id, SUM(item_vendas.sub_total) as item_soma_total, products.preco_venda')
        ->where('caixas.user_abertura_id', $user_auth_id)
        ->where('caixas.id', $caixa_info->caixa_id)
        ->where('caixas.status', 1)
        ->groupBy('products.nome', 'products.id', 'products.preco_venda')
        ->get();

        $qtd_itens_vendidos = count($itens_vendidos);

        foreach($conta_fechamentos as $conta_fechamento)
        {
            array_push($data, $conta_fechamento->forma_pagamento); 
        }

        for($i = 0; $i < count($data); $i++)
        {
            if($data[$i] == 01)
                $data[$i] = "DINHEIRO";
            if($data[$i] == 02)
                $data[$i] = "CHEQUE";
            if($data[$i] == 03)
                $data[$i] = "cartao_de_credito";
            if($data[$i] == 04)
                $data[$i] = "cartao_de_debito";
            if($data[$i] == 05)
                $data[$i] = "PIX";
        }
    
        return view('caixas.fechamento_caixa', compact('caixa_info', 'conta_fechamentos', 'data', 'itens_vendidos', 'qtd_itens_vendidos')); 
    }

    function fechamentoCaixa(Request $request)
    {   
        $user_auth  = Auth::user()->id;
        $caixa_info = Caixa::select('id', 'sangria', 'suplemento', 'valor_vendido','total_caixa')
        ->where('user_abertura_id', $user_auth)
        ->where('status', '=', 1)
        ->first();
       
        $data                             = $request->all();
        $total_fechamento                 = 0.0;
        $total_valor_informado_fechamento = 0.0;
        $total_diferenca                  = 0.0;
       
        $validator = Validator::make($data, [
            'valor_informado_fechamento.*' => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/|',
            'diferenca_fechamento.*'       => 'required|',
          ],
          [
            'valor_informado_fechamento.*.required' => 'Preencha todos os campos em "REALIZADOS(R$)".',
            'valor_informado_fechamento.*.regex'    => 'Formato incorreto em "REALIZADOS(R$)".',
            'diferenca_fechamento.*.required'       => 'Preencha todos os campos em "DIFERENÇAS(R$)".',
          ]);
      
          if ($validator->fails()) {
              return response()->json([
                  'error' => $validator->errors()->all()
              ]);
          }

          if (count($data) == 1)
          {
            Caixa::where('user_abertura_id', $user_auth)
            ->where('status', '=', 1)
            ->update([
                "user_fechamento_id" => $user_auth,
                "data_fechamento"    => date('Y:m:d'),
                "horario_fechamento" => date('H:i:s'),
            ]);

            ContaFechamento::create([
                'caixa_id'                       => $caixa_info->id,
                'forma_pagamento_fechamento'     => "Não houve vendas.",
                'diferenca_pagamento_fechamento' =>  "R$ 0,00"
            ]);

            return;
          }

        $data['total_fechamento']           =  str_replace(".","", $data['total_fechamento']);
        $data['total_fechamento']           =  str_replace(",",".", $data['total_fechamento']);
        $data['valor_informado_fechamento'] =  str_replace(".","", $data['valor_informado_fechamento']);
        $data['valor_informado_fechamento'] =  str_replace(",",".", $data['valor_informado_fechamento']);   

        for($i = 0; $i < count($data['total_fechamento']); $i++)
        {
            $total_fechamento += $data['total_fechamento'][$i];
        }
    
        for($j = 0; $j < count($data['valor_informado_fechamento']); $j++)
        {
            $total_valor_informado_fechamento += $data['valor_informado_fechamento'][$j];  
        }

        if ($total_fechamento > $total_valor_informado_fechamento)
        {
            $total_diferenca        = $total_fechamento - $total_valor_informado_fechamento;
            $valor_fechamento_caixa = $caixa_info->total_caixa + $caixa_info->suplemento - $caixa_info->sangria - $total_diferenca; 
            $valor_vendido          = $caixa_info->valor_vendido - $total_diferenca;

            Caixa::where('caixas.user_abertura_id', $user_auth)
            ->where('caixas.status', '=', 1)
            ->update([
                "user_fechamento_id" => $user_auth,
                "data_fechamento"    => date('Y:m:d'),
                "horario_fechamento" => date('H:i:s'),
                "valor_fechamento"   => $valor_fechamento_caixa,
                "valor_vendido"      => $valor_vendido
            ]);

        } else if ($total_fechamento < $total_valor_informado_fechamento)
        {
            $total_diferenca        = $total_valor_informado_fechamento - $total_fechamento;
            $valor_fechamento_caixa = $caixa_info->total_caixa + $caixa_info->suplemento + $total_diferenca - $caixa_info->sangria ; 
            $valor_vendido          = $caixa_info->valor_vendido + $total_diferenca;

            Caixa::where('caixas.user_abertura_id', $user_auth)
            ->where('caixas.status', 1)
            ->update([
                "user_fechamento_id" => $user_auth,
                "data_fechamento"    => date('Y:m:d'),
                "horario_fechamento" => date('H:i:s'),
                "valor_fechamento"   => $valor_fechamento_caixa,
                "valor_vendido"      => $valor_vendido
            ]);
        } else {
          
            Caixa::where('caixas.user_abertura_id', $user_auth)
            ->where('caixas.status', '=', 1)
            ->update([
                "user_fechamento_id" => $user_auth,
                "data_fechamento"    => date('Y:m:d'),
                "horario_fechamento" => date('H:i:s'),
                "valor_fechamento"   => $caixa_info->total_caixa,
            ]);
        }
     
        for($i = 0; $i < count($data['forma_pagamento_numero']); $i++)
        {
            if ($data['forma_pagamento_numero'][$i] == "01")
            {
                ContaFechamento::create([
                    'caixa_id'                       => $data['caixa_id'],                    
                    'forma_pagamento_fechamento'     => $data['forma_pagamento_fechamento'][$i],
                    'total_caixa_conta_fechamento'   => $data['total_fechamento'][$i],
                    'total_caixa_informado'          => $data['valor_informado_fechamento'][$i],
                    'diferenca_pagamento_fechamento' => $data['diferenca_fechamento'][$i]
                ]);
            }
            if ($data['forma_pagamento_numero'][$i] == "02")
            { 
                ContaFechamento::create([
                    'caixa_id'                       => $data['caixa_id'],                    
                    'forma_pagamento_fechamento'     => $data['forma_pagamento_fechamento'][$i],
                    'total_caixa_conta_fechamento'   => $data['total_fechamento'][$i],
                    'total_caixa_informado'          => $data['valor_informado_fechamento'][$i],
                    'diferenca_pagamento_fechamento' => $data['diferenca_fechamento'][$i]
                ]);
               
            }
            if ($data['forma_pagamento_numero'][$i] == "03")
            {
                ContaFechamento::create([
                    'caixa_id'                       => $data['caixa_id'],                    
                    'forma_pagamento_fechamento'     => $data['forma_pagamento_fechamento'][$i],
                    'total_caixa_conta_fechamento'   => $data['total_fechamento'][$i],
                    'total_caixa_informado'          => $data['valor_informado_fechamento'][$i],
                    'diferenca_pagamento_fechamento' => $data['diferenca_fechamento'][$i]
                ]);
            }
            if ($data['forma_pagamento_numero'][$i] == "04")
            {
                ContaFechamento::create([
                    'caixa_id'                       => $data['caixa_id'],                    
                    'forma_pagamento_fechamento'     => $data['forma_pagamento_fechamento'][$i],
                    'total_caixa_conta_fechamento'   => $data['total_fechamento'][$i],
                    'total_caixa_informado'          => $data['valor_informado_fechamento'][$i],
                    'diferenca_pagamento_fechamento' => $data['diferenca_fechamento'][$i]
                ]);
            }
            if ($data['forma_pagamento_numero'][$i] == "05")
            {
                ContaFechamento::create([
                    'caixa_id'                       => $data['caixa_id'],                    
                    'forma_pagamento_fechamento'     => $data['forma_pagamento_fechamento'][$i],
                    'total_caixa_conta_fechamento'   => $data['total_fechamento'][$i],
                    'total_caixa_informado'          => $data['valor_informado_fechamento'][$i],
                    'diferenca_pagamento_fechamento' => $data['diferenca_fechamento'][$i]
                ]);
            }
        } 
    }

    function cupomFechamento($caixa_id){
     
        $user_auth  = Auth::user()->id;
     
        $info_caixa = Caixa::join('numero_caixas', 'numero_caixas.id', '=', 'caixas.nro_caixa_id')
        ->join('conta_fechamentos', 'caixas.id', '=', 'conta_fechamentos.caixa_id')
        ->select('numero_caixas.descricao','caixas.data_abertura', 'caixas.data_fechamento', 'caixas.horario_abertura', 'caixas.horario_fechamento', 'caixas.valor_abertura', 'caixas.valor_vendido', 'caixas.total_caixa', 'caixas.sangria', 'caixas.suplemento', 'caixas.valor_fechamento')
        ->where('caixas.id', $caixa_id)
        ->where('caixas.status', 1)
        ->first();

        $fechamento = $info_caixa->valor_fechamento != 0 ? $info_caixa->valor_fechamento : $info_caixa->total_caixa;

        $pagamentos = ContaFechamento::join('caixas', 'caixas.id', '=', 'conta_fechamentos.caixa_id')
        ->selectRaw('conta_fechamentos.forma_pagamento_fechamento as forma, SUM(conta_fechamentos.total_caixa_conta_fechamento) as total_venda_fechamento, SUM(conta_fechamentos.total_caixa_informado) as caixa_informado, conta_fechamentos.diferenca_pagamento_fechamento as diferenca_pagamento')
        ->where("conta_fechamentos.caixa_id", $caixa_id)
        ->where('caixas.status', 1)
        ->groupBy('conta_fechamentos.forma_pagamento_fechamento', 'diferenca_pagamento')
        ->get();
        
        $view = view('caixas.cupom_fechamento', compact('info_caixa', 'pagamentos', 'fechamento'));

        $pdf = PDF::loadHTML($view)->setPaper([0, 0, 807.874, 321.102], 'landscape');

        return $pdf->stream();
    }

    function caixaLogout($caixa_id)
    {
        $path         = '';
        $rule_as_user = Auth::user()->role_as;
      
         NumeroCaixa::join('caixas', 'numero_caixas.id', 'caixas.nro_caixa_id')
         ->where('caixas.id', $caixa_id)
         ->update([
            'user_id' => null
        ]);

        Caixa::where('id', $caixa_id)
        ->where('status', '=', 1)
        ->update([
            "status" => 0
        ]);

        if ($rule_as_user == 2)
        {
            $path = "/dashboard";
            return response()->json($path);
        }

        Auth::logout();
    }

    function suprimentoCaxiaValores($desricao_caixa)
    {
        $total_caixa = NumeroCaixa::join('caixas', 'numero_caixas.id', '=', 'caixas.nro_caixa_id')
        ->select('caixas.total_caixa')
        ->where("numero_caixas.descricao", $desricao_caixa)
        ->where('caixas.status', 1)
        ->first();

        $total_caixa_formatado = number_format($total_caixa->total_caixa,'2',',','.');

        return response()->json($total_caixa_formatado);
    }

    function suprimentoCaixa(Request $request)
    {
        $data = $request->all();
       
        $validator = Validator::make($data, [
            'valor_suprimento'      => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/|',
            'saldo_apos_suprimento' => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/|',
          ],
          [
            'valor_suprimento.required'      => 'Preencha o campo "Valor da suprimento".',
            'valor_suprimento.regex'         => 'Formato do valor deve ser em moeda".',
            'saldo_apos_suprimento.required' => 'Preencha o campo "Valor do suprimento".',
            'saldo_apos_suprimento.regex'    => 'Formato do valor deve ser em moeda".',
          ]);
      
          if ($validator->fails()) {
              return response()->json([
                  'error' => $validator->errors()->all()
              ]);
          }

        $numero_caixa = NumeroCaixa::select('id')->where('descricao', $request->numero_caixa_suprimento)
          ->first();

        $caixa_update = Caixa::where('caixas.nro_caixa_id', $numero_caixa->id)
          ->where('caixas.status', 1)
          ->first();
     
        $valor_suprimento_formatado = str_replace('.', '', $request->valor_suprimento);
        $total_caixa_foramatado     = str_replace('.', '', $request->saldo_apos_suprimento);

        $caixa_update->suplemento += str_replace(',', '.', $valor_suprimento_formatado);
        $caixa_update->total_caixa = str_replace(',', '.', $total_caixa_foramatado);
       
        $caixa_update->update();

        return response()->json(['message' => 'Suprimento realizada com sucesso!']);
    }

    function sangriaCaxiaValores($desricao_caixa)
    {
        $total_caixa = NumeroCaixa::join('caixas', 'numero_caixas.id', '=', 'caixas.nro_caixa_id')
        ->select('caixas.total_caixa')
        ->where("numero_caixas.descricao", $desricao_caixa)
        ->where('caixas.status', 1)
        ->first();

        $total_caixa_formatado = number_format($total_caixa->total_caixa,'2',',','.');

        return response()->json($total_caixa_formatado);
    }

    function retiradaSangria(Request $request)
    {
        $data = $request->all();
       
        $validator = Validator::make($data, [
            'valor_sangria'      => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/|',
            'saldo_apos_sangria' => 'required|regex:/^\d{1,3}(\.\d{3})*,\d{2}$/|',
          ],
          [
            'valor_sangria.required'      => 'Preencha o campo "Valor da sangria".',
            'valor_sangria.regex'         => 'Formato do valor deve ser em moeda".',
            'saldo_apos_sangria.required' => 'Preencha o campo "Valor da sangria".',
            'saldo_apos_sangria.regex'    => 'Formato do valor deve ser em moeda".',
          ]);
      
          if ($validator->fails()) {
              return response()->json([
                  'error' => $validator->errors()->all()
              ]);
          }

        $numero_caixa = NumeroCaixa::select('id')->where('descricao', $request->numero_caixa_sangria)
          ->first();

        $caixa_update = Caixa::where('caixas.nro_caixa_id', $numero_caixa->id)
          ->where('caixas.status', 1)
          ->first();
     
        $valor_sangria_formatado = str_replace('.', '', $request->valor_sangria);
        $total_caixa_foramatado  = str_replace('.', '', $request->saldo_apos_sangria);

        $caixa_update->sangria    += str_replace(',', '.', $valor_sangria_formatado);
        $caixa_update->total_caixa = str_replace(',', '.', $total_caixa_foramatado);
       
        $caixa_update->update();

        return response()->json(['message' => 'Sangria realizada com sucesso!']);
    }

    function opLogout()
    {
        Auth::logout();
    }
}
