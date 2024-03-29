$(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var element = document.getElementById("pagamento_pdv_mobile");
    var mq      = window.matchMedia( "(max-width: 768px)" );
    
    document.getElementById('cod_barra').focus();

    $('#cod_barra').on('keyup', function(){
        var data = $(this).val();
        getProdutoSearch(data);
    });

    $('#cod_barra_mobile').on('keyup', function(){
        var data = $(this).val();
        getProdutoSearch(data);
    });

    $('.produto_search_tabela').on('keyup',function(){
        var data = $(this).val();
        getProdutoTabela(data);
    });

    $('.list_venda_pdv_table_search').on('keyup',function(){
        var data = $(this).val();
        getVendasTabela(data)
    });

    $(document).on('click', '.cancela_venda_pdv_link', function(){
        var nro_cupom_cancel = $(this).closest("tr").children(":first").text();

        swal("Tem certeza que deseja cancelar a venda do cupom nº "+nro_cupom_cancel+"?", {
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
                    url:"/cancelavendapdv/"+nro_cupom_cancel,
                    method: 'GET',
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
                            getVendasTabela();
                            conferenciaCaixa();
                            $('#cancela_venda_modal').modal('hide');
                        });  
                    }
                });
            }
        });
    });

    $(document).on('click', 'li', function(){
        var item = $(this).text();
        $('#cod_barra').val(item);
        $('#cod_barra_mobile').val(item);
        $('.lista_produtos_input').html("")
        $('.lista_produtos_mobile').html("")
        document.getElementById('qtd').focus();
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
        if ( $('#cod_barra').val() == ""){
            return
        }
        var user_id      = $('#user_id').val();
        var numero       = $('#numero').val();
        var cod_barra    = $('#cod_barra').val();
        var caixa_id_pdv = $('#caixa_id_pdv').val();
        var qtd          = $('#qtd').val();

        $.ajax({
            url: '/estoque_negativo',
            method: 'GET',
            data: {cod_barra: cod_barra, qtd: qtd},
            dataType: 'JSON',
            beforeSend: () =>{
                $("#preloader_itens_vendas").css({'display' : 'block'});
            },
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
                            $.post('addproduto', {user_id: user_id, caixa_id_pdv: caixa_id_pdv, numero: numero, cod_barra: cod_barra, qtd: qtd})
                            .done(function(data){
                                $("#preloader_itens_vendas").css({'display' : 'none'});
                                getProduto(cod_barra);
                                getProdutoTabela();
                                document.getElementById('cod_barra').focus();    
                            });
                        }

                        $('#cod_barra').val("");
                        $('#qtd').val("");

                        document.getElementById('cod_barra').focus();
                        $("#preloader_itens_vendas").css({'display' : 'none'});

                        return false;

                    });
                }
                else
                {
                    $.post('addproduto', {user_id: user_id, caixa_id_pdv: caixa_id_pdv, numero: numero, cod_barra: cod_barra, qtd: qtd})
                    .done(function(data){
                        $("#preloader_itens_vendas").css({'display' : 'none'})
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
                 $("#preloader_itens_vendas").css({'display' : 'none'})
              }
            }
        });
    }); 

    if (!mq.matches) {
        element.remove();
    } 

    $('#valor_recebido').mask("000.000.000.000.000,00", {reverse: true});

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

            var total_venda        = formatNumber.format(data.total_venda);
            var total_itens_mobile = data.total_itens_mobile;

            $('tbody').html("");
            $('.total_venda').val();
            
            $.each(produto, function(index, data){
                if (data.qtd_atacado != null)
                    var valor_unitario = data.qtd >= data.qtd_atacado ? formatNumber.format(data.preco_atacado) : formatNumber.format(data.preco_venda);
                else
                    var valor_unitario = formatNumber.format(data.preco_venda);

                var subtotal = formatNumber.format(data.sub_total);
              
                $('#letreiro').val(data.nome).css({'letter-spacing' : '5px', 'font-weight' : 'bold', 'text-transform': 'uppercase', 'color' : 'grey', 'font-style': 'italic'});
                $('#descricao').val(data.descricao);
                $('#qtd_produto').val(data.qtd);
                $('#valor_unitario').val(valor_unitario);
                $('#subtotal').val(subtotal);
            })

            $.each(pdv,function(index,data){
                if (data.qtd_atacado != null)
                    var preco_venda = data.qtd >= data.qtd_atacado ? formatNumber.format(data.preco_atacado) : formatNumber.format(data.preco_venda);
                else
                    var preco_venda = formatNumber.format(data.preco_venda);

                var sub_total = formatNumber.format(data.sub_total);
                var img_tag   = data.img != null ? '<img src="storage/'+data.img+'" alt="img-item" />' : '<img src="img/sem_imagem.png"/>';
                
                $('.table_itens_vendas tbody').append('<tr>\
                    <td data-label="#">'+img_tag+'</td>\
                    <td data-label="Item">'+data.nome.toUpperCase()+'</td>\
                    <td data-label="VL. Unitário">'+preco_venda+'</td>\
                    <td data-label="Quantidade">'+data.qtd+'</td>\
                    <td data-label="Subtotal">'+sub_total+'</td>\
                    <td data-label="Excluir"><a href="#" onclick="deletaProdutoCod('+ data.item_venda_id +','+ data.product_id +', '+ data.qtd +')"><i class="fas fa-times-circle" style="color:red;"></i></a></td>\
                    </tr>');
            });

            $('#cod_barra').val('');
            $('#qtd').val('');
            $('.total_venda').val(total_venda);
            $('.total_produtos_mobile span').text(total_itens_mobile);

            totalPagamento();
            getProdutoTabela();
            getVendasTabela();
            conferenciaCaixa();
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
          $('.lista_produtos_mobile').html(data);
        }
    })
}

