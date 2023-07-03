<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ItemVenda;
use App\Models\PDV;
use App\Models\Nfe;
use Illuminate\Support\Facades\Validator;

class ItemVendaController extends Controller
{
    function index(Request $request, $produto_id)
    { 
        /*PDV*/
        // $produto = Product::join('item_vendas', 'products.id', '=', 'item_vendas.product_id')
        // ->select('products.nome', 'products.preco_venda', 'item_vendas.qtd', 'item_vendas.sub_total','products.descricao')
        // ->where('products.cod_barra', '=', $produto_id)->get();
        // dd($produto);
        /*-------*/

        $itens = PDV::join('products', 'products.id', '=', 'p_d_v_s.product_id')
        ->join('item_vendas', 'item_vendas.id', '=', 'p_d_v_s.item_venda_id')
        ->select('products.nome', 'products.preco_venda', 'products.img', 'item_vendas.id AS item_venda_id', 'item_vendas.product_id', 'item_vendas.qtd', 'item_vendas.sub_total')
        ->get();

        $total_venda = PDV::join('item_vendas', 'item_vendas.id', '=', 'p_d_v_s.item_venda_id')
        ->sum('item_vendas.sub_total');

        return response()->json(['itens' => $itens, 'total_venda' => $total_venda]);
    }
    
    // function store(Request $request)
    // {
    //     $cod_barra = $request->cod_barra;
    //     $qtd       = $request->qtd > 0 ? $request->qtd : 1;
       
    //     $produto = Product::select('cod_barra', 'nome','id', 'estoque', 'preco_venda')
    //     ->where('cod_barra', $cod_barra)->first();
        
    //     $item = ItemVenda::get();
        
    //     foreach($item as $data)
    //     {   
    //         if ($produto->id == $data->product_id)
    //         {
    //             $data->qtd       += $qtd;
    //             $data->sub_total  = $produto->preco_venda * $data->qtd;

    //             $data->where('product_id', $produto->id)->update(['qtd' => $data->qtd, 'sub_total' => $data->sub_total]);
                
    //             $estoque_atualizado = $produto->estoque - $qtd;
    //             $produto->estoque   = $estoque_atualizado;

    //             $produto->where('cod_barra', $cod_barra)->update(['estoque' => $produto->estoque]);

    //             return;
    //         }
    //     }
       
    //     if ($qtd == 1)
    //     {
    //         $item_venda             = new ItemVenda();
    //         $item_venda->product_id = $produto->id;
    //         $item_venda->sub_total  = $produto->preco_venda * $qtd;
    //         $item_venda->data_venda = date("Y-m-d");
    //         $item_venda->save();

    //         $pdv = new PDV();
    //         $pdv->product_id = $produto->id;
    //         $pdv->item_venda_id = $item_venda->id;
    //         $pdv->save();

    //         $estoque_atualizado = $produto->estoque - $qtd;
    //         $produto->estoque   = $estoque_atualizado;

    //         $produto->where('cod_barra', $cod_barra)->update(['estoque' => $produto->estoque]);
    //     } 
    //     else 
    //     {
    //         $item_venda             = new ItemVenda();
    //         $item_venda->product_id = $produto->id;
    //         $item_venda->qtd        = $qtd;
    //         $item_venda->sub_total  = $produto->preco_venda * $qtd;
    //         $item_venda->data_venda = date("Y-m-d");
    //         $item_venda->save();

    //         $pdv = new PDV();
    //         $pdv->product_id = $produto->id;
    //         $pdv->item_venda_id = $item_venda->id;
    //         $pdv->save();

    //         $estoque_atualizado = $produto->estoque - $qtd;
    //         $produto->estoque   = $estoque_atualizado;

    //         $produto->where('cod_barra', $cod_barra)->update(['estoque' => $produto->estoque]);
    //     }
    // }

