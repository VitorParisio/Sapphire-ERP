@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <div id="preloader_full"><img src="{{asset('img/preloader.gif')}}" alt=""></div>
    <div class="mobile_path" style="display:flex; justify-content:space-between" >
        <h5 class="m-0 text-dark"><i class="fas fa-play-circle"></i> Produtos</h5>
        <ol class="breadcrumb float-sm-right" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Produtos</li>
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
                    <div class="mobile_search">
                        <input id="search_categoria" class="search_categoria" name="search_categoria" type="text" placeholder="Pesquisar categoria" style="outline: none" autocomplete="off">
                        <div id="total_categorias" class="total_categorias" style="font-size:13px; position:absolute; margin: -18px 0; font-weight:900"></div>
                    </div>
                    <hr>
                    <div class="categorias_produto">
                        <div class="lista_categoria">
                            <table class="table-striped tb_categorias mobile-tables">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Categoria</th>
                                        <th>Descrição</th>
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
                                <label for="nome">Produto*
                                    <input type="text" class="form-control" name="nome" id="nome" placeholder="" value="{{ old('nome')}}" autocomplete="off">
                                </label>
                                <label for="preco_compra">Preço Custo(R$)*
                                    <input type="text" class="form-control" name="preco_compra" id="preco_compra" value="{{old('preco_compra')}}" style="text-align: right" autocomplete="off">
                                </label>
                                <label for="qtd_atacado">Qtd. Atacado
                                    <input type="text" class="form-control" name="qtd_atacado" id="qtd_atacado" value="{{old('qtd_atacado')}}" style="text-align: right" autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="estoque">Estoque Atual*
                                    <input type="text" class="form-control" name="estoque" id="estoque" placeholder="" value="{{old('estoque')}}" autocomplete="off">
                                </label>
                                <label for="estoque_minimo">Estoque Mínimo*
                                    <input type="text" class="form-control" name="estoque_minimo" id="estoque_minimo" placeholder="" value="{{old('estoque_minimo')}}" autocomplete="off">
                                </label>
                                <label for="preco_venda">Preço Venda(R$)*
                                    <input type="text" class="form-control" name="preco_venda" id="preco_venda" value="{{old('preco_venda')}}" style="text-align: right" autocomplete="off">
                                </label>
                                <label for="preco_atacado">Preço Atacado(R$)
                                    <input type="text" class="form-control" name="preco_atacado" id="preco_atacado" value="{{old('preco_atacado')}}" style="text-align: right" autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="cod_barra">EAN (Códgio Barra)
                                    <input type="text" class="form-control" name="cod_barra" id="cod_barra" placeholder="" value="{{ old('cod_barra')}}" autocomplete="off">
                                </label>
                                <label for="ucom">Unid. comercial*
                                    <input type="text" class="form-control" name="ucom" id="ucom" value="UNID" autocomplete="off" disabled>
                                </label>
                                <label for="lucro_real">Lucro Real(R$)
                                    <input type="text" class="form-control" name="margem_lucro" id="lucro_real" autocomplete="off">
                                </label>
                                <label for="validade">Validade
                                    <input type="date" class=" form-control datetimepicker-input" data-target="#reservationdate" name="validade" id="validade" value="{{old('validade')}}">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="descricao">Descrição
                                    <input type="text" class="form-control" name="descricao" id="descricao" value="{{old('descricao')}}" autocomplete="off">
                                </label><br>
                                <label for="preco_minimo">Preço Mínimo(R$)
                                    <input type="text" class="form-control" name="preco_minimo" id="preco_minimo" value="{{old('preco_minimo')}}" style="text-align: right" autocomplete="off">
                                </label>
                                <label for="lucro_per">Lucro %
                                    <input type="text" class="form-control" name="margem_lucro_per" id="lucro_per" value="{{old('margem_lucro_per')}}" style="text-align: right" autocomplete="off">
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
                            <label for="situacao_tributaria">Situação Tributária
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
                        <div class="mobile_search">
                            <input class="search_product" id="search_product" name="search_product" type="text" placeholder="Pesquisar produto" style="outline: none" autocomplete="off">
                            <div class="total_estoque_baixo">
                                <span id="total_produtos" style="font-size:13px; position:absolute; margin: -34px 0; font-weight:900"></span>
                                <a class="estoque_baixo_modal" href="javascript:void(0);" style="font-size: 13px; position: absolute; margin: -34px 99px; font-weight: 900; color:red; display:none"><i>estoque baixo</i></a>
                            </div>
                        </div>
                        <hr>
                        <div style="height: 465px; width:100%; overflow:auto;">
                            <table class="table table-striped lista_produto_table mobile-tables">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Código</th>
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
            @include('modals.produto.detalhe')
        </div>
        <div>
            @include('modals.produto.editar')
        </div>
        <div>
            @include('modals.categoria.editar')
        </div>
        <div>
            @include('modals.estoque_baixo')
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

        $('#cfop').prop('disabled', true);
        $('#situacao_tributaria').prop('disabled', true);
        $('#origem').prop('disabled', true);
        $('.unidade_medida_editar').prop('disabled', true);
        $('#cfop_editar').prop('disabled', true);
        $('#origem_editar').prop('disabled', true);
        $('#lucro_real').attr("readonly","true");
        $('#lucro_per').attr('readonly', "true");
        $('#lucro_real_editar').attr('readonly', "true");
        $('#lucro_per_editar').attr('readonly', "true");
        $('#situacao_tributaria_editar').prop('disabled', true);

        $('.search_category').on('keyup',function(){
            var value = $(this).val();
            searchCategory(value);
        });

        $('.search_product').on('keyup',function(){
            var value = $(this).val();
            getProduto(value);
        });

        $('.search_item').on('keyup',function(){
            var value = $(this).val();
            searchItem(value);
        });

        $('#cod_barra').blur(function(){
            var value = $(this).val();
            $('#ceantrib').val(value);
        });

        $('#preco_venda').blur(function(){
            var value = $(this).val();
            $('#vuntrib').val(value);
        });

        $('#estoque').blur(function(){
            var value = $(this).val();
            $('#qtrib').val(value);
        });

        $('#preco_compra').blur(function(){
            var preco_compra_lucro = $(this).val();
            var preco_venda_lucro  = $('#preco_venda').val();

            if (preco_compra_lucro != "" && preco_venda_lucro != "")
                lucroValores(preco_compra_lucro, preco_venda_lucro)
        });  

        $('#preco_venda').blur(function(){
            var preco_venda_lucro = $(this).val();
            var preco_compra_lucro  = $('#preco_compra').val();
            
            if (preco_compra_lucro != "" && preco_venda_lucro != "")
                lucroValores(preco_compra_lucro, preco_venda_lucro)
        }); 
        
        $('.preco_compra_editar').blur(function(){
            var preco_compra_lucro = $(this).val();
            var preco_venda_lucro  = $('.preco_venda_editar').val();

            lucroValores(preco_compra_lucro, preco_venda_lucro)
        });  

        $('.preco_venda_editar').blur(function(){
            var preco_venda_lucro  = $(this).val();
            var preco_compra_lucro = $('.preco_compra_editar').val();

            lucroValores(preco_compra_lucro, preco_venda_lucro)
        });  
            
        $(document).delegate(".dtls_btn","click",function(){
            $('#detalhe_produto_modal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();
        
            var validade = "";
            var validade_split = data[10].split('-');

            if (validade_split == "Não informado")
                validade = validade_split
            else
                validade = validade_split[2]+'/'+validade_split[1]+'/'+validade_split[0];

            $('.img_detalhe').html(data[0]);
            $('.id_detalhe').html(data[1]);
            $('.categoria_detalhe').html(data[2]);
            $('.produto_detalhe').html(data[3]);
            $('.preco_venda_detalhe').html(data[4]);
            $('.estoque_detalhe').html(data[5]);
            $('.qtd_atacado_detalhe').html(data[6]);
            $('.preco_atacado_detalhe').html(data[7]);
            $('.descricao_detalhe').html(data[8]);
            $('.unidade_detalhe').html(data[9]);
            $('.validade_detalhe').html(validade);
            $('.cod_barra_detalhe').html(data[11]);
            $('.estoque_minimo_detalhe').html(data[12]);
            $('.preco_custo_detalhe').html(data[13]);
            $('.preco_minimo_detalhe').html(data[14]);
            $('.ncm_detalhe').html(data[15]);
            $('.cest_detalhe').html(data[16]);
            $('.extipi_detalhe').html(data[17]);
            $('.cfop_detalhe').html(data[18]);
            $('.origem_detalhe').html(data[19]);
            $('.situacao_tributaria_detalhe').html(data[20]);
            $('.lucro_real_detalhe').html(data[21]);
            $('.lucro_per_detalhe').html(data[22]);
        });

        $(document).delegate(".edt_btn","click",function(){
            $('#editar_produto_modal').modal('show');
            
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            var cod_barra;      
            var estoque_minimo; 
            var ncm;           
            var cest;           
            var extipi;  
            var qtd_atacado;

            var preco_compra      = data[13].slice(3);
            var preco_venda       = data[4].slice(3);
            var lucro_margem_real = data[21].slice(3);
            var preco_minimo      = data[14].slice(3);
            var preco_atacado     = data[7].slice(3);
            var lucro_margem_per  = data[22].substring(0, data[22].length-1);
                   
            if(data[11] == 'Não informado')
                cod_barra = '';
            else
                cod_barra = data[11];

            if(data[12] == 'Não informado')
                estoque_minimo = '';
            else
                estoque_minimo = data[12];

            if(data[15] == 'Não informado')
                ncm = '';
            else
                ncm = data[15];

            if(data[16] == 'Não informado')
                cest = '';
            else
                cest = data[16];

            if(data[17] == 'Não informado')
                extipi = '';
            else
                extipi = data[17];

            if(data[6] == 'Não informado')
                qtd_atacado = '';
            else
                qtd_atacado = data[6];

            $('.img_editar').html(data[0]);
            $('.id_editar').val(data[1]);
            $('.categoria_editar').val(data[2]);
            $('.produto_editar').val(data[3]);
            $('.preco_venda_editar').val(preco_venda);
            $('#vuntrib_editar').val(preco_venda);
            $('.preco_atacado_editar').val(preco_atacado);
            $('.qtd_atacado_editar').val(qtd_atacado);
            $('.estoque_editar').val(data[5]);
            $('#qtrib_editar').val(data[5]);
            $('.descricao_editar').val(data[8]);
            $('.unidade_medida_editar').val(data[9]);
            $('.validade_editar').val(data[10]);
            $('.cod_barra_editar').val(cod_barra);
            $('#ceantrib_editar').val(cod_barra);
            $('.estoque_minimo_editar').val(estoque_minimo);
            $('.preco_compra_editar').val(preco_compra);
            $('.preco_minimo_editar').val(preco_minimo);
            $('.ncm_editar').val(ncm);
            $('.cest_editar').val(cest);
            $('.extipi_editar').val(extipi);
            $('.cfop_editar').val(data[18]);
            $('.origem_editar').val(data[19]);
            $('.situacao_tributaria_editar').val(data[20]);
            $('.lucro_real_editar').val(lucro_margem_real);
            $('.lucro_per_editar').val(lucro_margem_per);
            selectCategoria(data[1]);
            
        });

        $(document).delegate(".del_btn","click",function(){
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            $id = data[1];

            swal("Tem certeza que deseja remover o produto?", {
                buttons: {
                    yes: {
                        text: "Sim",
                        value: "yes"
                    },
                    no: {
                        text: "Não",
                        value: "no"
                    }
                }
            }).then((value) => {
                if (value === "yes") {
                    $.ajax({
                        url:'/delete_produto/'+$id,
                        type: 'DELETE',
                        beforeSend: () =>{
                            $("#preloader_full").css({'display' : 'block'});
                        }, 
                        success:function(data)
                        {
                            $("#preloader_full").css({'display' : 'none'});
                            swal({
                                type: "warning",
                                text: data.message,
                                icon: "success",
                                showCancelButton: false,
                                confirmButtonColor: "#DD6B55",
                                closeOnConfirm: false
                            }).then(() => {
                                $('.errors').html("");
                                getProduto();
                                notifications();
                            });  
                        }
                    });
                }
                return false;
            });
        });

        $(document).on('submit', '#form_cadastro_produto', function(e){
            e.preventDefault();
            
            var addFormProduto = new FormData($('#form_cadastro_produto')[0]);
            
            $.ajax({
                type: 'POST',
                url: '/produtos',
                data: addFormProduto,
                processData: false,  
                contentType: false,  
                dataType: 'json',
                beforeSend: () =>{
                    $("#preloader_full").css({'display' : 'block'});
                }, 
                success: function(data)
                {
                    $(".errors").html("");
                    $("#preloader_full").css({'display' : 'none'});
                    if($.isEmptyObject(data.error)){
                        swal({
                            text: data.message,
                            icon: "success"
                        }).then(() =>{
                            $('#form_cadastro_produto').find('input[type="text"]').val("");
                            $('#form_cadastro_produto').find('input[type="date"]').val("");
                            $('#form_cadastro_produto').find('input[type="file"]').val("");
                            $('#form_cadastro_produto').find('#ucom').val("UNID");
                            $('#form_cadastro_produto').find('#utrib').val("UNID");
                            $('#form_cadastro_produto').find('#utrib').prop("readonly",true);
                            $('#form_cadastro_produto').find('#ucom').prop("readonly",true);
                            $('#form_cadastro_produto').find('#origem').val("0 - Nacional");
                            $('#form_cadastro_produto').find('#situacao_tributaria').val("102 - Tributada pelo Simples Nacional sem permissão de crédito");
                            $('#form_cadastro_produto').find('#cfop').val("5101");
                            $('.img_produto_input span').next().text("Selecionar imagem");

                            getCategoria();
                            getProduto();
                            selectCategoria();
                            notifications();
                        });
                    }else{
                        $.each(data.error, function( index, value) {
                            $("#preloader_full").css({'display' : 'none'});
                            $(".errors").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
                        });
                    }
                }
            });
        });

        $(document).on('submit', '#form_edit_produto', function(e){
            e.preventDefault();
            
            var id              = $('.id_editar').val();
            var editFormProduto = new FormData($('#form_edit_produto')[0]);
            
            $.ajax({
                type: 'POST',
                url: '/update_produto/'+id,
                data: editFormProduto,
                processData: false,  
                contentType: false,  
                dataType: 'json',
                beforeSend: () =>{
                    $("#preloader_full").css({'display' : 'block'});
                }, 
                success: function(data)
                {
                    $("#preloader_full").css({'display' : 'none'});
                    if($.isEmptyObject(data.error)){
                        swal({
                            text: data.message,
                            icon: "success"
                        }).then(() =>{
                            $('#editar_produto_modal').modal('hide');
                            $(".errors").html("");
                            $(".errors_editar_produto").html("");
                            $('.search_product').val("");
                            $('#lucro_real').val("");
                            $('#lucro_per').val("");

                            getCategoria();
                            getProduto();
                            selectCategoria();
                            notifications();
                        });
                    }else{
                        $.each(data.error, function(index, value) {
                            $("#preloader_full").css({'display' : 'none'});
                            $(".errors_editar_produto").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
                        });
                    }
                }
            });
        });

        $(document).delegate(".edt_cate","click",function(){
            $('#editar_categoria_modal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

           $('.id_editar_categoria').val(data[0]);
           $('.categoria_editar').val(data[1]);
           $('.descricao_categoria').val(data[2]);

        });

        $(document).delegate(".del_cate","click",function(){
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            $id = data[0];

            swal("Todos os produtos desta categoria serão removidos. Tem certeza que deseja remover?", {
                buttons: {
                    yes: {
                        text: "Sim",
                        value: "yes"
                    },
                    no: {
                        text: "Não",
                        value: "no"
                    },
                    
                },
                icon:"warning" 
            }).then((value) => {
                if (value === "yes") {
                    $.ajax({
                        url:'/categoria_delete/'+$id,
                        type: 'DELETE',
                        beforeSend: () =>{
                            $("#preloader_full").css({'display' : 'block'});
                        }, 
                        success:function(data)
                        {
                            $("#preloader_full").css({'display' : 'none'});
                            swal({
                                type: "warning",
                                text: data.message,
                                icon: "success",
                                showCancelButton: false,
                                confirmButtonColor: "#DD6B55",
                                closeOnConfirm: false
                            }).then(() => {
                                $('.errors').html("");
                                getCategoria();
                                getProduto();
                                selectCategoria();
                            });  
                        }
                    });
                }
                return false;
            });
        });

        $(document).on('submit', '#form_edit_categoria', function(e){
            e.preventDefault();
            
            var id                = $('.id_editar_categoria').val();
            var editFormCategoria = new FormData($('#form_edit_categoria')[0]);

            $.ajax({
                type: 'POST',
                url: '/update_categoria/'+id,
                data: editFormCategoria,
                processData: false,  
                contentType: false,  
                dataType: 'json',
                beforeSend: () =>{
                    $("#preloader_full").css({'display' : 'block'});
                }, 
                success: function(data)
                {
                    $("#preloader_full").css({'display' : 'none'});
                    if($.isEmptyObject(data.error)){
                        swal({
                        type: "warning",
                        text: data.message,
                        icon: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        closeOnConfirm: false
                        }).then(() => {
                            $('#editar_categoria_modal').modal('hide');
                            $(".errors").html("");

                            getCategoria();
                            getProduto();
                            selectCategoria();
                        });
                    }else{
                        $.each(data.error, function( index, value) {
                            $("#preloader_full").css({'display' : 'none'});
                            $(".errors_editar_categoria").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
                        });
                    }
                }
            });
        });

        $('.estoque_baixo_modal').on('click', function(){
            $('#estoque_baixo_modal').modal('show');

            $.ajax({
                url:"{{ route('estoque_baixo.notifications') }}",
                method: 'GET',
                dataType: 'json',
                success:function(data)
                {
                    $('.list_produtos_estoque_baixo').html(data.dados_produtos_estoque_baixo); 
                }
            });
        })
        $(document).delegate('.select_produto_estoque_baixo', 'click', function(e){

            $('#estoque_baixo_modal').modal('hide');

            var produto_selecionado_estoque_baixo = $(this).text();

            $('.search_product').val(produto_selecionado_estoque_baixo).focus().trigger("keypress");
        });
     
        $('.tabheading li').click(function () {
            
            var tab_id = $(this).attr("rel");

            $(this).parents('.tabcontainer').find('.active').removeClass('active');
            $('.tabbody').hide();
            $('#' + tab_id).show();
            $(this).addClass('active');

            return false;
        });

        $('.close').click(function(){
            $('.errors').html("");
            $(".errors_editar_categoria").html("");
            $(".errors_editar_produto").html("");
            selectCategoria();
        });

        $('#img_produto_input').change(function(e){
            $('.img_produto_input span').next().text(e.target.files[0].name);
        })

        getCategoria();
        getProduto();
        listaTotalItens();
        selectCategoria();
    });

    function getCategoria()
    {   
        $.ajax({
            url: '/categorias',
            type: 'GET',
            success:function(data)
            {
                var qnt_categoria = data.categoria_count > 0 ? data.categoria_count : 0;
                $('.tb_categorias tbody').html("");
                $('.total_categorias').html('<span>Total de categorias: '+qnt_categoria+'</span>')

                if (data.texto != null)
                {
                    $('.tb_categorias tbody').html('\
                    <tr>\
                        <td colspan="4" style="font-weight:100; font-size:19px;">'+data.texto+'</td>\
                    </tr>');
                }
                else
                {
                    $.each(data.categorias, function(index,value){
                        $('.tb_categorias tbody').append('<tr>\
                            <td data-label="Código">'+value.id+'</td>\
                            <td data-label="Categoria">'+value.categoria+'</td>\
                            <td data-label="Descrição">'+value.descricao+'</td>\
                            <td data-label="Editar"><a href="#" title="Editar categoria" class="edt_cate" style="font-size: 13px; color: #656565;"><i class="fas fa-edit"></i></a></td>\
                            <td data-label="Excluir"><a href="#" title="Deletar categoria" class="del_cate" style="font-size: 13px; color: red;"><i class="fas fa-times-circle"></i></a></td></tr>'
                        ); 
                    }); 
                }
            }
        });
    }

    function addCategoria()
    {  
        var categoria = $('#categoria').val();
        var descricao = $('#descricao_categoria').val();

        $(".errors").html("");  
        $("#preloader_full").css({'display' : 'block'});

        $.post('categorias', {categoria:categoria, descricao:descricao}, function(data){
            if($.isEmptyObject(data.error)){
                $("#preloader_full").css({'display' : 'none'});
                swal({
                    text: data.message,
                    icon: "success"
                }).then(() =>{
                    getCategoria();
                    selectCategoria();
                    $('#categoria').val("");
                    $('#descricao_categoria').val("");
                });
            }else{
                $.each(data.error, function( index, value) {
                    $("#preloader_full").css({'display' : 'none'})
                    $(".errors").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
                });
            } 
        });
    }

    function getProduto(query = '')
    {   
        
        $.ajax({
            url:"{{ route('produtos.search_product') }}",
            method: 'GET',
            dataType: 'json',
            data:{query: query},
            success:function(data)
            {   
                $('.lista_produto_table tbody').html(data.output);
                $('#total_produtos').text('Total de itens: '+data.total_product);
                 
            }
        });
    }

    function selectCategoria(id = '')
    {
        $.ajax({
            type: 'GET',
            url: '/select_categoria/'+id,
            success:function(data)
            {
                $(".select_categoria option").remove("");
                if (data.id > 0)
                    $(".select_categoria").append(data.first_option);
                else
                    $(".select_categoria").append('<option value="0" style="font-weight: 100; font-style:italic;" selected>Selecione...</option>');

                $.each(data.dados_categoria, function(index, value)
                {
                    $(".select_categoria").append('<option value="'+index+'">'+value+'</option>');
                    
                });
            }
        })
    }

    function searchCategory(query = '')
    {
        $.ajax({
            url:"{{ route('produtos.search_category') }}",
            method: 'GET',
            dataType: 'json',
            data:{query: query},
            success:function(data)
            {
                $('.tb_total_itens tbody').html(data.itens_agrupados);
                 
            }
        });
    }

    function searchItem(query = '')
    {
        $.ajax({
            url:"{{ route('produtos.search_item') }}",
            method: 'GET',
            dataType: 'json',
            data:{query: query},
            success:function(data)
            {
                $('.tb_total_itens tbody').html(data.itens_agrupados);
                 
            }
        });
    }

    function listaTotalItens()
    {
        $.ajax({
            url:"{{ route('produtos.total_item') }}",
            method: 'GET',
            dataType: 'json',
            success:function(data)
                {
                    var options = { 
                    style: 'currency', 
                    currency: 'BRL', 
                    minimumFractionDigits: 2, 
                    maximumFractionDigits: 3 
                };

                var formatNumber = new Intl.NumberFormat('pt-BR', options);

                $('.tb_total_itens tbody').html("");

                if (data.qtd_total_item == 0)
                {
                    $('.tb_total_itens tbody').html('\
                        <tr>\
                            <td colspan="6" style="font-weight:100; font-size:19px;"><i>Item não encontrado.</i></td>\
                        </tr>\
                    ');
                }

                $.each(data.itens_agrupados, function(index,value){

                    var img_prod  = value.img ? '<img src="storage/'+value.img+'" alt="img_item" style="width:42px; height:42px; border-radius:30px;"/>' : '<i class="fas fa-image fa-3x"></i>';
                    var estoque   = value.qtd_compra - value.total_itens;
                    var sub_total = formatNumber.format(value.sub_total);
                   
                    $('.tb_total_itens tbody').append('<tr>\
                        <td>'+img_prod+'</td>\
                        <td>'+value.nome+'</td>\
                        <td>'+value.qtd_compra+'</td>\
                        <td>'+value.total_itens+'</td>\
                        <td>'+estoque+'</td>\
                        <td>'+sub_total+'</td></tr>'
                    );
               });
            }
        });
    }

    function lucroValores(preco_compra_lucro, preco_venda_lucro)
    {
        var options = { 
                currency: 'BRL', 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 3 
            };

        var formatNumber = new Intl.NumberFormat('pt-BR', options);
       
        var resultLucroReal = parseFloat(preco_venda_lucro.replace("," , ".")) - parseFloat(preco_compra_lucro.replace("," , "."));  
        var resultLucroPer  = parseFloat((preco_venda_lucro.replace("," , ".")) - parseFloat(preco_compra_lucro.replace("," , "."))) / parseFloat(preco_venda_lucro.replace("," , "."));  

        resultLucroReal = formatNumber.format(resultLucroReal);
       
        $('#lucro_real').val(resultLucroReal);
        $('#lucro_per').val(resultLucroPer.toFixed(2));

        $('#lucro_real_editar').val(resultLucroReal);
        $('#lucro_per_editar').val(resultLucroPer.toFixed(2));
    }   
</script>
@endpush
