  <!DOCTYPE html>
  <html lang="pt-br">
  <head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Ssoft - Vitor Parísio">
    <meta name="keywords" content="smartsoft, ssoft, smartnet, softwares de vendas, sistema de vendas">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="css/style.css">
    <title>PDV - SapphireRP</title>
  </head>
  <body>
    <div style="display:flex; background:#183c70; color:#FFF;">
      <div class="operador" style="border-right: 1px solid #FFF;padding-right: 15px;padding-left: 12px">
        <span><b>Operador:</b>&nbsp<i>{{$dados_caixa->name}}</i></span>
      </div>
      <div class="nro_caixa_pdv" style="padding-left:12px;">
        <span><b>{{$dados_caixa->descricao}}</b></span>
      </div>
      <input type="hidden" id="user_id" value="{{$dados_caixa->user_id}}">
      <input type="hidden" id="numero" value="{{$dados_caixa->numero}}">
      <input type="hidden" id="id_cupom" value="{{$id_cupom}}">
    </div>
    <input type="text" class="letreiro" id="letreiro" placeholder="CAIXA LIVRE" autocomplete="off" style="font-style:italic">
    <div class="pdv">
      <div class="itens_vendas">
        <div>
          <table class="table table-bordered table_itens_vendas">
            <thead>
              <tr>
                <th>#</th>
                <th>Item</th>
                <th>Valor unitário(R$)</th>
                <th>Qtd</th>
                <th>Subtotal(R$)</th>
                <th><i class="fas fa-times-circle"></i></th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <div class="" style="display: flex; align-items:center; margin-top: 400px; justify-content: space-between;">
          <div class="" style="display: flex;">
            <input type="text" id="cod_barra" placeholder="Código/Produto" style="outline:none; text-align:center" autocomplete="off" >
            <form id="form_cod_barra" action="#">
              @csrf
              <input type="text" id="qtd" placeholder="1" style="outline:none; text-align:center; height:41px" autocomplete="off" >
              <button type="submit" style="display: none;"></button>
            </form>
            <input type="text" class="total_venda" id="total_venda"/>
          </div>
        </div>
      </div>
      <div class="dados_itens_venda">
        <div id="dado_descricao">
          <h3>DESCRIÇÃO DO PRODUTO</h3>
          <input type="text" id="descricao">
        </div>
        <div id="dado_qtd">
          <h3>QUANTIDADE</h3>
          <input type="text" id="qtd_produto">
        </div>
        <div id="dado_valor_unitario">
          <h3>VALOR UNITÁRIO(R$)</h3>
          <input type="text" id="valor_unitario">
        </div>
        <div id="dado_subtotal">
          <h3>SUBTOTAL(R$)</h3>
          <input type="text" id="subtotal">
        </div>
      </div>
    </div>
    <div class="lista_atalhos">
      <div class="lista1">
        <div>
          <span><i class="fas fa-sticky-note"></i>&nbspAlt+3 - Abrir cupom</span>
          <span><i class="fas fa-money-bill-wave"></i>&nbspAlt+ENTER - Pagamento</span>
        </div>
        <div>
          <span><i class="fas fa-list-alt"></i>&nbspAlt+T - Tabela/preços</span>
          <span><i class="fas fa-cash-register"></i>&nbspAlt+F - Fechar caixa</span>
        </div>
      </div>
    </div> 
    @include('modals.pdv.pagamento')
    <button id="btn_modal_venda" data-toggle="modal" data-target="#pagamento_modal" style="display: none"></button>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="{{ asset('js/pdv.js') }}"></script>
    <script>
      $(function(){
        var tecla_pressionada = {};

        document.addEventListener('keydown', (e) => {
            tecla_pressionada[e.key] = true;
            if (tecla_pressionada['Alt'] && e.key == 'Enter')
              $( "#btn_modal_venda" ).first().trigger( "click" ); 
            
            if (tecla_pressionada['Alt'] && e.key == '3') 
              document.getElementById('cod_barra').focus();
        });

        document.addEventListener('keyup', (e) => {
            delete tecla_pressionada[e.key];
        });

        $('#letreiro').prop( "disabled", true );
        $('#descricao').prop( "disabled", true );
        $('#qtd_produto').prop( "disabled", true );
        $('#valor_unitario').prop( "disabled", true );
        $('#subtotal').prop( "disabled", true );
        $('#total').prop( "disabled", true );
        $('#troco').prop( "disabled", true );
        $('#total_pagamento').prop( "disabled", true );
        $('#total_venda').prop( "disabled", true );
        
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
          totalPagamento();
        });
      });

      function openCupom() {
        var popupWindow = window.open('/cupom', '_blank', "width=300, height=600");
        popupWindow.focus();
      }
  </script>
  </body>
</html>