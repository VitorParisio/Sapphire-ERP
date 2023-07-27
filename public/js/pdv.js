$(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    document.getElementById('cod_barra').focus();

    $('.total_venda').append('Total: R$ 0,00');

    $('#cod_barra').keypress(function(){
        document.getElementById('qtd').focus();
    });

    $('#form_cod_barra').submit(function(e){
        e.preventDefault();
        
        var user_id   = $('#user_id').val();
        var numero    = $('#numero').val();
        var id_cupom  = $('#id_cupom').val();
        var cod_barra = $('#cod_barra').val();
        var qtd       = $('#qtd').val();

        $.ajax({
            url: '/estoque_negativo',
            method: 'GET',
            data: {cod_barra: cod_barra, qtd: qtd},
            dataType: 'JSON',
            success:function(data)
            {
              if (!data.error)
              {
                if (data)
                {
                    console.log(data)
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
                            $.post('addproduto', {user_id: user_id, numero: numero, id_cupom: id_cupom, cod_barra: cod_barra, qtd: qtd})
                            .done(function(data){
                                getProduto(cod_barra);
                                document.getElementById('cod_barra').focus();    
                            });
                        }

                        $('#cod_barra').val("");
                        $('#qtd').val("");

                        document.getElementById('cod_barra').focus();

                        return false;

                    });
                }
                else
                {
                    $.post('addproduto', {user_id: user_id, numero: numero, id_cupom: id_cupom, cod_barra: cod_barra, qtd: qtd})
                    .done(function(data){
                    
                        getProduto(cod_barra);
                        document.getElementById('cod_barra').focus();    
                    });
                }
              }
              else{
                swal({
                     text: data.error,
                     icon: "warning",
                 });
                 $('#cod_barra').val("");
                 $('#qtd').val("");
                 document.getElementById('cod_barra').focus(); 
              }
            }
        });
    }); 

    getProduto();
});

function getProduto(data)
{
    $.ajax({
        url: "getproduto/"+ data,
        type:'GET',
        success: function(data) {
            var options = { 
                style: 'currency', 
                currency: 'BRL', 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 3 
            };

            var formatNumber = new Intl.NumberFormat('pt-BR', options);
            var produto      = data.produto;
            var pdv          = data.pdv;
            var total_venda  = formatNumber.format(data.total_venda);

            $('tbody').html("");
            $('.total_venda').html("");

            $.each(produto, function(index, data){
                var valor_unitario = formatNumber.format(data.preco_venda);
                var subtotal       = formatNumber.format(data.sub_total);

                $('#letreiro').val(data.nome).css({'letter-spacing' : '5px', 'font-weight' : 'bold', 'text-transform': 'uppercase', 'color' : 'grey'});
                $('#descricao').val(data.descricao);
                $('#qtd_produto').val(data.qtd);
                $('#valor_unitario').val(valor_unitario);
                $('#subtotal').val(subtotal);
            })

            $.each(pdv,function(index,data){
                var preco_venda = formatNumber.format(data.preco_venda);
                var sub_total   = formatNumber.format(data.sub_total);
                var img_tag     = data.img != null ? '<img src="storage/'+data.img+'" alt="img-item" />' : '<i class="fas fa-file-image fa-3x"></i>'
                
                $('tbody').append('<tr>\
                    <td>'+img_tag+'</td>\
                    <td>'+data.nome+'</td>\
                    <td>'+preco_venda+'</td>\
                    <td>'+data.qtd+'</td>\
                    <td>'+sub_total+'</td>\
                    <td><a href="#" onclick="deletaProdutoCod('+ data.item_venda_id +','+ data.product_id +', '+ data.qtd +')"><i class="fas fa-times-circle" style="color:red;"></i></a></td>\
                    </tr>');
            });

            $('#cod_barra').val('');
            $('#qtd').val('');
            $('.total_venda').append('Total: '+total_venda);
            totalPagamento();
        }
    });
}

function totalPagamento(){
    var forma_pagamento = $('#forma_pagamento').val();
    var valor_recebido  = $('#valor_recebido').val().replaceAll(".", "").replaceAll(",", ".");
    //var desconto      = $('#desconto').val().replaceAll(".", "").replaceAll(",", ".");
    var troco           = 0.00;

    $.ajax(
        {
        url: "totalpagamento",
        type: 'GET',
        success: function(data){
            var options = { 
                currency: 'BRL', 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 3 
            };
            var formatNumber = new Intl.NumberFormat('pt-BR', options);

            if (forma_pagamento == "Cartão de Crédito" || forma_pagamento == "Cartão de Débito")
                troco = troco;
            else 
                troco = valor_recebido - data;
            
            // if (desconto > 0 && troco > 0){
            //     troco = troco;
            //     data  = data - desconto;
            // } 

            troco = troco.toFixed(2);
            troco = formatNumber.format(troco);
            data  = formatNumber.format(data);

            if (valor_recebido < 1)
                $('#troco').prop('placeholder', '0,00');
            else
                $('#troco').val(troco);

            $('#total_pagamento').val(data);
        }
    });
}

function deletaProdutoCod(item_venda_id, product_id, qtd)
{
    $.ajax(
        {
        url: "deletaprodutocod/"+item_venda_id+"/"+product_id+"/"+qtd+"",
        type: 'DELETE',
        data: {
            "item_venda_id": item_venda_id,
            "product_id"   : product_id,
            "qtd"          : qtd,
        },
        success: function(){
            $('#letreiro').val('Produto removido')
            .css({'letter-spacing' : '5px', 'font-weight' : 'bold', 'text-transform': 'uppercase', 'font-style' : 'italic'});
            $('#descricao').val('')
            $('#qtd_produto').val('')
            $('#valor_unitario').val('')
            $('#subtotal').val('')
            document.getElementById('cod_barra').focus();

            getProduto(cod_barra);
        }
    });
}

function removeProdutos(){
    $.ajax(
        {
        url: "deletaprodutos",
        type: 'DELETE',
        success: function(){
           console.log('Produtos deletados!');
        }
    });
}

function finalizarVenda(){

    var user_id         = $('#user_id').val();
    var numero          = $('#numero').val();
    var id_cupom        = $('#id_cupom').val();
    var forma_pagamento = $('#forma_pagamento').val();
    var valor_recebido  = $('#valor_recebido').val();
    var desconto        = $('#desconto').val();
    var total_venda     = $('#total_pagamento').val();
    var troco           = $('#troco').val();

    $.post('finalizavenda', {user_id: user_id, numero: numero, id_cupom: id_cupom, total_venda: total_venda, valor_recebido: valor_recebido, forma_pagamento: forma_pagamento, desconto : desconto, troco : troco}, function(data){
        removeProdutos();
        $('.modal').hide();
        $('.modal-backdrop').hide();
        $('.total_venda').html('Total: R$ 0,00');
        $('.table_itens_vendas tbody').html("");
        $('#valor_recebido').val('');
        $('#valor_recebido').prop('placeholder', 'A RECEBER');
        $('#desconto').prop('placeholder', 'DESCONTO');;
        $('#troco').val('');
        $('#total_pagamento').val('');
        $('#descricao').val('');
        $('#qtd_produto').val('');
        $('#valor_unitario').val('');
        $('#subtotal').val('');
        $('#letreiro').val("CAIXA LIVRE");
        // $('#letreiro').val("CAIXA LIVRE");
        document.getElementById('cod_barra').focus();
        openCupom();
    });
}





