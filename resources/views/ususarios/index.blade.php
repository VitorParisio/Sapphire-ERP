@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <div style="display:flex; justify-content:space-between" >
        <h5 class="m-0 text-dark"><i class="fas fa-play-circle"></i> Usuários</h5>
        <ol class="breadcrumb float-sm-right" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active">Usuários</li>
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
                            <small><i class="fas fa-list fa-x3"></i> Lista de usuários</small>
                        </a>
                    </li>
                    <li rel="tab2">
                        <a href="#">
                            <small><i class="fas fa-plus fa-x3"></i> Novo usuário</small>
                        </a> 
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tabcontainer">
                <div class="tabbody active" id="tab1" style="display: block;">
                    <span id="total_usuarios" style="font-size:13px; position:absolute; margin: -18px 0; font-weight:900"></span>
                    <input class="search_usuario" id="search_user" name="search_client" type="text" placeholder="Pesquisar usuário" style="outline: none" autocomplete="off">
                    <hr>
                    <table class="table table-striped lista_usuario_table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Usuário</th>
                                <th>CPF/CNPJ</th>
                                <th>RG/Insc. Estadual</th>
                                <th>Cidade</th>
                                <th colspan=3>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                        
                </div> 
                <div class="tabbody" id="tab2" style="display: none;">  
                    <div class="adicionar_usuario">
                        <form id="form_cadastro_usuario" style="margin-top:-20px;" action="{{route('store.usuarios')}}" method="post" enctype="multipart/form-data">
                        @csrf
                            <div class="row  align-items-start cadastro_clientes_inputs">
                                <div class="col-md-12">
                                    <h4 style="background:teal; color:#FFF; padding:5px; font-size:16px; margin-bottom:15px;"><i class="fas fa-id-card"></i> IDENTIFICAÇÃO:</h4>
                                </div>
                                <div class="col-md-3">
                                    <label for="nome">Cliente*
                                        <input type="text" class="form-control" name="nome" id="nome" placeholder="" value="{{ old('nome')}}" autocomplete="off">
                                    </label>
                                    <label for="cpf_cnpj">CPF/CNPJ*
                                        <input type="text" class="form-control" name="cpf_cnpj" id="cpf_cnpj" value="{{ old('cnpj_cpf')}}" autocomplete="off">
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label for="rg_ie">RG/Insc. Estadual*
                                        <input type="text" class="form-control" name="rg_ie" id="fantasia" value="{{old('rg_ie')}}"  autocomplete="off">
                                    </label>
                                    <label for="fone_dest">Telefone
                                        <input type="text" class="form-control" name="fone" id="fone_dest" value="{{old('fone')}}" style="text-align: right" autocomplete="off" />
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label for="email_dest">Email
                                        <input type="text" class="form-control" name="email" id="email_dest" value="{{old('email')}}" style="text-align: right" autocomplete="off" />
                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <hr>
                                    <h4 style="background:teal; color:#FFF; padding:5px; font-size:16px; margin-bottom:15px; margin-top:15px;"><i class="fas fa-map-marker-alt"></i> ENDEREÇO:</h4>
                                </div>
                                <div class="col-md-3">
                                    <label for="cep">CEP*
                                        <input type="text" class="form-control" name="cep" id="cep" placeholder="" value="{{old('cep')}}" autocomplete="off" onblur="pesquisacep(this.value);"  maxlength="9">
                                    </label>
                                    <label for="rua">Logradouro*
                                        <input type="text" class="form-control" name="rua" id="rua" placeholder="" value="{{old('rua')}}" autocomplete="off" readonly>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label for="numero">Número*
                                        <input type="text" class="form-control" name="numero" id="numero" placeholder="" value="{{old('numero')}}" autocomplete="off">
                                    </label>
                                    <label for="complemento">Complemento
                                        <input type="text" class="form-control" name="complemento" id="complemento" placeholder="" value="{{old('complemento')}}" autocomplete="off">
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label for="bairro">Bairro*
                                        <input type="text" class="form-control" name="bairro" id="bairro" placeholder="" value="{{old('bairro')}}" autocomplete="off" readonly>
                                    </label>
                                    <label for="cidade">Cidade*
                                        <input type="text" class="form-control" name="cidade" id="cidade" placeholder="" value="{{old('cidade')}}" autocomplete="off" readonly>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label for="uf">UF*
                                        <input type="text" class="form-control" name="uf" id="uf" placeholder="" value="PE" autocomplete="off" readonly>
                                        <input type="hidden" name="cibge" type="text" id="ibge" value="2610707" readonly/></label><br />
                                    </label>
                                </div>
                            </div>
                            <div style="display:flex; gap:4px; margin-top:10px;">
                                <button type="submit" style="border: none; background: #3f6792; color: #FFF;">Adicionar</button><br>
                                <input type="reset" value="Cancel" class="btn btn-danger">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div>
            @include('modals.destinatario.detalhe')
        </div>
        <div>
            @include('modals.destinatario.editar')
        </div>
    </div>
