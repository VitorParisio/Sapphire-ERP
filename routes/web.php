<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendasController;
use App\Http\Controllers\ItemVendaController;
use App\Http\Controllers\EmitenteController;
use App\Http\Controllers\DestinatarioController;
use App\Http\Controllers\NfeController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/', function () {
    return view('auth.login');
    // return view('home');
});

 Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::get('notas_fiscais', [NfeController::class, 'index'])->name('index.nfe');
    Route::get('cadastrar_nota', [NfeController::class, 'create'])->name('create.nfe');
    Route::post('cadastra_nfe', [NfeController::class, 'cadastraNfe'])->name('cadastra.nfe');
    Route::get('gera_nfe/{id}', [NfeController::class, 'geraNfe'])->name('gera.nfe');
    Route::get('consulta_nfe', [NfeController::class, 'consultaNfe'])->name('consulta.nfe');
    Route::get('imprime_nfe/{id}', [NfeController::class, 'imprimeNfe'])->name('imprimi.nfe');
    Route::post('cancela_nfe', [NfeController::class, 'cancelaNfe'])->name('cancelar.nfe');
    Route::get('carta_correcao_nfe', [NfeController::class, 'cartaCorrecaoNfe'])->name('carta.correcao.nfe');
    //Route::get('email_nfe', [NfeController::class, 'emailNfe'])->name('email.nfe');
    Route::get('status_sefaz', [NfeController::class, 'statusSefaz'])->name('status.sefaz');

    Route::get('/empresas', [EmitenteController::class, 'index'])->name('index.empresa');
    Route::post('/empresas', [EmitenteController::class, 'store'])->name('store.empresa');
    Route::get('/empresas/page', [EmitenteController::class, 'getEmpresa'])->name('empresa.search_empresa');
    Route::post('/update_empresa/{id}', [EmitenteController::class, 'update'])->name('empresa.update');
    Route::delete('/delete_empresa/{id}', [EmitenteController::class, 'destroy'])->name('empresa.delete');

    Route::get('/clientes', [DestinatarioController::class, 'index'])->name('index.clientes');
    Route::post('/clientes', [DestinatarioController::class, 'store'])->name('store.clientes');
    Route::get('/clientes/page', [DestinatarioController::class, 'getCliente'])->name('clientes.search_client');
    Route::post('/update_cliente/{id}', [DestinatarioController::class, 'update'])->name('cliente.update');
    Route::delete('/delete_cliente/{id}', [DestinatarioController::class, 'destroy'])->name('cliente.delete');

    Route::get('/categorias', [CategoryController::class, 'index'])->name('categorias.index');
    Route::post('/categorias', [CategoryController::class, 'store'])->name('categorias.store');
    Route::post('/update_categoria/{id}', [CategoryController::class, 'update'])->name('categorias.update');
    Route::delete('/categoria_delete/{id}', [CategoryController::class, 'destroy'])->name('categorias.delete');
    Route::get('/select_categoria/{id?}', [CategoryController::class, 'selectCategoria'])->name('categorias.select_categoria');
    Route::get('/categoria_delete', [CategoryController::class, 'getCategory'])->name('produtos.search_category');

    Route::get('/produtos', [ProductController::class, 'index'])->name('produtos');
    Route::post('/produtos', [ProductController::class, 'store'])->name('produtos.store');
    Route::post('/update_produto/{id}', [ProductController::class, 'update'])->name('produtos.update');
    Route::delete('/delete_produto/{id}', [ProductController::class, 'destroy'])->name('produtos.delete');
    Route::get('/produtos/page', [ProductController::class, 'getProduct'])->name('produtos.search_product');
    Route::get('/produtos/item', [ProductController::class, 'searchItem'])->name('produtos.search_item');
    Route::get('/produtos/page-data', [ProductController::class, 'paginationProduct'])->name('produtos.pagination_produto');
    Route::get('/total_itens', [ProductController::class, 'totalItem'])->name('produtos.total_item');
    Route::get('/notifications', [ProductController::class, 'notifications'])->name('produtos.notifications');
   
    Route::get('/getproduto/{cod_barra}', [ItemVendaController::class, 'index'])->name('item_vendas.get_produto');
    Route::post('/add_produto_nfe', [ItemVendaController::class, 'storeNfe'])->name('item_vendas.add_produto_nfe');
    Route::delete('/deletaprodutocod/{item_venda_id}/{product_id}/{qtd}', [ItemVendaController::class, 'destroy'])->name('item_vendas.deletaprotudocod');
    Route::get('/totalpagamento', [ItemVendaController::class, 'totalPagamento'])->name('item_vendas.total_pagamento');
    Route::delete('/deletaprodutos', [ItemVendaController::class, 'removeProdutos'])->name('item_vendas.deleta_produtos');
    Route::get('/estoque_negativo_nfe', [ItemVendaController::class, 'estoqueNegativoNfe'])->name('intem_vendas.estoque_negativo');


    //Route::get('/estoque_negativo', [ItemVendaController::class, 'estoqueNegativo'])->name('item_vendas.estoque_negativo');
    //Route::post('/addproduto', [ItemVendaController::class, 'store'])->name('item_vendas.add_produto');
    //Route::get('/vender', [VendasController::class, 'index'])->name('vender.vendas');
    //Route::get('/pdv', [VendasController::class, 'pdv'])->name('pdv.vendas');
    //Route::post('/finalizavenda', [VendasController::class, 'finalizaVenda'])->name('finaliza_venda.vendas');

 });