function getProdutoTabela(query = '')
{   
    $.ajax({
        url:"/getprodutotable/"+query,
        method: 'GET',
        success:function(data)
        {   
           $('.table_produto_list_pdv tbody').html(data.produto_nome_busca);
        }
    });
}

function getVendasTabela(query = '')
{   
    $.ajax({
        url:"/getvendastablepdv/"+query,
        method: 'GET',
        success:function(data)
        {   
           $('.tabela_vendas_pdv tbody').html(data.venda_pdv_busca);
        }
    });
}

function conferenciaCaixa()
{
    $.ajax({
        url:"/conferenciacaixa/",
        method: 'GET',
        success:function(data)
        {   
           $('.tabela_conferencia_caixa tbody').html(data.conferencia_caixa);
           $('.total_venda_conferencia_caixa').html(data.conferencia_caixa_total);
           $('.total_values_conferencia_caixa').html(data.total_row_conferencia_caixa);
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
        beforeSend: () => {
            $("#preloader_troco").css({'display' : 'block'});
            $("#disabled_btn_finaliza_venda").prop('disabled', true);
        },
        success:function(data){
            $("#preloader_troco").css({'display' : 'none'});
            $("#disabled_btn_finaliza_venda").prop('disabled', false);
           
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
        beforeSend: () =>{
            $("#preloader_full").css({'display' : 'block'});
        },
        success: function(){
            $('#letreiro').val('Produto removido')
            .css({'letter-spacing' : '5px', 'font-weight' : 'bold', 'text-transform': 'uppercase', 'color' : 'grey', 'font-style': 'italic'});
            $('#descricao').val('')
            $('#qtd_produto').val('')
            $('#valor_unitario').val('')
            $('#subtotal').val('')
            $("#preloader_full").css({'display' : 'none'});
      
            document.getElementById('cod_barra').focus();

            getProduto();
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
    var caixa_id_pdv    = $('#caixa_id_pdv').val();
    var numero          = $('#numero').val();
    var id_cupom        = $('#id_cupom').val();
    var forma_pagamento = $('#forma_pagamento').val();
    var valor_recebido  = $('#valor_recebido').val();
    var desconto        = $('#valor_desconto').val();
    var total_venda     = $('#total_pagamento').val();
    var troco           = $('#troco').val();
    var datos_itens     = {user_id: user_id, caixa_id_pdv:caixa_id_pdv, numero: numero, id_cupom: id_cupom, total_venda: total_venda, valor_recebido: valor_recebido, forma_pagamento: forma_pagamento, desconto : desconto, troco : troco}
    
$.ajax({
    type: "POST",
    url: 'finalizavenda',
    data: datos_itens,
    beforeSend: () =>{
        $('.modal').hide();
        $('.modal-backdrop').hide();
        $("#preloader_full").css({'display' : 'block'});
    },
    success:function(data)
    {
        getVendasTabela();
        conferenciaCaixa();
        removeProdutos();
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
        $('.total_produtos_mobile span').text("0");
        $("#preloader_full").css({'display' : 'none'});
        document.getElementById('cod_barra').focus();
        openCupom();
    }
  });
}