@stop
@push('scripts')
<script src="{{ asset('js/cep.js') }}"></script>
<script>
    $(function(){
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.search_cliente').on('keyup',function(){
            var value = $(this).val();
            getCliente(value);
        });

        $(document).delegate(".dtls_btn","click",function(){
            $('#detalhe_cliente_modal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            $('.id_cliente_detalhe').html(data[0]);
            $('.cliente_detalhe').html(data[1]);
            $('.cpf_cnpj_detalhe').html(data[2]);
            $('.rg_ie_detalhe').html(data[3]);
            $('.cidade').html(data[4]);
            $('.email_detalhe').html(data[5]);
            $('.fone_detalhe').html(data[6]);
            $('.cep').html(data[7]);
            $('.logradouro').html(data[8]);
            $('.numero').html(data[9]);
            $('.complemento').html(data[10]);
            $('.bairro').html(data[11]);
            $('.uf').html(data[12]);
        });

        $(document).delegate(".edt_btn","click",function(){
            $('#editar_cliente_modal').modal('show');
        
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            $('.id_editar').val(data[0]);
            $('.cliente_editar').val(data[1]);
            $('.cpf_cnpj_editar').val(data[2]);
            $('.rg_ie_editar').val(data[3]);
            $('.email_editar').val(data[5]);
            $('.fone_editar').val(data[6]);
            $('.cep_editar').val(data[7]);
            $('.logradouro_editar').val(data[8]);
            $('.cidade_editar').val(data[4]);
            $('.numero_editar').val(data[9]);
            $('.complemento_editar').val(data[10]);
            $('.bairro_editar').val(data[11]);
            $('.uf_editar').val(data[12]);
        
        });

        $(document).delegate(".del_btn","click",function(){
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            $id = data[0];

            swal("Tem certeza que deseja este cliente?", {
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
                        url:'/delete_cliente/'+$id,
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
                                getCliente();
                            });  
                        }
                    });
                }
                return false;
            });
        });

        $(document).on('submit', '#form_cadastro_cliente', function(e){
            e.preventDefault();
           
            var addFormCliente = new FormData($('#form_cadastro_cliente')[0]);
            
            $.ajax({
                type: 'POST',
                url: '/clientes',
                data: addFormCliente,
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
                            $('#form_cadastro_cliente').find('input[type="text"]').val("");
                            $('#form_cadastro_cliente').find('input[id="uf"]').val("PE");
                            getCliente();
                        });
                    }else{
                        $.each(data.error, function( index, value) {
                            $(".errors").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
                        });
                    }
                }
            });
        });

        $(document).on('submit', '#form_edit_cliente', function(e){
            e.preventDefault();
            
            var id              = $('.id_editar').val();
            var editFormCliente = new FormData($('#form_edit_cliente')[0]);
            
            $.ajax({
                type: 'POST',
                url: '/update_cliente/'+id,
                data: editFormCliente,
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
                            $('#editar_cliente_modal').modal('hide');
                            $(".errors").html("");
                            getCliente();
                        });
                    }else{
                        $.each(data.error, function( index, value) {
                            $(".errors_editar_cliente").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
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
            $(".errors_editar_cliente").html("");
        });
         getCliente();
    });

    function getCliente(query = '')
    {   
        
        $.ajax({
            url:"{{ route('clientes.search_client') }}",
            method: 'GET',
            dataType: 'json',
            data:{query: query},
            success:function(data)
            {   
                $('.lista_cliente_table tbody').html(data.output);
                $('#total_clientes').text('Total de clientes: '+data.total_client);
                 
            }
        });
    }
</script>
@endpush
