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
            <div class="row">
                <div class="col-md-6">
                    <input type="hidden" id="id_nota_fiscal" value="{{$id_nota_fiscal}}">
                    <label style="width:100%" for="select_emitente">Empresa*
                        <select class="form-control select_emitente" id="select_emitente" name="emitente_id">
                            <option value="0">Selecione...</option>
                            @foreach($emitentes as $emitente)
                                <option value="{{$emitente->id}}">{{$emitente->razao_social}}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
                <div class="col-md-6">
                    <label style="width:100%" for="select_destinatario">Cliente*
                        <select class="form-control select_destinatario" id="select_destinatario" name="destinatario_id" >
                            <option value="0">Selecione...</option>
                            @foreach($destinatarios as $destinatarios)
                                <option  value="{{$destinatarios->id}}">{{$destinatarios->nome}}</option>
                            @endforeach
                        </select>
                    </label>
                </div>
            </div>
            <hr>
            <div class="mt-3">
                <label class="mr-3" style="width:60%" for="select_produto">Produto*
                    <select class="form-control select_produto" id="select_produto" name="item_venda_nfe_id">
                        <option value="0">Selecione...</option>
                        @foreach($produtos as $produto)
                            <option  value="{{$produto->id}}">{{$produto->nome}}</option>
                        @endforeach
                    </select>
                </label>
                <label class="mr-3" for="qtd_produto_nfe">Quantidade*
                    <input type="text" name="qtd" class="form-control" id="qtd_produto_nfe" placeholder="1">
                </label>
                <button class="add_produto_nfe btn btn-primary"><i class="fas fa-plus"></i> Adicionar</button>
            </div>
        <div>
    </div>
    <hr>
    <div class="card card-primary">
        <div>
            <table class="table list_produto_add">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produto</th>
                        <th>Valor Unitário(R$)</th>
                        <th>Quantidade</th>
                        <th>Sub-Total</th>
                        <th><i class="fas fa-trash"></i></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="card-footer" style="display:flex; align-items:center; justify-content: space-between">
            <div style="display:flex; flex:2;">
                <select id="forma_pagamento" class="form-control">
                    <option value="01">Dinheiro</option>
                    <option value="02">Cheque</option>
                    <option value="03">Cartão de Crédito</option>
                    <option value="04">Cartão de Débito</option>
                </select>
                <input type="text" class="form-control text-right" id="valor_recebido" placeholder="A RECEBER" autocomplete="off" style="margin-left:3px;">
                <input type="text" id="troco" class="form-control text-right" autocomplete="off" placeholder="TROCO" style="margin-left:3px;">
            </div>
            <div>
                <input type="text" id="total_venda_nfe" class="form-control text-right" autocomplete="off" placeholder="TOTAL" style="margin-left:2.5px;">
            </div>
        </div>
    </div>
    <hr>
    <div style="display:flex; margin-top:30px" class="mt-3">
        <button class="btn btn-success save_sell">Cadastrar</button>
    </div>
    <div>
        @include('modals.pagamento')
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

        var id_nota_fiscal = $('#id_nota_fiscal').val();
        
        $('#troco').prop( "disabled", true );
        $('#total_pagamento').prop( "disabled", true );
        $('#total_venda_nfe').prop( "disabled", true );
        $('#valor_recebido').mask("000.000.000.000.000,00", {reverse: true});

        $('#valor_recebido').blur(function(){
            totalPagamentoNfe()
        });

        $('.add_produto_nfe').click(function(e){
          
            var dados = {"produto_id": $('#select_produto').val(), 
                         "qtd"       : $('#qtd_produto_nfe').val(), 
                         "nfe_id"    : $('#id_nota_fiscal').val()
                        };
            
            $.ajax({
                url: '/estoque_negativo_nfe',
                method: 'GET',
                data: dados,
                success:function(data)
                {
                    if (data == 'reload')
                    {
                        location.href = '/cadastrar_nota'
                    }
                    else
                    {
                        $(".errors").html("");
                        if($.isEmptyObject(data.error))
                        {  
                            if (data)
                            {
                                swal("Excedeu o limite do estoque. Desejas continuar a venda?", {
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
                                    if (value === "yes") 
                                    {
                                        $.post('add_produto_nfe', {produto_id: dados.produto_id, qtd: dados.qtd, nfe_id: dados.nfe_id})
                                        .done(function(data){
                                            $('#qtd_produto_nfe').val("");
                                            getProdutoNfe(dados.produto_id);
                                        });
                                    }
                                    return false;
                                });
                            }
                            else
                            {
                                $.post('add_produto_nfe', {produto_id: dados.produto_id, qtd: dados.qtd, nfe_id: dados.nfe_id})
                                .done(function(data){
                                    $('#qtd_produto_nfe').val("");
                                    getProdutoNfe(dados.produto_id);
                                });
                            }
                        } else {
                            $.each(data.error, function( index, value) {
                                $(".errors").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
                            });
                        }
                    }
                    
                }
            });
        });

        $('.save_sell').click(function(){
            var data = {
                        "nfe_id"              : $('#id_nota_fiscal').val(), 
                        "select_destinatario" : $('#select_destinatario').val(),
                        "select_emitente"     : $('#select_emitente').val(),
                        "forma_pagamento"     : $('#forma_pagamento').val(),
                        "valor_recebido"      : $('#valor_recebido').val(),
                        "troco"               : $('#troco').val(),
                        "total_venda"         : $('#total_venda_nfe').val()
                       };
            $.ajax({
                type: 'POST',
                url: '/cadastra_nfe',
                data: data,
                success:function(data){
                    if (data == 'reload')
                    {
                        location.href = '/cadastrar_nota'
                    }
                    else
                    {
                        $(".errors").html("");
                        if($.isEmptyObject(data.error))
                        {  
                            if (data.valores_incorretos)
                            {
                                swal({
                                    text: data.valores_incorretos,
                                    icon: "warning"
                                })
                            }
                            else
                            {
                                swal({
                                    text: data.message,
                                    icon: "success"
                                }).then(() =>{
                                    removeProdutos();
                                    location.href = '/notas_fiscais';
                                });
                            }
                        } else {
                            $.each(data.error, function( index, value) {
                                $(".errors").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
                            });
                        }
                    }
                }
            });
        }); 

        getProdutoNfe();
    });

    function getProdutoNfe(produto_id)
    { 
        $.ajax({
            url: "getprodutonfe/"+ produto_id +"",
            type:'GET',
            success: function(data) {
                var options = { 
                    style: 'currency', 
                    currency: 'BRL', 
                    minimumFractionDigits: 2, 
                    maximumFractionDigits: 3 
                };

                var formatNumber = new Intl.NumberFormat('pt-BR', options);
                var itens        = data.itens;
                var total_venda  = formatNumber.format(data.total_venda);

                $('tbody').html("");

                $.each(itens,function(index,data){
                    var preco_venda = formatNumber.format(data.preco_venda);
                    var sub_total   = formatNumber.format(data.sub_total);
                    var img_tag     = data.img != null ? '<img src="storage/'+data.img+'" alt="img-item" style="width:30px"/>' : '<i class="fas fa-file-image fa-3x"></i>'
                    
                    $('tbody').append('<tr>\
                        <td>'+img_tag+'</td>\
                        <td>'+data.nome+'</td>\
                        <td>'+preco_venda+'</td>\
                        <td>'+data.qtd+'</td>\
                        <td>'+sub_total+'</td>\
                        <td><a href="#" onclick="deletaProdutoCodNfe('+ data.item_venda_nfe_id +','+ data.product_id +', '+ data.qtd +')"><i class="far fa-times-circle"></i></a></td>\
                        </tr>');
                });
                
                totalPagamentoNfe();
            }
        });
    } 

    function deletaProdutoCodNfe(item_venda_nfe_id, product_id, qtd)
    {
        $.ajax(
            {
            url: "deletaprodutocodnfe/"+item_venda_nfe_id+"/"+product_id+"/"+qtd+"",
            type: 'DELETE',
            data: {
                "item_venda_nfe_id": item_venda_nfe_id,
                "product_id"       : product_id,
                "qtd"              : qtd,
            },
            success: function(){
                $('#valor_recebido').val("");
                $('#troco').val("");
                
                getProdutoNfe(product_id);
            }
        });
    }

    function totalPagamentoNfe()
    {
        var forma_pagamento = $('#forma_pagamento').val();
        var valor_recebido  = $('#valor_recebido').val().replaceAll(".", "").replaceAll(",", ".");
        var troco           = 0;

        $.ajax(
            {
            url: "totalpagamentonfe",
            type: 'GET',
            success: function(data){
                var options = { 
                    currency: 'BRL', 
                    minimumFractionDigits: 2, 
                    maximumFractionDigits: 3 
                };
                
                var formatNumber = new Intl.NumberFormat('pt-BR', options);

                if (forma_pagamento == "02" || forma_pagamento == "03" || forma_pagamento == "04")
                    troco = troco;
                else 
                    troco = valor_recebido - data;
                
                troco = troco.toFixed(2);
                troco = formatNumber.format(troco);
                data  = formatNumber.format(data);
                
                if (valor_recebido == "" || data == "0,00")
                    $('#troco').val("");
                else
                    $('#troco').val(troco);
                
                if (data == "0,00")
                    $('#total_venda_nfe').val("");
                else
                    $('#total_venda_nfe').val(data);
            }
        });
    }

    function removeProdutos(){
        $.ajax({
            url: "deletaprodutosnfe",
            type: 'DELETE',
            success: function(){
            console.log('Produtos deletados!');
            }
        });
    }

 
</script>
@endpush
