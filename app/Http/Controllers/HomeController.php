<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\VendaCupom;
use App\Models\Destinatario;
use App\Models\ItemVenda;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        if (Gate::allows('is_master') || Gate::allows('is_admin'))
        {
            $clientes = Destinatario::all()->count();
            $produtos = Product::all()->count();

            $mes_cliente        = [];
            $total_cliente      = [];
            $mes_venda          = [];
            $total_mes          = [];
            $totalMesEspecifico = [];
            
            $totalCompraMes = Product::select(Product::raw('MONTH(created_at) as mes'), Product::raw('SUM(preco_compra) as total'))
            ->where(Product::raw('MONTH(created_at)'), date('m'))
            ->where(Product::raw('YEAR(created_at)'), date('Y'))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

            $total_cliente_bar_grafico = Destinatario::select('created_at')
            ->get()
            ->groupBy(function($data){ 
                return Carbon::parse($data->created_at)->isoFormat('MMMM');
            });
          
            $totalVendasMes = VendaCupom::select(VendaCupom::raw('MONTH(created_at) as mes'), VendaCupom::raw('SUM(total_venda) as total'))
            ->where(VendaCupom::raw('MONTH(created_at)'), date('m'))
            ->where(VendaCupom::raw('YEAR(created_at)'), date('Y'))
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

            $meses_bar_grafico = VendaCupom::select('created_at', 'total_venda')
            ->where(VendaCupom::raw('YEAR(created_at)'), date('Y'))
            ->get()
            ->groupBy(function($data){ 
                return Carbon::parse($data->created_at)->isoFormat('MMMM');
            });

            $itens_vendidos = ItemVenda::join('products', 'products.id', '=', 'item_vendas.product_id')
            ->selectRaw('products.id, products.nome, products.estoque, products.preco_venda , SUM(item_vendas.qtd) as total_item_venda')
            ->where(ItemVenda::raw('MONTH(item_vendas.data_venda)'), date('m'))
            ->where(ItemVenda::raw('YEAR(item_vendas.data_venda)'), date('Y'))
            ->groupBy('products.id', 'products.nome', 'products.preco_venda', 'products.estoque','item_vendas.data_venda')
            ->get();

            foreach ($meses_bar_grafico as $key => $values)
            { 
                $mes_venda[] = $key;
            }
            
            foreach($mes_venda as $key => $values)
            {   
                for ($rows=0; $rows < count($meses_bar_grafico[$values]); $rows++) 
                { 
                    $totalMesEspecifico[$values][] = $meses_bar_grafico[$values][$rows]->total_venda;
                }   
            }
           
            foreach ($totalMesEspecifico as $key => $value)
            {
                $total_mes[$key] = array_sum($value);
            }

            foreach ($total_cliente_bar_grafico as $key => $values)
            { 
                $mes_cliente[]   = $key;
                $total_cliente[] = count($values);
            }
         
            $clienteLabel     = "'Comparativo - Clientes'";
            $totalVendasLabel = "'Comparativo - Vendas'";

            $mesClienteDados = json_encode($mes_cliente);
            $totalMesCliente = implode(',', $total_cliente);
           
            $mesVendasDado = json_encode($mes_venda);
            $totalMesDado  = implode(',', $total_mes);
          
            return view('home', compact('clientes', 'produtos', 'totalCompraMes','totalVendasMes', 'mesClienteDados', 'mesVendasDado', 'totalMesCliente', 'totalMesDado', 'totalVendasLabel', 'clienteLabel', 'itens_vendidos'));
        }

        return redirect('op_abre_caixa');
    }
}
