@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <div style="display:flex; justify-content:space-between" >
        <h5 class="m-0 text-dark"><i class="fas fa-play-circle"></i> Controle de estoque</h5>
        <ol class="breadcrumb float-sm-right" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Estoque</li>
        </ol>
    </div>
    <div class="errors"></div>
@stop

@section('content')
    <div class="card card-primary card-tabs">
        <div class="tabcontainer">
            <div>
                <ul class="tabheading">
                    <li class="active" rel="tab1" >
                        <a href="#">
                            <small><i class="fas fa-tags"></i> Categorias</small>
                        </a>
                    </li>
                    <li rel="tab2">
                        <a href="#">
                            <small><i class="fas fa-list fa-x3"></i> Lista de produtos</small>
                        </a> 
                    </li>
                    <li rel="tab3">
                        <a href="#">
                            <small><i class="fas fa-plus fa-x3"></i> Novo produto</small>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tabcontainer">
                <div class="tabbody active" id="tab1" style="display: block;">  
                    <div class="total_categorias" style="font-size:13px; position:absolute; margin: -18px 0; font-weight:900"></div>
                    <input class="search_categoria" id="search_categoria" name="search_categoria" type="text" placeholder="Pesquisar categoria" style="outline: none" autocomplete="off">
                    <hr>
                    <div class="categorias_produto">
                        <div class="lista_categoria">
                            <table class="table-striped tb_categorias">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Categorias</th>
                                        <th>Descrições</th>
                                        <th colspan="2">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="adicionar_categoria">
                            <div class="header_card_categoria">
                            <i class="fas fa-plus"></i>&nbsp<span>Adicionar Categoria</span>
                            </div>
                            <div> 
                                <label for="categoria">Categoria*</label>
                                <input type="text" name="categoria" id="categoria" value="{{old('categoria')}}" autocomplete="off">
                                <label for="descricao_categoria">Descrição</label>
                                <textarea rows="5" name="descricao" id="descricao_categoria" value="{{old('descricao')}}"></textarea>
                            </div>
                            <button class="btn_add_categoria" onclick="addCategoria()">Adicionar</button>
                        </div>
                    </div>
                </div>
                <div class="tabbody" id="tab3" style="display: none;">
                    <form id="form_cadastro_produto" action="{{route('produtos.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row cadastro_produtos_inputs">
                            <div class="col-md-12">
                                <h4 style="background:teal; color:#FFF; padding:5px; font-size:16px; margin-bottom:15px;"><i class="fas fa-info-circle"></i> Informações básicas</h4>
                            </div>
                            <div class="col-md-3">
                                <label for="select_categoria">Categoria*
                                    <select class="form-control select_categoria" id="select_categoria" name="category_id"></select>
                                </label>
                                <label for="estoque">Estoque atual*
                                    <input type="text" class="form-control" name="estoque" id="estoque" placeholder="" value="{{old('estoque')}}" autocomplete="off">
                                </label>
                                <label for="preco_compra">Preço custo(R$)
                                    <input type="text" class="form-control" name="preco_compra" id="preco_compra" value="{{old('preco_compra')}}" style="text-align: right" autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="nome">Produto*
                                    <input type="text" class="form-control" name="nome" id="nome" placeholder="" value="{{ old('nome')}}" autocomplete="off">
                                </label>
                                <label for="estoque_minimo">Estoque mínimo
                                    <input type="text" class="form-control" name="estoque_minimo" id="estoque_minimo" placeholder="" value="{{old('estoque_minimo')}}" autocomplete="off">
                                </label>
                                <label for="preco_venda">Preço venda(R$)*
                                    <input type="text" class="form-control" name="preco_venda" id="preco_venda" value="{{old('preco_venda')}}" style="text-align: right" autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="cod_barra">EAN (Códgio Barra)
                                    <input type="text" class="form-control" name="cod_barra" id="cod_barra" placeholder="" value="{{ old('cod_barra')}}" autocomplete="off">
                                </label>
                                <label for="ucom">Unid. comercial*
                                    <input type="text" class="form-control" name="ucom" id="ucom" value="UNID" autocomplete="off" disabled>
                                </label>
                                <label for="preco_minimo">Preço mínimo(R$)
                                    <input type="text" class="form-control" name="preco_minimo" id="preco_minimo" value="{{old('preco_minimo')}}" style="text-align: right" autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="descricao">Descrição
                                    <input type="text" class="form-control" name="descricao" id="descricao" value="{{old('descricao')}}" autocomplete="off">
                                </label>
                                <label for="validade">Validade
                                    <input type="date" class=" form-control datetimepicker-input" data-target="#reservationdate" name="validade" id="validade" value="{{old('validade')}}">
                                </label>
                                <div>
                                    <input type="file" name="img" id="img_produto_input" accept="image/*">
                                    <label>Imagem:</label>
                                    <label for="img_produto_input" class="img_produto_input">
                                        <span>Procurar</span>
                                        <span>Selecionar imagem</span>
                                    </label>
                                    <small>Tamanho máximo: 2MB</small>
                                </div> 
                            </div>
                            <div class="col-md-12">
                                <h4 style="background:teal; color:#FFF; padding:5px; font-size:16px; margin-bottom:15px;"><i class="fas fa-file-invoice-dollar"></i> Dados fiscais</h4>
                            </div>
                            <div class="col-md-3">
                                 <label for="ncm">NCM 
                                    <input type="text" class="form-control" name="ncm" id="ncm" value="{{ old('ncm')}}" autocomplete="off">
                                </label> 
                            </div>
                            <div class="col-md-3">
                                <label for="cest">CEST
                                    <input type="text" class="form-control" name="cest" id="cest" value="{{old('cest')}}" autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="extipi">IPI
                                    <input type="text" class="form-control" name="extipi" id="extipi" value="{{old('extipi')}}"  autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="cfop">CFOP
                                    <input type="text" class="form-control" name="cfop" id="cfop" value="5101"  autocomplete="off" disabled>
                                </label>
                            </div> 
                            <div class="col-md-3">
                                <label for="origem">Origem
                                    <input type="text" class="form-control" name="origem" id="origem" value="0 - Nacional"  autocomplete="off" disabled>
                                </label>
                            </div> 
                            <label for="situacao_tributaria">Situação tributária
                                <input type="text" class="form-control" name="situacao_tributaria" id="situacao_tributaria" value="102 - Tributada pelo Simples Nacional sem permissão de crédito">
                            </label>
                            <label for="ceantrib" style="display: none">EAN Unid. Tributável
                                <input type="text" class="form-control" name="ceantrib" id="ceantrib" value="{{ old('ceantrib')}}">
                            </label>
                            <label for="qtrib" style="display: none">Qtd. Tributável
                                <input type="text" class="form-control" name="qtrib" id="qtrib" value="{{old('qtrib')}}">
                            </label>
                            <label for="vuntrib" style="display: none">Valor Unid. Tributável(R$)*
                                <input type="text" class="form-control" name="vuntrib" id="vuntrib" value="{{old('vuntrib')}}">
                            </label>
                        </div>
                        <hr>
                        <div style="display:flex; gap:4px; margin-top:15px;">
                            <button type="submit" style="border: none; background: #3f6792; color: #FFF;">Adicionar</button><br>
                            <input type="reset" value="Cancel" class="btn btn-danger">
                        </div>
                    </form>
                </div>
                <div class="tabbody" id="tab2" style="display: none;">
                    <div class="lista_produto">
                        <span id="total_produtos" style="font-size:13px; position:absolute; margin: -34px 0; font-weight:900"></span>
                        <input class="search_product" id="search_product" name="search_product" type="text" placeholder="Pesquisar produto" style="outline: none" autocomplete="off">
                        <hr>
                        <div style="height: 465px; width:100%; overflow:auto;">
                            <table class="table table-striped lista_produto_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Categoria</th>
                                        <th>Produto</th>
                                        <th>Preço venda</th>
                                        <th>Estoque atual</th>
                                        <th colspan=3>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div>
                        @include('modals.produto.detalhe')
                    </div>
                    <div>
                        @include('modals.produto.editar')
                    </div>
                </div>                   
            </div>
            <div class="tabbody" id="tab4" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; flex-wrap:wrap; align-items:center;">
                            <h3 class="card-title">Total de itens</h3><br>
                            <input class="search_item" id="search_item" name="search_item" type="text" placeholder="Pesquisar..." style="outline: none" autocomplete="off">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="total_itens">
                            <table class="table-striped tb_total_itens">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Produto</th>
                                        <th>Estoque inicial</th>
                                        <th>Vendidos</th>
                                        <th>Estoque atual</th>
                                        <th>Sub-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>  
            </div> 
        </div>
        <div>
            @include('modals.categoria.editar')
        </div>
    </div>
@stop
@push('scripts')
<script src="{{ asset('js/mask.js') }}"></script>
<script>
    $(function(){
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    });
</script>
@endpush
