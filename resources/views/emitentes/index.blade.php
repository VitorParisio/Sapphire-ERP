@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <div style="display:flex; justify-content:space-between" >
        <h5 class="m-0 text-dark"><i class="fas fa-play-circle"></i> Empresas</h5>
        <ol class="breadcrumb float-sm-right" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Empresas</li>
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
                            <small><i class="fas fa-list fa-x3"></i> Lista de empresas</small>
                        </a>
                    </li>
                    <li rel="tab2">
                        <a href="#">
                            <small><i class="fas fa-plus fa-x3"></i> Nova empresa</small>
                        </a> 
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tabcontainer">
                <div class="tabbody active" id="tab1" style="display: block;">
                    <span id="total_empresas" style="font-size:13px; position:absolute; margin: -18px 0; font-weight:900"></span>
                    <input class="search_empresa" id="search_empresa" name="search_empresa" type="text" placeholder="Pesquisar empresa" style="outline: none" autocomplete="off">
                    <hr>
                    <table class="table table-striped lista_empresa_table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>CNPJ</th>
                                <th>Razão Social</th>
                                <th>Insc. Estadual</th>
                                <th>Localidade</th>
                                <th colspan=3>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>  
                <div class="tabbody active" id="tab2" style="display: none;">  
                    <div style="display: flex; justify-content:space-between; align-items: flex-start; margin-bottom: 5px; margin-top: -10px;">
                    </div>
                    <div class="adicionar_empresa">
                        <form id="form_cadastro_empresa" style="margin-top:-20px;" action="{{route('store.empresa')}}" method="post" enctype="multipart/form-data">
                        @csrf
                            <div class="row cadastro_empresas_inputs">
                                <div class="col-md-12">
                                    <h4 style="background:teal; color:#FFF; padding:5px; font-size:16px;"><i class="fas fa-id-card"></i> IDENTIFICAÇÃO:</h4>
                                </div>
                                <div class="col-md-3">
                                    <label for="cnpj">CNPJ/CPF*
                                        <input type="text" class="form-control" name="cnpj" id="cnpj" placeholder="" value="{{ old('cnpj')}}" autocomplete="off">
                                    </label>
                                   
                                    <label for="inscricao_estadual">Inscrição Estadual*
                                        <input type="text" class="form-control" id="inscricao_estadual" name="ie" value="{{ old('IE')}}" autocomplete="off"/>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label for="razao_social">Razão Social*
                                        <input type="text" class="form-control" name="razao_social" id="razao_social" value="{{ old('razao_social')}}" autocomplete="off">
                                    </label>
                                    <label for="fantasia">Fantasia*
                                        <input type="text" class="form-control" name="nome_fantasia" id="fantasia" value="{{old('nome_fantasia')}}"  autocomplete="off">
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label for="inscricao_municipal">Inscrição Municipal
                                        <input type="text" class="form-control" name="im" id="inscricao_municipal" value="{{old('im')}}" style="text-align: right" autocomplete="off" />
                                    </label>
                                    <label for="cnae">CNAE
                                        <input type="text" class="form-control" name="cnae" id="cnae" value="{{old('cnae')}}" style="text-align: right" autocomplete="off" />
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <input type="file" name="certificado_a1" id="file_empresa_input" />
                                    <label>Certificado Digital*</label>
                                    <label for="file_empresa_input" class="file_empresa_input">
                                        <span>Procurar</span>
                                        <span>Selecionar certificado</span>
                                    </label>
                                    <label for="senha_certificado">Senha (certificado)*
                                        <input type="password" class="form-control" name="senha_certificado" id="senha_certificado" value="" style="text-align: right">
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
                                        <input type="hidden" name="cibge" type="text" id="ibge" value="2610707" /></label><br />
                                    </label>
                                </div>
                            </div>
                            <hr>
                            <div style="display:flex; gap:4px; margin-top:10px;">
                                <button type="submit" style="border: none; background: #3f6792; color: #FFF;">Adicionar</button><br>
                                <input type="reset" value="Cancel" class="btn btn-danger">
                            </div>
                        </form>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
    <div>
        @include('modals.emitente.detalhe')
    </div>
    <div>
        @include('modals.emitente.editar')
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

        $('.search_empresa').on('keyup',function(){
            var value = $(this).val();
            searchCategory(value);
        });

        $(document).delegate(".dtls_btn","click",function(){
            $('#detalhe_empresa_modal').modal('show');

            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            $('.id_empresa_detalhe').html(data[0]);
            $('.cnpj_detalhe').html(data[1]);
            $('.razao_detalhe').html(data[2]);
            $('.ie_detalhe').html(data[3]);
            $('.cidade').html(data[4]);
            $('.fantasia_detalhe').html(data[5]);
            $('.im_detalhe').html(data[6]);
            $('.cnae_detalhe').html(data[7]);
            $('.cep').html(data[8]);
            $('.logradouro').html(data[9]);
            $('.numero').html(data[10]);
            $('.complemento').html(data[11]);
            $('.bairro').html(data[12]);
            $('.uf').html(data[13]);
        });

        $(document).delegate(".edt_btn","click",function(){
            $('#editar_empresa_modal').modal('show');
            
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            $('.id_editar').val(data[0]);
            $('.cnpj_editar').val(data[1]);
            $('.razao_editar').val(data[2]);
            $('.ie_editar').val(data[3]);
            $('.cidade_editar').val(data[4]);
            $('.fantasia_editar').val(data[5]);
            $('.im_editar').val(data[6]);
            $('.cnae_editar').val(data[7]);
            $('.cep_editar').val(data[8]);
            $('.logradouro_editar').val(data[9]);
            $('.numero_editar').val(data[10]);
            $('.complemento_editar').val(data[11]);
            $('.bairro_editar').val(data[12]);
            $('.uf_editar').val(data[13]);
            
        });

        $(document).delegate(".del_btn","click",function(){
            $tr = $(this).closest('tr');

            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            $id = data[0];

            swal("Tem certeza que deseja remover a empresa?", {
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
                        url:'/delete_empresa/'+$id,
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
                                getEmpresa();
                            });  
                        }
                    });
                }
                return false;
            });
        });

        $(document).on('submit', '#form_cadastro_empresa', function(e){
            e.preventDefault();
        
            var addFormEmpresa = new FormData($('#form_cadastro_empresa')[0]);
            
            $.ajax({
                type: 'POST',
                url: '/empresas',
                data: addFormEmpresa,
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
                            $('#form_cadastro_empresa').find('input[type="text"]').val("");
                            $('#form_cadastro_empresa').find('input[type="file"]').val("");
                            $('.file_empresa_input span').next().text("Selecionar certificado");
                            $('#form_cadastro_empresa').find('input[type="password"]').val("");
                            $('#form_cadastro_empresa').find('input[id="uf"]').val("PE");
                            getEmpresa();
                        });
                    }else{
                        $.each(data.error, function( index, value) {
                            $(".errors").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
                        });
                    }
                }
            });
        });

        $(document).on('submit', '#form_edit_empresa', function(e){
            e.preventDefault();
            
            var id              = $('.id_editar').val();
            var editFormEmpresa = new FormData($('#form_edit_empresa')[0]);
           
            $.ajax({
                type: 'POST',
                url: '/update_empresa/'+id,
                data: editFormEmpresa,
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
                            $('#editar_empresa_modal').modal('hide');
                            $(".errors").html("");
                            $(".errors_editar_empresa").html("");
                            $('#form_edit_empresa').find('input[type="file"]').val("");
                            $('.file_empresa_input_editar span').next().text("Selecionar certificado");
                            $('#form_edit_empresa').find('input[type="password"]').val("");
                            getEmpresa();
                        });
                    }else{
                        $.each(data.error, function( index, value) {
                            $(".errors_editar_empresa").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
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
            $(".errors_editar_empresa").html("");
            $('#form_edit_empresa').find('input[type="file"]').val("");
            $('.file_empresa_input_editar span').next().text("Selecionar certificado");
        });

        $('#file_empresa_input').change(function(e){
            $('.file_empresa_input span').next().text(e.target.files[0].name);
        })

        $('#file_empresa_input_editar').change(function(e){
            $('.file_empresa_input_editar span').next().text(e.target.files[0].name);
        })

        getEmpresa();
    });
    
    function getEmpresa(query = '')
    {   
        
        $.ajax({
            url:"{{ route('empresa.search_empresa') }}",
            method: 'GET',
            dataType: 'json',
            data:{query: query},
            success:function(data)
            {   
                $('.lista_empresa_table tbody').html(data.output);
                $('#total_empresas').text('Total de empresas: '+data.total_empresas);
                 
            }
        });
    }

    function searchEmpresa(query = '')
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

</script>
@endpush
