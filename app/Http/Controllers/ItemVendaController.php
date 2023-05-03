<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ItemVenda;
use App\Models\PDV;

class ItemVendaController extends Controller
{
    function index(Request $request, $cod_barra)
    { 
        $produto = Product::join('item_vendas', 'products.id', '=', 'item_vendas.product_id')
        ->select('products.nome', 'products.preco_venda', 'item_vendas.qtd', 'item_vendas.sub_total','products.descricao')
        ->where('products.cod_barra', '=', $cod_barra)->get();
        
        $pdv = PDV::join('products', 'products.id', '=', 'p_d_v_s.product_id')
        ->join('item_vendas', 'item_vendas.id', '=', 'p_d_v_s.item_venda_id')
        ->select('products.nome', 'products.preco_venda', 'products.img', 'item_vendas.id AS item_venda_id', 'item_vendas.product_id', 'item_vendas.qtd', 'item_vendas.sub_total')
        ->get();

        $total_venda = PDV::join('item_vendas', 'item_vendas.id', '=', 'p_d_v_s.item_venda_id')
        ->sum('item_vendas.sub_total');
        
        return response()->json(['pdv' => $pdv, 'total_venda' => $total_venda, 'produto' => $produto]);
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

    function store(Request $request)
    {
        $product_id = $request->produto_id;
        $qtd        = $request->qtd > 0 ? $request->qtd : 1;
        $output     = '';

        $produto = Product::where('id', $product_id)->first();
        
        $item = ItemVenda::get();
        
        foreach($item as $data)
        {   
            if ($produto->id == $data->product_id)
            {
                $data->qtd       += $qtd;
                $data->sub_total  = $produto->preco_venda * $data->qtd;

                $data->where('product_id', $produto->id)->update(['qtd' => $data->qtd, 'sub_total' => $data->sub_total]);
                
                $estoque_atualizado = $produto->estoque - $qtd;
                $produto->estoque   = $estoque_atualizado;

                $produto->where('id', $product_id)->update(['estoque' => $produto->estoque]);

                $dados = PDV::join('products', 'products.id', '=', 'p_d_v_s.product_id')
                ->join('item_vendas', 'item_vendas.id', '=', 'p_d_v_s.item_venda_id')
                ->get(); 
        
                foreach($dados as $dado)
                {
                    $img_prod = $dado->img ? '<img src="storage/'.$dado->img.'" alt="img_item" style="width:60px; height:60px; border-radius:30px;"/>' : '<i class="fas fa-image fa-3x" style="font-size:50px"></i>';
                    $output = '<li>'.$img_prod.'</li>
                               <li>'.$dado->nome.'</li>
                               <li>'.$dado->qtd.'</li>
                               <li>'.$dado->sub_total.'</li>';
                }
                
                return response()->json($output);
            }
        }
       
        if ($qtd == 1)
        {
            $item_venda             = new ItemVenda();
            $item_venda->product_id = $produto->id;
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
            $item_venda->qtd        = $qtd;
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

        $dados = PDV::join('products', 'products.id', '=', 'p_d_v_s.product_id')
        ->join('item_vendas', 'item_vendas.id', '=', 'p_d_v_s.item_venda_id')
        ->get(); 

        foreach($dados as $dado)
        {
            $img_prod = $dado->img ? '<img src="storage/'.$dado->img.'" alt="img_item" style="width:60px; height:60px; border-radius:30px;"/>' : '<i class="fas fa-image fa-3x" style="font-size:50px"></i>';
            $output = '<li>'.$img_prod.'</li>
                       <li>'.$dado->nome.'</li>
                       <li>'.$dado->qtd.'</li>
                       <li>'.$dado->sub_total.'</li>';
        }
        
        return response()->json($output);
    }


    function destroy(Request $request, $item_venda_id, $product_id, $qtd)
    {
        $produto = Product::where('id', $product_id)->first();
        $item    = ItemVenda::where('id', $item_venda_id)->first();

        $item->delete();

        $recupera_qtd = $produto->estoque + $qtd;
        $produto->update(['estoque' => $recupera_qtd]); 
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
