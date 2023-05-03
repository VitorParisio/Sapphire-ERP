
$(function(){
    $('.preco_compra_editar').mask("000.000.000.000.000,00", {reverse: true});
    $('.preco_venda_editar').mask("000.000.000.000.000,00", {reverse: true});
    $('#preco_compra').mask("000.000.000.000.000,00", {reverse: true});
    $('#preco_venda').mask("000.000.000.000.000,00", {reverse: true});
    $('#valor_recebido').mask("000.000.000.000.000,00", {reverse: true});
    $('#desconto').mask("000.000.000.000.000,00", {reverse: true});
});