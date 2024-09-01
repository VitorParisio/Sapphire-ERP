<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioController extends Controller
{
    function relProduto()
    {
        return view('relatorios.relatorio_produto');
    }

    function relVenda()
    {
        return view('relatorios.relatorio_venda');
    }

    function filtroProduto(Request $request)
    {
        $dados_filtro_prod = '';
        $warning = '<i class="fas fa-exclamation-circle"></i> Necessário preencher todos os campos.';

        if ($request->data_de_produto == null || $request->data_ate_produto == null)
        {
            return response()->json(['msg_erro' => $warning]);
        }

        $filtro_prod = Product::join('categories','products.category_id', '=', 'categories.id')
        ->select('categories.categoria', 'products.nome', 'products.id', 'products.estoque', 'products.preco_compra', 'products.preco_venda')
        ->whereBetween(Product::raw('DATE(created_at)'), [$request->data_de_produto, $request->data_ate_produto])
        ->get();
        
        $total_venda_prod_rel = Product::select('preco_venda')
        ->whereBetween(Product::raw('DATE(created_at)'), [$request->data_de_produto, $request->data_ate_produto])
        ->sum('preco_venda');

        $total_compra_prod_rel = Product::select('preco_compra')
        ->whereBetween(Product::raw('DATE(created_at)'), [$request->data_de_produto, $request->data_ate_produto])
        ->sum('preco_compra');
        
        if (count($filtro_prod) > 0)
        {
            foreach ($filtro_prod as $data_filtro_prod)
            {
                $dados_filtro_prod .=
                '
                    <tr>
                        <td>'.$data_filtro_prod->id.'</td>
                        <td>'.$data_filtro_prod->categoria.'</td>
                        <td>'.$data_filtro_prod->nome.'</td>
                        <td>'.$data_filtro_prod->estoque.'</td>
                        <td>R$ '.number_format($data_filtro_prod->preco_compra, 2,',','.').'</td>
                        <td>R$ '.number_format($data_filtro_prod->preco_venda, 2,',','.').'</td>
                    </tr>
                '; 
            }
            
            $dados_filtro_prod .= '<tr><td>Total</td><td></td><td></td><td></td><td>R$ '.number_format($total_compra_prod_rel,2,',','.').'</td><td>R$ '.number_format($total_venda_prod_rel,2,',','.').'</td></tr>';
        } else {
            $dados_filtro_prod = '<tr><td colspan="6"><i>Pesquisa não encontrada.</i></td></tr>';
        }

       return response()->json(['dados_filtro_prod' => $dados_filtro_prod]);
    }

    function tst()
    {
        $pdf = Pdf::loadView('relatorios.relatorio_produto')->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->stream('tst.pdf');
    }
}