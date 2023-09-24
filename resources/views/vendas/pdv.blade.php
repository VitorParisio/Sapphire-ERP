  <!DOCTYPE html>
  <html lang="pt-br">
  <head>
  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="css/style.css">
    <title>PDV - SapphireRP</title>
  </head>
  <body style="background:linear-gradient(45deg, black, transparent);">
    <div id="preloader_full"><img src="{{asset('img/preloader.gif')}}" alt=""></div>
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
      <input type="hidden" id="caixa_id_pdv" value="{{$dados_caixa->caixa_id_pdv}}">
    </div>
    <input type="text" class="letreiro" id="letreiro" placeholder="CAIXA LIVRE" autocomplete="off" style="font-style:italic">
    <div class="pdv">
      <div class="itens_vendas">
        <div>
          <table class="table table-bordered table_itens_vendas">
            <div class="preloader" id="preloader_itens_vendas"><img src="{{asset('img/preloader.gif')}}" alt=""></div>
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
        <div style="display: flex; align-items:center; margin-top: 438px; justify-content: space-between; position: absolute;">
          <div style="display: flex; margin-top:-180px;">
            <div class="lista_produtos_input" style="width: 485px;"></div>
            <input type="text" id="cod_barra" placeholder="Código/Produto" style="outline:none; text-align:center; width: 485px;" autocomplete="off" >
            <form id="form_cod_barra" action="#">
              @csrf
              <input type="text" id="qtd" placeholder="1" style="outline:none; text-align:center; height:41px" autocomplete="off" >
              <button type="submit" style="display: none;"></button>
            </form>
          </div>
        </div>
      </div>
      <div class="dados_itens_venda">
        <div id="dado_descricao">
          <span style="font-weight: bold">DESCRIÇÃO DO PRODUTO</span>
          <input type="text" id="descricao">
        </div>
        <div id="dado_qtd">
          <span style="font-weight: bold">QUANTIDADE</span>
          <input type="text" id="qtd_produto">
        </div>
        <div id="dado_valor_unitario">
          <span style="font-weight: bold">VALOR UNITÁRIO(R$)</span>
          <input type="text" id="valor_unitario">
        </div>
        <div id="dado_subtotal">
          <span style="font-weight: bold">SUBTOTAL(R$)</span>
          <input type="text" id="subtotal">
        </div> 
      </div> 
      <div>
        @include('modals.pdv.tabela_produto')
      </div>
      <div>
        @include('modals.pdv.pagamento')
      </div>
      <button id="btn_modal_venda" data-toggle="modal" data-target="#pagamento_modal" style="display: none"></button>
      <button id="btn_modal_tabela_produto" data-toggle="modal" data-target="#tabela_produto_modal" style="display: none"></button>
    </div>
    <div style="position:absolute; right:0; margin-top:13px">
      <label for="total_venda" style="font-size:35px; font-weight:bold;">TOTAL</label><br>
      <input type="text" class="total_venda" id="total_venda"/>
    </div>
    <div class="lista_atalhos">
      <div class="lista_teclas">
        <div></div>
        <div>
          <span>Alt + 3 - INICIAR VENDA&nbsp<i class="fas fa-sticky-note"></i></span>
          <span>Alt + Q - BUSCAR PRODUTO&nbsp<i class="fas fa-search"></i></span>
        </div>
        <div>
          <span>Alt + ENTER - PAGAMENTO&nbsp<i class="fas fa-money-bill-wave"></i></span>
          <span>Alt + R - FECHAR CAIXA&nbsp<i class="fas fa-cash-register"></i></span>
        </div>
      </div>
    </div>
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
              $( "#btn_modal_venda" ).trigger( "click" ); 

            if (tecla_pressionada['Alt'] && e.key == 'q')
              $( "#btn_modal_tabela_produto" ).trigger( "click" ); 

            if (tecla_pressionada['Alt'] && e.key == 'r')
            {
              
              swal("Tem certeza que deseja fechar o caixa?", {
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
                icon:"warning", 
                }).then((value) => {
                  if (value === "yes") {
                    $("#preloader_full").css({'display' : 'block'});

                    setTimeout(() => {
                      $("#preloader_full").css({'display' : 'none'});
                     window.location.href = "/fecha_caixa";
                    }, 1000); 
                  } 
                }); 
            }
             
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

        $('#valor_recebido').blur(function(){
          if ($('#valor_recebido').val() == '')
            $('#valor_recebido').prop( "placeholder", "A RECEBER" );
        });

        $('#desconto').blur(function(){
          if ($('#desconto').val() == '')
            $('#desconto').prop( "placeholder", "DESCONTO%" );
          totalPagamento();
        });

        $('#valor_recebido').blur(function(){
          totalPagamento();
        });

        $('#valor_recebido').focus(function(){
          $('#valor_recebido').prop( "placeholder", "0,00" );
        });

        $('#desconto').focus(function(){
          $('#desconto').prop( "placeholder", "%" );
        });
      });

      function openCupom() {
        var popupWindow = window.open('/cupom', '_blank', "width=300, height=600");
        popupWindow.focus();
        popupWindow.print(); 
      }
  </script>
  </body>
</html>