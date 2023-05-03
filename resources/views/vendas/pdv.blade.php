@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content')
  <input type="text" class="letreiro" id="letreiro" placeholder="CAIXA LIVRE" autocomplete="off" style="font-style:italic">
  <div class="pdv">
    <div class="itens_vendas">
      <table class="table table-bordered table_itens_vendas">
        <thead>
          <tr>
            <th>#</th>
            <th>Item</th>
            <th>Valor unitário</th>
            <th>Qtde.</th>
            <th>Sub-Total</th>
            <th><i class="fas fa-trash"></i></th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
    <div class="dados_itens_venda">
      <div id="dado_descricao">
        <h3>Descrição do produto</h3>
        <input type="text" id="descricao">
      </div>
      <div id="dado_qtd">
        <h3>Quantidade</h3>
        <input type="text" id="qtd_produto">
      </div>
      <div id="dado_valor_unitario">
        <h3>Valor unitário</h3>
        <input type="text" id="valor_unitario">
      </div>
      <div id="dado_subtotal">
        <h3>Sub-total</h3>
        <input type="text" id="subtotal">
      </div>
    </div>
  </div> 
  <div class="" style="display: flex; align-items:center;">
    <div class="" style="display: flex;">
      <input type="text" id="cod_barra" placeholder="Código de Barra" style="outline:none; text-align:center" autocomplete="off" >
      <form id="form_cod_barra" action="#">
        @csrf
        <input type="text" id="qtd" placeholder="1" style="outline:none; text-align:center; height:41px" autocomplete="off" >
        <button type="submit" style="display: none;"></button>
      </form>
    </div>
    <div class="total_venda"></div>
  </div>
  @include('modals.pagamento')
  <button id="btn_modal_venda" data-toggle="modal" data-target="#pagamento_modal" style="display: none"></button>
@stop

@push('scripts')
  <script src="{{ asset('js/mask.js') }}"></script>
  <script>
    $(function(){
      var tecla_pressionada = {};
      document.addEventListener('keydown', (e) => {
          tecla_pressionada[e.key] = true;
          if (tecla_pressionada['Control'] && e.key == 'Enter')
            $( "#btn_modal_venda" ).first().trigger( "click" );  
      });
      document.addEventListener('keyup', (e) => {
          delete tecla_pressionada[e.key];
      });

      $('#letreiro').prop( "disabled", true );
      $('#descricao').prop( "disabled", true );
      $('#qtd_produto').prop( "disabled", true );
      $('#valor_unitario').prop( "disabled", true );
      $('#subtotal').prop( "disabled", true );
      $('#subtotal').prop( "disabled", true );
      $('#total').prop( "disabled", true );
      $('#troco').prop( "disabled", true );
      $('#total_pagamento').prop( "disabled", true );
      $('#desconto').prop( "disabled", true );
      
      $('#valor_recebido').focus(function(){
        $('#valor_recebido').prop( "placeholder", "0,00" );
      });

      $('#desconto').focus(function(){
        $('#desconto').prop( "placeholder", "0,00" );
      });
      
      $('#valor_recebido').blur(function(){
        if ($('#valor_recebido').val() == '')
          $('#valor_recebido').prop( "placeholder", "A RECEBER" );
      });

      $('#desconto').blur(function(){
        if ($('#desconto').val() == '')
          $('#desconto').prop( "placeholder", "DESCONTO" );
      });

      $('#valor_recebido').blur(function(){
        totalPagamento()
      });
    });
  </script>
@endpush