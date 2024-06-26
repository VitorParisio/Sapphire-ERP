<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ItemVenda;
use App\Models\PDV;
use App\Models\Cupom;
// use Illuminate\Support\Facades\Validator;

class ItemVendaController extends Controller
{
    function index($produto_pdv)
    { 
        $produto = Product::join('item_vendas', 'products.id', '=', 'item_vendas.product_id')
        ->select('products.nome', 'products.preco_venda', 'products.preco_atacado', 'products.qtd_atacado', 'products.descricao', 'item_vendas.qtd', 'item_vendas.sub_total')
        ->where('products.cod_barra', '=', $produto_pdv)
        ->orWhere('products.nome', '=', $produto_pdv)
        ->get();

        $itens = PDV::join('products', 'products.id', '=', 'p_d_v_s.product_id')
        ->join('item_vendas', 'item_vendas.id', '=', 'p_d_v_s.item_venda_id')
        ->select('products.nome', 'products.preco_venda', 'products.img', 'products.preco_atacado', 'products.qtd_atacado','item_vendas.id AS item_venda_id', 'item_vendas.product_id', 'item_vendas.qtd', 'item_vendas.sub_total')
        ->get();
        
        $total_venda = PDV::join('item_vendas', 'item_vendas.id', '=', 'p_d_v_s.item_venda_id')
        ->sum('item_vendas.sub_total');

        $total_itens_mobile = PDV::count();
     
        return response()->json(['produto' => $produto, 'pdv' => $itens, 'total_venda' => $total_venda, 'total_itens_mobile' => $total_itens_mobile]);
    }

    function store(Request $request)
    {
        $cod_barra = $request->cod_barra;
        $qtd       = $request->qtd > 0 ? $request->qtd : 1;
        $slc_ult_id_cupom  = Cupom::orderBy('id', 'desc')->limit(1)->first();
       
        $produto = Product::select('cod_barra', 'nome','id', 'estoque', 'preco_venda', 'qtd_atacado', 'preco_atacado')
        ->where('cod_barra', $cod_barra)
        ->orWhere('nome', $cod_barra)
        ->first();
        
        $item = PDV::get();
    
        foreach($item as $data)
        {   
            if ($produto->id == $data->product_id)
            {
                $itens      = ItemVenda::where('id', $data->item_venda_id)->first();
                $itens->qtd += $qtd;

                if ($produto->qtd_atacado != null)
                  $itens->sub_total = $qtd >= $produto->qtd_atacado ? $produto->preco_atacado * $itens->qtd : $produto->preco_venda * $itens->qtd;
                else
                  $itens->sub_total = $produto->preco_venda * $itens->qtd;

                $itens->where('id', $data->item_venda_id)->update(['qtd' => $itens->qtd, 'sub_total' => $itens->sub_total]);
                
                $estoque_atualizado = $produto->estoque - $qtd;
              
                $produto->estoque = $estoque_atualizado;
               
                $produto->where('cod_barra', $cod_barra)
                ->orWhere('nome', $cod_barra)
                ->update(['estoque' => $produto->estoque]);

                return;
            }
        }
       
        if ($qtd == 1)
        {
            $item_venda             = new ItemVenda();
            $item_venda->product_id = $produto->id;
            $item_venda->caixa_id   = $request->caixa_id_pdv;
            $item_venda->cupom_id   = $slc_ult_id_cupom->id;
            $item_venda->qtd        = $qtd;
            $item_venda->sub_total  = $produto->preco_venda * $qtd;
            $item_venda->data_venda = date("Y-m-d");
            $item_venda->save();

            $pdv                = new PDV();
            $pdv->product_id    = $produto->id;
            $pdv->item_venda_id = $item_venda->id;

            $pdv->save();

            $estoque_atualizado = $produto->estoque - $qtd;
            $produto->estoque   = $estoque_atualizado;

            $produto->where('cod_barra', $cod_barra)
            ->orWhere('nome', $cod_barra)
            ->update(['estoque' => $produto->estoque]); 
        } 
        else 
        {
            $item_venda             = new ItemVenda();
            $item_venda->product_id = $produto->id;
            $item_venda->caixa_id   = $request->caixa_id_pdv;
            $item_venda->cupom_id   = $slc_ult_id_cupom->id;
            $item_venda->qtd        = $qtd;
            $item_venda->data_venda = date("Y-m-d");
            
            if ($produto->qtd_atacado != null)
              $item_venda->sub_total  = $qtd >= $produto->qtd_atacado ? $produto->preco_atacado * $qtd : $produto->preco_venda * $qtd;
            else
              $item_venda->sub_total  = $produto->preco_venda * $qtd;
            
            $item_venda->save();

            $pdv = new PDV();
            $pdv->product_id    = $produto->id;
            $pdv->item_venda_id = $item_venda->id;

            $pdv->save();

            $estoque_atualizado = $produto->estoque - $qtd;
            $produto->estoque   = $estoque_atualizado;

            $produto->where('cod_barra', $cod_barra)
            ->orWhere('nome', $cod_barra)
            ->update(['estoque' => $produto->estoque]);
        }
    }

    function estoqueNegativo(Request $request)
    {
        $cod_barra        = $request->cod_barra;
        
        $qtd              = $request->qtd > 0 ? $request->qtd : 1;
        $estoque_negativo = false;
       
        $produto = Product::select('cod_barra', 'nome','id', 'estoque', 'preco_venda')
        ->where('cod_barra', $cod_barra)
        ->orWhere('nome', $cod_barra)
        ->first();
       
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
    
    function getProdutoSearch(Request $request, $produto_search = null)
    {
      if ($request->ajax())
      {
          $produto_nome = Product::select('nome')
          ->where('nome','LIKE', $produto_search."%")
          ->limit(1)
          ->get();
      
          $output = '';
          
          if ($produto_search != null)
          {
              $output.='<ul class="list-group" style="display:block; position:relative; margin:41px 0px;">';
                  foreach($produto_nome as $nomes)
                  {
                      $output.='<li class="list-group-item item_search">'.strtoupper($nomes->nome).'</li>';
                  }
              $output.='</ul>';
          }
          else
          {
              $output = "";
          }
          
          return $output;
      }
        
        return view('vendas.pdv');
    }

    function getProdutoTable(Request $request, $query = null)
    {
        $produto_nome_busca = '';
        $total_row          = '';
    
        if ($request->ajax())
        {
          if ($query != '')
          {
            $produto = Product::select('id', 'nome', 'preco_venda', 'preco_atacado')
            ->where('nome','LIKE','%'.$query.'%')
            ->get(); 
          }
          else
          {
            $produto = Product::select('id', 'nome', 'preco_venda', 'preco_atacado')
            ->orderBy('nome', 'ASC')
            ->get();
          }
    
          $total_row = $produto->count();
          
          if ($total_row > 0)
          {
            foreach($produto as $row)
            {
              $produto_nome_busca .='
                <tr>
                  <td>'.$row->id.'</td>
                  <td class="produto_nome_busca"><a href="javascript:void(0);">'.strtoupper($row->nome).'</a></td>
                  <td>R$ '.number_format($row->preco_venda, 2, ',', '.').'</td>
                  <td>R$ '.number_format($row->preco_atacado, 2, ',', '.').'</td>
                </tr>
              ';
            }
          }
          else
          {
            $produto_nome_busca ='
              <tr>
                <td colspan="7" style="font-weight:100; font-size: 19px"><i>Produto não encontrado.</i></td>
              </tr>
            ';
          }
    
          $data = array(
            'produto_nome_busca' => $produto_nome_busca,
          );
    
          return response()->json($data);
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