    function storeNfe(Request $request)
    {
        $product_id = $request->produto_id;
        $nfe_id     = $request->nfe_id;
        $qtd        = $request->qtd > 0 ? $request->qtd : 1;

        $produto = Product::where('id', $product_id)->first();
        $nota    = Nfe::where('id', $nfe_id)->first();
        $item    = PDV::all();
     
        foreach($item as $data)
        {   
            if ($product_id == $data->product_id)
            {
                $items = ItemVenda::where('id', $data->item_venda_id)->first();

                $items->qtd       += $qtd;
                $items->sub_total  = $produto->preco_venda * $items->qtd;

                $items->update(['qtd' => $items->qtd, 'sub_total' => $items->sub_total]);
                
                $estoque_atualizado = $produto->estoque - $qtd;
                $produto->estoque   = $estoque_atualizado;

                $produto->update(['estoque' => $produto->estoque]);

                return;
            }
        }
        if ($qtd == 1)
        {
            $item_venda             = new ItemVenda();
            $item_venda->product_id = $produto->id;
            $item_venda->nfe_id     = $nota->id;
            $item_venda->sub_total  = $produto->preco_venda * $qtd;
            $item_venda->data_venda = date("Y-m-d");
            $item_venda->save();

            $pdv = new PDV();
            $pdv->product_id = $produto->id;
            $pdv->item_venda_id = $item_venda->id;
            $pdv->save();

            $estoque_atualizado = $produto->estoque - $qtd;
            $produto->estoque   = $estoque_atualizado;

            $produto->where('id', $product_id)->update(['estoque' => $produto->estoque]);
        } 
        else 
        {
            $item_venda             = new ItemVenda();
            $item_venda->product_id = $produto->id;
            $item_venda->nfe_id     = $nota->id;
            $item_venda->qtd        = $qtd;
            $item_venda->sub_total  = $produto->preco_venda * $qtd;
            $item_venda->data_venda = date("Y-m-d");
            $item_venda->save();

            $pdv = new PDV();
            $pdv->product_id    = $produto->id;
            $pdv->item_venda_id = $item_venda->id;
            $pdv->save();

            $estoque_atualizado = $produto->estoque - $qtd;
            $produto->estoque   = $estoque_atualizado;

            $produto->where('id', $product_id)->update(['estoque' => $produto->estoque]);
        }

    }

    function destroy($item_venda_id, $product_id, $qtd)
    {
        $produto = Product::where('id', $product_id)->first();
        $item    = ItemVenda::where('id', $item_venda_id)->first();

        $item->delete();

        $recupera_qtd = $produto->estoque + $qtd;
        $produto->update(['estoque' => $recupera_qtd]); 
    }

    function estoqueNegativoNfe(Request $request)
    {   
        $nota = Nfe::select("id")
        ->orderBy('id', 'desc')
        ->limit(1)
        ->first();

        if ($nota->id != $request->nfe_id) 
            return response()->json('reload');
        
        $data             = $request->all();
        $qtd              = $data['qtd'] > 0 ? $request->qtd : 1;
        $estoque_negativo = false;

        $validator = Validator::make($data, [
            'produto_id' => 'required|not_in:0'
        ],
        [
            'produto_id.required' => 'Campo "Produto" deve ser preenchido.',
            'produto_id.not_in'   => 'Campo "Produto" deve ser preenchido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                    'error' => $validator->errors()->all()
            ]);
        }

        $produto = Product::select('nome', 'estoque')->where('id', $data['produto_id'])->first();
     
        if ($produto->estoque <  $qtd)
        {
            $estoque_negativo = true;
            return response()->json($estoque_negativo);
        }
        else{
            return response()->json($estoque_negativo);
        } 
    }

    function estoqueNegativo(Request $request)
    {
        $cod_barra = $request->cod_barra;
        $qtd       = $request->qtd > 0 ? $request->qtd : 1;
        $estoque_negativo = false;

        $produto = Product::select('cod_barra', 'nome','id', 'estoque', 'preco_venda')
        ->where('cod_barra', $cod_barra)->first();

        if ($produto != null){
            if ($produto->estoque < $qtd)
            {
                $estoque_negativo = true;
                return response()->json($estoque_negativo);
            }
            else{
                return response()->json($estoque_negativo);
            }
        }else{
            return response()->json(['error' => 'Produto não cadastrado']);
        }

    }

    function totalPagamento()
    {
        $total_venda_pagamento = PDV::join('item_vendas', 'item_vendas.id', '=', 'p_d_v_s.item_venda_id')
        ->sum('item_vendas.sub_total');
       
        return response()->json($total_venda_pagamento);
    }    

    function removeProdutos()
    {
        PDV::truncate();
    }
}
