$(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    document.getElementById('cod_barra').focus();

    $('#cod_barra').on('keyup',function(){
        var data = $(this).val();
        getProdutoSearch(data);
    });

    $('.produto_search_tabela').on('keyup',function(){
        var data = $(this).val();
        getProdutoTabela(data);
    });

    $(document).on('click', 'li', function(){
        var item = $(this).text();
        $('#cod_barra').val(item);
        document.getElementById('qtd').focus();
        $('.lista_produtos_input').html("")
    });

    $(document).on('click', '.produto_nome_busca', function(){
        var item = $(this).text();
        $('#cod_barra').val(item);
        $('.produto_search_tabela').val("");
        $('.modal').hide();
        $('.modal-backdrop').hide();
        document.getElementById('qtd').focus();
        getProdutoTabela();
       
    });

    $('.total_venda').val('R$ 0,00');

    $('#cod_barra').keypress(function(){
        document.getElementById('qtd').focus();
    });

    $('#form_cod_barra').submit(function(e){
        e.preventDefault();
        
        var user_id   = $('#user_id').val();
        var numero    = $('#numero').val();
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
                    swal("Excedeu o limite do estoque. Desejas continuar com a venda?", {
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
                            $.post('addproduto', {user_id: user_id, numero: numero, cod_barra: cod_barra, qtd: qtd})
                            .done(function(data){
                                getProduto(cod_barra);
                                getProdutoTabela();
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
                    $.post('addproduto', {user_id: user_id, numero: numero, cod_barra: cod_barra, qtd: qtd})
                    .done(function(data){

                        getProduto(cod_barra);
                        getProdutoTabela();
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

    $('#valor_recebido').mask("000.000.000.000.000,00", {reverse: true});

    getProduto();
    getProdutoTabela();
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
            $('.total_venda').val();
            
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
            $('.total_venda').val(total_venda);
            totalPagamento();
        }
    });
}

function getProdutoSearch(data = '')
{
    $.ajax({
        url: "getprodutosearch/"+data,
        type:'GET',
        success: function(data) {
            $('.lista_produtos_input').html(data);
        }
    })
}

function getProdutoTabela(query = '')
{   
    $.ajax({
        url:"/getprodutotable",
        method: 'GET',
        dataType: 'json',
        data:{query: query},
        success:function(data)
        {   
            $('.table_produto_list_pdv tbody').html(data.produto_nome_busca);
        }
    });
}
function totalPagamento(){
    var valor_recebido       = $('#valor_recebido').val().replaceAll(".", "").replaceAll(",", ".");;
    var valor_desconto       = $('#desconto').val().replaceAll(".", "").replaceAll(",", ".");;
    var desconto_porcentagem = '';
    var desconto             = '';
    var troco                = 0.00;
   
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
            
            desconto_porcentagem = valor_desconto / 100
            desconto             = desconto_porcentagem * data;
            data                 = data - desconto;
            troco                = valor_recebido - data;
            
            troco     = troco.toFixed(2);
            data      = data.toFixed(2);
            desconto  = desconto.toFixed(2);
            
            troco     = formatNumber.format(troco);
            data      = formatNumber.format(data);
            desconto  = formatNumber.format(desconto);
            
            if (valor_recebido == "")
                $('#troco').prop('placeholder', '0,00');
            else
                $('#troco').val(troco);

            $('#valor_desconto').val(desconto);    
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

            getProduto();
            getProdutoTabela();
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
    var desconto        = $('#valor_desconto').val();
    var total_venda     = $('#total_pagamento').val();
    var troco           = $('#troco').val();

    $.post('finalizavenda', {user_id: user_id, numero: numero, id_cupom: id_cupom, total_venda: total_venda, valor_recebido: valor_recebido, forma_pagamento: forma_pagamento, desconto : desconto, troco : troco}, function(data){
        removeProdutos();
        $('.modal').hide();
        $('.modal-backdrop').hide();
        $('.total_venda').val('R$ 0,00');
        $('.table_itens_vendas tbody').html("");
        $('#valor_recebido').val('');
        $('#desconto').val('');
        $('#valor_recebido').prop('placeholder', 'A RECEBER');
        $('#desconto').prop('placeholder', 'DESCONTO%');
        $('#valor_desconto').val('');
        $('#troco').val('');
        $('#total_pagamento').val('').prop('placeholder', '0,00');
        $('#descricao').val('');
        $('#qtd_produto').val('');
        $('#valor_unitario').val('');
        $('#subtotal').val('');
        $('.letreiro').val("CAIXA LIVRE");
        document.getElementById('cod_barra').focus();
        openCupom();
    });
}





