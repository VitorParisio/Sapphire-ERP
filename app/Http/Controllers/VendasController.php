<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\Cupom;
use App\Models\NumeroCaixa;
use App\Models\ItemVenda;
use App\Models\VendaCupom;
use App\Models\Emitente;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class VendasController extends Controller
{
    function index(){
       
        return view('vendas.index');
    }

    function cashVerify($id)
    {
        $caixas_fechados = NumeroCaixa::select('user_id')->where('user_id', null)->get();
        $caixas          = Caixa::join('users', 'users.id','=','caixas.user_abertura_id')
        ->where('users.id', $id)->first();

        if (count($caixas_fechados) == 2)
            return response()->json(['message' => 'NENHUM CAIXA ABERTO']);  
    
        if ($caixas == null)
            return response()->json(['message' => 'ABRA UM NOVO CAIXA']);  
    
        return;
    }

    function pdv()
    {
        $user_id_auth = Auth::user()->id;
        $dados_caixa  = NumeroCaixa::join('users', 'users.id', '=', 'numero_caixas.user_id')
        ->join('caixas', 'numero_caixas.id', '=', 'caixas.nro_caixa_id')
        ->where('numero_caixas.user_id', $user_id_auth)
        ->where('caixas.status', 1)
        ->select('users.name','caixas.id AS caixa_id_pdv', 'numero_caixas.user_id', 'numero_caixas.numero','numero_caixas.descricao')
        ->first();

        if ($dados_caixa == null)
        {
            return redirect()->back();
        }

        $cupom             = new Cupom();
        $slc_ult_id_cupom  = Cupom::orderBy('id', 'desc')->limit(1)->first();
        $count_qtd_id      = 0;
        $id_cupom          = '';
       
        if ($slc_ult_id_cupom == null)
        {    
            $cupom->save();

            $id_cupom = $cupom->id;    

            return view('vendas.pdv', compact('dados_caixa', 'id_cupom'));
        }
        else
        {
            if ($slc_ult_id_cupom->user_id == null && 
                $slc_ult_id_cupom->caixa_id == null)
            {
                $count_qtd_id = ItemVenda::select('cupom_id')->where('cupom_id', $slc_ult_id_cupom->id)->count();
                
                $slc_ult_id_cupom->delete(); 
            }

            $cupom->save(); 
            
            $novo_id_cupom = Cupom::select('id')->orderBy('id', 'desc')->limit(1)->first();
            $id_cupom      = $novo_id_cupom->id;
          
            ItemVenda::select('cupom_id')
            ->orderBy('id', 'desc')->limit($count_qtd_id)
            ->update(['cupom_id' => $id_cupom]);
    
            return view('vendas.pdv', compact('dados_caixa', 'id_cupom'));
        }
    }

    function finalizaVenda(Request $request){
        $data              = $request->all();
    
        $numero_caixa      = $request->caixa_id_pdv;
        $slc_ult_id_cupom  = Cupom::orderBy('id', 'desc')->limit(1)->first();
        $cupom             = Cupom::get();

        $valor_recebido_formatado = str_replace('.', '', $request->valor_recebido);
        $total_venda_formatado    = str_replace('.', '', $request->total_venda);
        $desconto_formatado       = str_replace('.', '', $request->desconto);
        $troco_formatado          = str_replace('.', '', $request->troco);
        
        $data['cupom_id']       = $slc_ult_id_cupom->id;
        $data['caixa_id']       = $request->caixa_id_pdv;
        $data['valor_recebido'] = str_replace(',', '.', $valor_recebido_formatado);
        $data['total_venda']    = str_replace(',', '.', $total_venda_formatado);
        $data['troco']          = str_replace(',', '.', $troco_formatado);
        $data['desconto']       = str_replace(',', '.', $desconto_formatado);

        $vendido_caixa = Caixa::select('valor_vendido', 'total_caixa')
        ->where('id', $numero_caixa)->first();

        $valor_vendido = $vendido_caixa->valor_vendido + $data['total_venda'];
        $total_caixa   = $vendido_caixa->total_caixa + $data['total_venda'];

        Caixa::where('id', $numero_caixa)
        ->update(['valor_vendido' => $valor_vendido, 'total_caixa' => $total_caixa]);
        
        VendaCupom::create($data);

        if ($cupom->count() == 1)
        {
            Cupom::where('id', $slc_ult_id_cupom->id)->update([
                "user_id"   => $request->user_id,
                "caixa_id"  => $request->caixa_id_pdv,
                "nro_cupom" => 1
            ]);  
        } else {
            $nmro_cupom = Cupom::select("nro_cupom")
            ->orderBy('id', 'desc')
            ->skip(1)
            ->limit(1)
            ->first();
           
            $ult_numero_cupom = $nmro_cupom->nro_cupom + 1;
            
            Cupom::where('id', $slc_ult_id_cupom->id)->update([
                "user_id"   => $request->user_id,
                "caixa_id"  => $request->caixa_id_pdv,
                "nro_cupom" => $ult_numero_cupom
            ]);  
        }

        $novo_cupom = new Cupom();
        $novo_cupom->save();

        return redirect()->route('cupom.vendas');
    }

    function cupom(){
        
        $user_auth = Auth::user()->id;
        $emitente = Emitente::first();
        $cupom_id = Cupom::orderBy('id', 'desc')->skip(1)->limit(1)->first();
        $total    = null;

        $descricao_caixa = NumeroCaixa::join('caixas', 'numero_caixas.id' , '=', 'caixas.nro_caixa_id')
        ->select('numero_caixas.descricao AS descricao_caixa')
        ->where('caixas.user_abertura_id', $user_auth)
        ->where('caixas.status', 1)
        ->first();
      
        $itens = ItemVenda::join('products', 'products.id', '=', 'item_vendas.product_id')
        ->join('cupoms', 'cupoms.id', '=', 'item_vendas.cupom_id')
        ->select('products.nome', 'products.preco_venda', 'item_vendas.qtd', 'item_vendas.sub_total')
        ->where('cupoms.id', $cupom_id->id)
        ->get();

        $qtd_itens = $itens->count();
      
        $cupom = Cupom::join('users', 'users.id', '=', 'cupoms.user_id')
        ->join('caixas', 'caixas.id', '=', 'cupoms.caixa_id')
        ->join('venda_cupoms', 'venda_cupoms.cupom_id', '=', 'cupoms.id')
        ->select('users.name', 'venda_cupoms.total_venda', 'venda_cupoms.valor_recebido', 'venda_cupoms.troco', 'venda_cupoms.desconto', 'venda_cupoms.forma_pagamento', 'venda_cupoms.created_at')
        ->where('cupoms.id', $cupom_id->id)
        ->groupBy('users.name', 'venda_cupoms.total_venda', 'venda_cupoms.valor_recebido', 'venda_cupoms.troco', 'venda_cupoms.desconto', 'venda_cupoms.forma_pagamento','venda_cupoms.created_at')->get();
       
        $total = $cupom[0]->total_venda + $cupom[0]->desconto;

        $view = view('vendas.cupom', compact('descricao_caixa', 'emitente', 'itens', 'cupom', 'qtd_itens', 'total'));
        
        $pdf = PDF::loadHTML($view)->setPaper([0, 0, 807.874, 221.102], 'landscape');

        return $pdf->stream();
    }
}
