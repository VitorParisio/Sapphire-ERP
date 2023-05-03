@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <div style="display:flex; justify-content:space-between" >
        <h5 class="m-0 text-dark"><i class="fas fa-play-circle"></i> Cadastrar Nota</h5>
        <ol class="breadcrumb float-sm-right" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Cadastrar Nota</li>
        </ol>
    </div>
    <div class="errors"></div>
@stop
@section('content')
    <div class="card card-primary">
        <div class="card-body">
            <div class="col-md-12">
                <label style="width:100%" for="select_emitente">Empresa*
                    <select class="form-control select_emitente" id="select_emitente" name="emitente_id">
                        <option value="0">Selecione...</option>
                        @foreach($emitentes as $emitente)
                            <option value="{{$emitente->id}}">{{$emitente->razao_social}}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div class="col-md-12 mt-3">
                <label style="width:100%" for="select_destinatario">Cliente*
                    <select class="form-control select_destinatario" id="select_destinatario" name="destinatario_id" >
                        <option value="0">Selecione...</option>
                        @foreach($destinatarios as $destinatarios)
                            <option  value="{{$destinatarios->id}}">{{$destinatarios->nome}}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            <div class="col-md-12 mt-3">
                <label class="mr-3" style="width:60%" for="select_produto">Produto*
                    <select class="form-control select_produto" id="select_produto" name="item_venda_id">
                        <option value="0">Selecione...</option>
                        @foreach($produtos as $produto)
                            <option  value="{{$produto->id}}">{{$produto->nome}}</option>
                        @endforeach
                    </select>
                </label>
                <label class="mr-3" for="qtd_produto">Quantidade*
                    <input type="text" name="qtd" class="form-control" id="qtd_produto">
                </label>
                <button class="add_produto_nfe"><i class="fas fa-plus"></i> Adicionar</button>
            </div>
        <div>
        <hr>
        <div class="card card-primary">
            <div class="card-body">
                <div class="list_produto_add">
                    <ul>
                        <li>#</li>
                        <li>Produto</li>
                        <li>Quantidade</li>
                        <li>Valor(R$)</li>
                    </ul>
                </div>
                <div class="prod_add">
                    <ul>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div style="display:flex; margin-top:30px" class="mt-3">
        <button class="btn btn-primary save_sell">Salvar</button>
        <button type="reset" class="btn btn-danger">Cancel</button>
    </div>
@stop
@push('scripts')
<script>
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.add_produto_nfe').click(function(){
            var data = {"produto_id": $('#select_produto').val(), "qtd" : $('#qtd_produto').val()};
            $.ajax({
                type: 'POST',
                url: '/add_produto_nfe',
                data: data,
                success:function(data){
                    $('.prod_add ul').html(data)
                }
          
            });
            // selectEmitente();
            // selectDestinatario();
            // selectProduto(); 
        });

        $('.save_sell').click(function(){
            var data = {"produto_id"          : $('#select_produto').val(), 
                        "select_destinatario" : $('#select_destinatario').val(),
                        "select_emitente"     : $('#select_emitente').val()};
            $.ajax({
                type: 'POST',
                url: '/cadastra_nfe',
                data: data,
                success:function(data){
                    swal({
                        text: data.message,
                        icon: "success"
                    }).then(() =>{
                        $('#form_cadastro_empresa').find('input[type="text"]').val("");
                        // getCategoria();
                        // getProduto();
                        // selectCategoria();
                    });
                }
            });
        });
    });
   
</script>
@endpush
