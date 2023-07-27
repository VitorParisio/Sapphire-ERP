<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ItemVendaNfe;
use App\Models\Nfe;
use App\Models\ItemNotaTemp;
use Illuminate\Support\Facades\Validator;

class ItemVendaNfeController extends Controller
{
    function index()
    { 
        $itens = ItemNotaTemp::join('products', 'products.id', '=', 'item_nota_temps.product_id')
        ->join('item_venda_nves', 'item_venda_nves.id', '=', 'item_nota_temps.item_venda_nfe_id')
        ->select('products.nome', 'products.preco_venda', 'products.img', 'item_venda_nves.id AS item_venda_nfe_id', 'item_venda_nves.product_id', 'item_venda_nves.qtd', 'item_venda_nves.sub_total')
        ->get();

        $total_venda = ItemNotaTemp::join('item_venda_nves', 'item_venda_nves.id', '=', 'item_nota_temps.item_venda_nfe_id')
        ->sum('item_venda_nves.sub_total');

        return response()->json(['itens' => $itens, 'total_venda' => $total_venda]);
    }

    function store(Request $request)
    {
        $product_id = $request->produto_id;
        $nfe_id     = $request->nfe_id;
        $qtd        = $request->qtd > 0 ? $request->qtd : 1;

        $produto = Product::where('id', $product_id)->first();
        $nota    = Nfe::where('id', $nfe_id)->first();
        $item    = ItemNotaTemp::all();
     
        foreach($item as $data)
        {   
            if ($product_id == $data->product_id)
            {
                $itens = ItemVendaNfe::where('id', $data->item_venda_nfe_id)->first();

                $itens->qtd       += $qtd;
                $itens->sub_total  = $produto->preco_venda * $itens->qtd;

                $itens->update(['qtd' => $itens->qtd, 'sub_total' => $itens->sub_total]);
                
                $estoque_atualizado = $produto->estoque - $qtd;
                $produto->estoque   = $estoque_atualizado;

                $produto->update(['estoque' => $produto->estoque]);

                return;
            }
        }
        if ($qtd == 1)
        {
            $item_venda_nfe             = new ItemVendaNfe();
            $item_venda_nfe->product_id = $produto->id;
            $item_venda_nfe->nfe_id     = $nota->id;
            $item_venda_nfe->sub_total  = $produto->preco_venda * $qtd;
            $item_venda_nfe->data_venda = date("Y-m-d");
            $item_venda_nfe->save();

            $nota_temp = new ItemNotaTemp();
            $nota_temp->product_id = $produto->id;
            $nota_temp->item_venda_nfe_id = $item_venda_nfe->id;
            $nota_temp->save();

            $estoque_atualizado = $produto->estoque - $qtd;
            $produto->estoque   = $estoque_atualizado;

            $produto->where('id', $product_id)->update(['estoque' => $produto->estoque]);
        } 
        else 
        {
            $item_venda_nfe             = new ItemVendaNfe();
            $item_venda_nfe->product_id = $produto->id;
            $item_venda_nfe->nfe_id     = $nota->id;
            $item_venda_nfe->qtd        = $qtd;
            $item_venda_nfe->sub_total  = $produto->preco_venda * $qtd;
            $item_venda_nfe->data_venda = date("Y-m-d");
            $item_venda_nfe->save();

            $nota_temp = new ItemNotaTemp();
            $nota_temp->product_id    = $produto->id;
            $nota_temp->item_venda_nfe_id = $item_venda_nfe->id;
            $nota_temp->save();

            $estoque_atualizado = $produto->estoque - $qtd;
            $produto->estoque   = $estoque_atualizado;

            $produto->where('id', $product_id)->update(['estoque' => $produto->estoque]);
        }

    }

    function estoqueNegativo(Request $request)
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

    function destroy($item_venda_nfe_id, $product_id, $qtd)
    {
        $produto = Product::where('id', $product_id)->first();
        $item    = ItemVendaNfe::where('id', $item_venda_nfe_id)->first();

        $item->delete();

        $recupera_qtd = $produto->estoque + $qtd;
        $produto->update(['estoque' => $recupera_qtd]); 
    }

    function totalPagamento()
    {
        $total_venda_pagamento = ItemNotaTemp::join('item_venda_nves', 'item_venda_nves.id', '=', 'item_nota_temps.item_venda_nfe_id')
        ->sum('item_venda_nves.sub_total');
       
        return response()->json($total_venda_pagamento);
    }    

    function removeProdutos()
    {
        ItemNotaTemp::truncate();
    }

}
