@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <div style="display:flex; justify-content:space-between" >
        <h5 class="m-0 text-dark"><i class="fas fa-play-circle"></i> Produtos</h5>
        <ol class="breadcrumb float-sm-right" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                            <small><i class="fas fa-sitemap fa-x3"></i> Categorias</small>
                        </a>
                    </li>
                    <li rel="tab2">
                        <a href="#">
                            <small><i class="fas fa-plus fa-x3"></i> Adicionar</small>
                        </a> 
                    </li>
                    <li rel="tab3">
                        <a href="#">
                            <small><i class="fas fa-list fa-x3"></i> Lista</small>
                        </a>
                    </li>
                    <li rel="tab4">
                        <a href="#">
                            <small><i class="fas fa-clipboard-list fa-x3"></i> Total itens</small>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tabcontainer">
                <div class="tabbody active" id="tab1" style="display: block;">  
                    <div style="display: flex; justify-content:space-between; align-items: flex-start; margin-bottom: 5px; margin-top: -10px;">
                        <div class="total_categorias" style="position: relative; top:-2px; font-weight: 100; font-size: 13px; height: 12px;"></div>
                        <!-- <input type="text" name="search_category" id="search_category" style="position: relative; outline:none; border:1px solid #848484;" placeholder="Pesquisar categoria..." autocomplete="off" /> -->
                    </div>
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
                                <span>Adicionar categoria</span>
                            </div>
                            <div> 
                                <label for="category">Categoria:</label>
                                <input type="text" name="category" id="category" autocomplete="off">
                                <label for="descricao_category">Descrição:</label>
                                <textarea rows="5" name="category" id="descricao_category"></textarea>
                            </div>
                            <button class="btn_add_categoria" onclick="addCategoria()">Adicionar</button>
                        </div>
                    </div>
                </div>
                <div class="tabbody" id="tab2" style="display: none;">
                    <form id="form_cadastro_produto" action="{{route('produtos.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row cadastro_produtos_inputs">
                            <div class="col-md-3">
                                <label for="select_categoria">Categorias*
                                    <select class="form-control select_categoria" id="select_categoria" name="category_id"></select>
                                </label>
                                <label for="nome">Produto*
                                    <input type="text" class="form-control" name="nome" id="nome" placeholder="" value="{{ old('nome')}}" autocomplete="off">
                                </label>
                                <label for="estoque">Qtd. Comercial*
                                    <input type="text" class="form-control" name="estoque" id="estoque" placeholder="" value="{{old('estoque')}}" autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                               <label for="cod_barra">EAN
                                    <input type="text" class="form-control" name="cod_barra" id="cod_barra" placeholder="" value="{{ old('cod_barra')}}" autocomplete="off">
                                </label>
                                <label for="ceantrib">EAN Unid. Tributável
                                    <input type="text" class="form-control" name="ceantrib" id="ceantrib" placeholder="" value="{{ old('ceantrib')}}" autocomplete="off">
                                </label>
                                <label for="qtrib">Qtd. Tributável*
                                    <input type="text" class="form-control" name="qtrib" id="qtrib" value="{{old('qtrib')}}" autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="ncm">NCM*
                                    <input type="text" class="form-control" name="ncm" id="ncm" value="{{ old('ncm')}}" autocomplete="off">
                                </label>
                                <label for="extipi">EXT IPI
                                    <input type="text" class="form-control" name="extipi" id="extipi" value="{{old('extipi')}}"  autocomplete="off">
                                </label>
                                <label for="estoque_minimo">Estoque Mínimo*
                                    <input type="text" class="form-control" name="estoque_minimo" id="estoque_minimo" placeholder="" value="{{old('estoque_minimo')}}" autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="preco_compra">Valor Custo(R$)*
                                    <input type="text" class="form-control" name="preco_compra" id="preco_compra" value="{{old('preco_compra')}}" style="text-align: right" autocomplete="off">
                                </label>
                                <label for="preco_venda">Valor Venda(R$)*
                                    <input type="text" class="form-control" name="preco_venda" id="preco_venda" value="{{old('preco_venda')}}" style="text-align: right" autocomplete="off">
                                </label>
                                <label for="preco_minimo">Valor Mínimo(R$)*
                                    <input type="text" class="form-control" name="preco_minimo" id="preco_minimo" value="{{old('preco_minimo')}}" style="text-align: right" autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="ucom">Unid. Comercial*
                                    <input type="text" class="form-control" name="ucom" id="ucom" value="UNID" autocomplete="off" disabled>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="utrib">Unid. Tributável*
                                    <input type="text" class="form-control" name="utrib" id="utrib" value="UNID" autocomplete="off" disabled>
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="descricao">Descricão
                                    <input type="text" class="form-control" name="descricao" id="descricao" value="{{old('descricao')}}" autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="vuntrib">Valor Unid. Tributável(R$)*
                                    <input type="text" class="form-control" name="vuntrib" id="vuntrib" value="{{old('vuntrib')}}" style="text-align: right" autocomplete="off">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="validade">Validade
                                    <input type="date" class=" form-control datetimepicker-input" data-target="#reservationdate" name="validade" id="validade" value="{{old('validade')}}">
                                </label>
                            </div>
                            <div class="col-md-3">
                                <input type="file" name="img" id="img_produto_input" accept="image/*">
                                <label>Imagem:</label>
                                <label for="img_produto_input" class="img_produto_input">
                                    <span>Procurar</span>
                                    <span>Selecionar imagem</span>
                                </label>
                                <small>Tamanho máximo: 2MB</small>
                            </div> 
                        </div>
                        <hr>
                        <div style="display:flex; gap:4px; margin-top:15px;">
                            <button type="submit" style="border: none; background: #3f6792; color: #FFF;">Adicionar</button><br>
                            <input type="reset" value="Cancel" class="btn btn-danger" style="">
                        </div>
                    </form>
                </div>
                <div class="tabbody" id="tab3" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex; justify-content: space-between; flex-wrap:wrap; align-items:center;">
                                <h3 class="card-title">Meus produtos</h3><br>
                                <input class="search_product" id="search_product" name="search_product" type="text" placeholder="Pesquisar..." style="outline: none" autocomplete="off">
                            </div>
                        </div>
                        <div class="card-body">
                        <span id="total_produtos" style="font-size:13px; position:absolute; margin: -18px 0; font-weight:100"></span>
                            <table class="table table-striped table-bordered lista_produto">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ID</th>
                                        <th>Produto</th>
                                        <th>Preço Custo</th>
                                        <th>Preço Venda</th>
                                        <th>Estoque</th>
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

        $(document).delegate(".dtls_btn","click",function(){
            $('#detalhe_produto_modal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            var validade_split = data[8].split('-');
            var validade_formatada = validade_split[2]+'/'+validade_split[1]+'/'+validade_split[0];

            $('.img_detalhe').html(data[0]);
            $('.id_detalhe').html(data[1]);
            $('.produto_detalhe').html(data[2]);
            $('.preco_custo_detalhe').html(data[3]);
            $('.preco_venda_detalhe').html(data[4]);
            $('.estoque_detalhe').html(data[5]);
            $('.descricao_detalhe').html(data[6]);
            $('.unidade_detalhe').html(data[7]);
            $('.validade_detalhe').html(validade_formatada);
            $('.cod_barra_detalhe').html(data[9]);
        });

        $(document).delegate(".edt_btn","click",function(){
            $('#editar_produto_modal').modal('show');
            
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            var preco_compra = data[3].slice(3);
            var preco_venda = data[4].slice(3);

            $('.img_editar').html(data[0]);
            $('.id_editar').val(data[1]);
            $('.produto_editar').val(data[2]);
            $('.preco_compra_editar').val(preco_compra);
            $('.preco_venda_editar').val(preco_venda);
            $('.estoque_editar').val(data[5]);
            $('.descricao_editar').val(data[6]);
            $('.unidade_editar').val(data[7]);
            $('.validade_editar').val(data[8]);
            $('.cod_barra_editar').val(data[9]);

            selectCategoria(data[1]);
            
        });

        $(document).delegate(".del_btn","click",function(){
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            $id = data[1];

            swal("Tem certeza que deseja removê-lo?", {
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
                        success:function(data)
                        {
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
                success: function(data)
                {
                    $(".errors").html("");
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

                            getCategoria();
                            getProduto();
                            selectCategoria();
                        });
                    }else{
                        $.each(data.error, function( index, value) {
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
                success: function(data)
                {
                    if($.isEmptyObject(data.error)){
                        swal({
                            text: data.message,
                            icon: "success"
                        }).then(() =>{
                            $('#editar_produto_modal').modal('hide');
                            $(".errors").html("");
                            getCategoria();
                            getProduto();
                            selectCategoria()
                        });
                    }else{
                        $.each(data.error, function( index, value) {
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
                        success:function(data)
                        {
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
                success: function(data)
                {
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
                            $(".errors_editar_categoria").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
                        });
                    }
                }
            });
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
        getCategoria()
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
                            <td>'+value.id+'</td>\
                            <td>'+value.categoria+'</td>\
                            <td>'+value.descricao+'</td>\
                            <td><a href="#" title="Editar categoria" class="edt_cate" style="font-size: 13px; color: #656565;"><i class="fas fa-edit"></i></a></td>\
                            <td><a href="#" title="Remover categoria" class="del_cate" style="font-size: 13px; color: red;"><i class="fas fa-minus-circle"></i></a></td></tr>'
                        ); 
                    }); 
                }
            }
        });
    }

    function addCategoria()
    {  
        var categoria = $('#category').val();
        var descricao = $('#descricao_category').val();

        $(".errors").html("");  

        $.post('categorias', {categoria:categoria, descricao:descricao}, function(data){
            $('#category').val("");
            $('#descricao_category').val("");

            if($.isEmptyObject(data.error)){
                swal({
                    text: data.message,
                    icon: "success"
                }).then(() =>{
                    getCategoria();
                    selectCategoria();
                });
            }else{
                $.each(data.error, function( index, value) {
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
                $('.lista_produto tbody').html(data.output);
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
</script>
@endpush
