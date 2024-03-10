<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VendasController;
use App\Http\Controllers\ItemVendaController;
use App\Http\Controllers\EmitenteController;
use App\Http\Controllers\DestinatarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\NfeController;
use App\Http\Controllers\ItemVendaNfeController;
use App\Http\Controllers\CaixaController;
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

// ------------ ROUTE AUTH ------------
// Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('login', [LoginController::class, 'login']);
// Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Reset password
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Route::group(['middleware' => 'register.domain.main'], function(){
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('/register', [RegisterController::class, 'register']);

// });
// ------------------------------------

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/funcionario', function () {
    return view('login.index');
});

Route::get('/404_error', function () {
    return view('404');
})->name('404_error');

Route::group(['middleware' => 'auth'], function (){
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/empresas', [EmitenteController::class, 'index'])->name('index.empresa');
    Route::post('/empresas', [EmitenteController::class, 'store'])->name('store.empresa');
    Route::get('/empresas/page', [EmitenteController::class, 'getEmpresa'])->name('empresa.search_empresa');
    Route::post('/update_empresa/{id}', [EmitenteController::class, 'update'])->name('empresa.update');
    Route::delete('/delete_empresa/{id}', [EmitenteController::class, 'destroy'])->name('empresa.delete');

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('index.usuarios');

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
    Route::get('/estoque_baixo', [ProductController::class, 'estoqueBaixo'])->name('estoque_baixo.notifications');

    Route::get('/getproduto/{produto_pdv}', [ItemVendaController::class, 'index'])->name('item_vendas.get_produto');
    Route::post('/addproduto', [ItemVendaController::class, 'store'])->name('item_vendas.add_produto');
    Route::delete('/deletaprodutocod/{item_venda_id}/{product_id}/{qtd}', [ItemVendaController::class, 'destroy'])->name('item_vendas.deletaprotudocod');
    Route::get('/totalpagamento', [ItemVendaController::class, 'totalPagamento'])->name('item_vendas.total_pagamento');
    Route::delete('/deletaprodutos', [ItemVendaController::class, 'removeProdutos'])->name('item_vendas.deleta_produtos');
    Route::get('/estoque_negativo', [ItemVendaController::class, 'estoqueNegativo'])->name('item_vendas.estoque_negativo');
    Route::get('/getprodutosearch/{produto_search?}', [ItemVendaController::class, 'getProdutoSearch'])->name('item_vendas.get_produto_search');
    Route::get('/getprodutotable/{query?}', [ItemVendaController::class, 'getProdutoTable'])->name('item_vendas.get_produto_table');

    Route::get('/getprodutonfe/{produto_id}', [ItemVendaNfeController::class, 'index'])->name('item_vendas_nfe.get_produto');
    Route::post('/add_produto_nfe', [ItemVendaNfeController::class, 'store'])->name('item_vendas_nfe.add_produto_nfe');
    Route::delete('/deletaprodutocodnfe/{item_venda_nfe_id}/{product_id}/{qtd}', [ItemVendaNfeController::class, 'destroy'])->name('item_vendas_nfe.deletaprotudocod');
    Route::get('/totalpagamentonfe', [ItemVendaNfeController::class, 'totalPagamento'])->name('item_vendas_nfe.total_pagamento');
    Route::delete('/deletaprodutosnfe', [ItemVendaNfeController::class, 'removeProdutos'])->name('item_vendas_nfe.deleta_produtos');
    Route::get('/estoque_negativo_nfe', [ItemVendaNfeController::class, 'estoqueNegativo'])->name('item_vendas_nfe.estoque_negativo');

    Route::get('/caixas', [CaixaController::class, 'index'])->name('index.caixa');
    Route::get('/get_caixas', [CaixaController::class, 'getCaixas'])->name('get_caixas.caixa');
    Route::post('/abertura_caixa', [CaixaController::class, 'abrirCaixa'])->name('abrir_caixa.caixa');
    Route::post('/abertura_caixa_op', [CaixaController::class, 'abrirCaixaOp'])->name('abrir_caixa_op.caixa');
    Route::get('/get_caixa_aberto/{id}', [CaixaController::class, 'getCaixaAberto'])->name('get_caixa_aberto.caixa');
    Route::get('/op_abre_caixa', [CaixaController::class, 'opAbreCaixa'])->name('op_abre_caixa.caixa');
    Route::get('/suprimento_valores/{descricao_caixa}', [CaixaController::class, 'suprimentoCaixaValores'])->name('suprimento_valores.caixa');
    Route::post('/suprimento', [CaixaController::class, 'suprimentoCaixa'])->name('suprimento.caixa');
    Route::get('/sangria_valores/{descricao_caixa}', [CaixaController::class, 'sangriaCaxiaValores'])->name('sangria.caixa');
    Route::post('/retirada_caixa', [CaixaController::class, 'retiradaSangria'])->name('retirada_sangria.caixa');
    Route::get('/fecha_caixa', [CaixaController::class, 'fechaCaixa'])->name('fecha_caixa.caixa');
    Route::post('/fechamento_caixa', [CaixaController::class, 'fechamentoCaixa'])->name('fechamento_caixa.caixa');
    Route::get('/imprimir_cupom_fechamento/{caixa_id}', [CaixaController::class, 'cupomFechamento'])->name('cupom.fechamento');
    Route::get('/caixa_logout/{caixa_id}', [CaixaController::class, 'caixaLogout'])->name('caixa_logout.fechamento');
    Route::get('/op_logout', [CaixaController::class, 'opLogout'])->name('op_logout.caixa');

    Route::get('/vender', [VendasController::class, 'index'])->name('vender.vendas');
    Route::get('/cash_verify/{id}', [VendasController::class, 'cashVerify'])->name('verify_cash.vendas');
    Route::get('/pdv', [VendasController::class, 'pdv'])->name('pdv.vendas');
    Route::post('/finalizavenda', [VendasController::class, 'finalizaVenda'])->name('finaliza_venda.vendas');
    Route::get('/cupom', [VendasController::class, 'cupom'])->name('cupom.vendas');

    Route::get('notas_fiscais', [NfeController::class, 'index'])->name('index.nfe');
    Route::get('cadastrar_nota', [NfeController::class, 'create'])->name('create.nfe');
    Route::post('cadastra_nfe', [NfeController::class, 'cadastraNfe'])->name('cadastra.nfe');
    Route::get('gera_nfe/{id}', [NfeController::class, 'geraNfe'])->name('gera.nfe');
    Route::get('consulta_nfe', [NfeController::class, 'consultaNfe'])->name('consulta.nfe');
    Route::get('imprime_nfe/{id}', [NfeController::class, 'imprimeNfe'])->name('imprimi.nfe');
    Route::post('cancela_nfe', [NfeController::class, 'cancelaNfe'])->name('cancelar.nfe');
    Route::post('carta_correcao_nfe', [NfeController::class, 'cartaCorrecaoNfe'])->name('carta.correcao.nfe');
    //Route::get('email_nfe', [NfeController::class, 'emailNfe'])->name('email.nfe');
    Route::get('itens_nota_nfe/{id}', [NfeController::class, 'itensNotaNfe'])->name('itens_nota.nfe');
    Route::get('status_sefaz', [NfeController::class, 'statusSefaz'])->name('status.sefaz');


    Route::fallback(function(){
        return redirect()->back();
    });
});

