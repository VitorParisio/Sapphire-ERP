<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;

class VendasController extends Controller
{

    function index(){
      
        return view('vendas.index');
    }

    function pdv(){
      
        return view('vendas.pdv');
    }

    function finalizaVenda(Request $request){
        $data = $request->all();

        $valor_recebido_formatado = str_replace('.', '', $request->valor_recebido);
        $total_venda_formatado    = str_replace('.', '', $request->total_venda);
        //$desconto_formatado       = str_replace('.', '', $request->desconto);
        $troco_formatado          = str_replace('.', '', $request->troco);
        
        $data['valor_recebido'] = str_replace(',', '.', $valor_recebido_formatado);
        $data['total_venda']    = str_replace(',', '.', $total_venda_formatado);
        $data['troco']          = str_replace(',', '.', $troco_formatado);
        $data['desconto']       = 0.00;
        
        Venda::create($data);
    }
}
