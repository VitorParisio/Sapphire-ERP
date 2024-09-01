<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Fechamento Caixa - SapphirERP</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <style>
        body{
            margin:0;
        }
        ul{
            list-style: none
        }
        .fechamento_caixa .header_fechamento_caixa {
            text-align: center;
            background-color: teal;
        }
        .fechamento_caixa .header_fechamento_caixa span{
           color:#FFF;
           font-weight: bold;
           font-size: 25px;
        }
        .callout{
            border-radius: 0.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 1px 2px rgba(0,0,0,.24);
            background-color: #fff;
            border-left: 5px solid #e9ecef;
            margin-bottom: 1rem
        }
        .callout.callout-info{
            border-left-color: #117a8b;
            padding: 0px;
            width: 300px;
            margin-left: 10px;
        }
        .callout .header_valores_caixa{
            text-align: center;
            background: #117a8b;
            padding: 8px 25px;
            color: #FFF;
            font-weight: bold;
            font-size: 19px;
            width: 100%;
        }
        .callout ul li{
            margin:10px 0;
        }

        .btn_fechar_caixa{
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn_fechar_caixa button{
            background: #7d8d97;
            color:#FFF;
            padding: 15px;
            border:none;
            font-weight: bold;
            font-size:20px;
            outline: none;
        }

        .btn_fechar_caixa button:hover{
            background: #5c666c;
        }

        table{
            text-align:center; 
            width:100%;
        }
        table thead{
            background: teal;
            color: #FFF;
        }

        .not_shell{
            text-align: center;
            font-family: monospace;
            font-style: italic;
        }
        #preloader_full{
            display: none;
            position: absolute;
            height:100vh;
            width: 100%;
            overflow: none;
            background-color: rgba(0,0,0,.6);
            text-align: center;
            z-index: 99999;
        }

        .fechamento_caixa  div:nth-child(4) .valores_pagamento_pdv ul li input{
            width: 100%;
        }
 
        #preloader_full img{
            margin: 290px auto;
            position: relative;
            height: 100px;
        }

        @media(max-width: 768px){
            .fechamento_dados{
                flex-wrap: wrap
            }
            .callout.callout-info{
                width: 100% !important;
                margin-left: 0px;
            }
            
            table thead tr{
              display: none;
            }

            .fechamento_dados .valores_pagamento_pdv{
                flex-direction: column !important;
            }

        } 

    </style>
</head>
<body>
    <div class="fechamento_caixa" style="margin: 0 auto;">
        <div id="preloader_full"><img src="{{asset('img/preloader.gif')}}" alt=""></div>
        <div class="errors"></div>
        <div class="header_fechamento_caixa">
            <span><i class="fas fa-cash-register"></i>&nbsp{{$caixa_info->descricao}} - FECHAMENTO</span>
        </div>
        <div class="fechamento_dados" style="display: flex; margin-top: 10px; gap: 10px;">
            <div class="callout callout-info">
                <div class="header_valores_caixa">
                    <span>VALORES</span>
                </div>
                <ul style="padding: 9px">
                    <li><b>FATURAMENTO:</b> R$ {{number_format($caixa_info->valor_vendido, 2,',','.')}}</li>
                    <li><b>TROCO:</b> R$ {{number_format($caixa_info->valor_abertura, 2,',','.')}}</li>
                    <li><b>RETIRADA:</b> R$ {{number_format($caixa_info->sangria, 2,',','.')}}</li>
                    <li><b>SUPRIMENTO:</b> R$ {{number_format($caixa_info->suplemento, 2,',','.')}}</li>
                    <li><b>TOTAL CAIXA:</b> R$ {{number_format($caixa_info->total_caixa, 2,',','.')}}</li>
                </ul>
            </div>
            <div class="valores_pagamento_pdv" style="display: flex; gap: 10px; width: 100%; justify-content: center;">
                <ul style="display:flex; flex-direction: column; gap: 10px; padding: 0;">
                    <li style="text-align:center; background:#0098ff; color:#FFF; font-weight:900; padding:10px;">PAGAMENTOS</li><hr>
                    @foreach($data as $key => $forma_pagamento_fechamento)
                        <li><input type="text" name="forma_pagamento_fechamento[]" class="forma_pagamento_fechamento_{{$forma_pagamento_fechamento}}" value="{{$forma_pagamento_fechamento}}" style="text-align:center; border:none; background: darkgrey; color:#FFF; padding:5px 0"></li>
                        <span class="msg_forma_pagamento_fechamento_{{$forma_pagamento_fechamento}}" style="display:none"></span>
                    @endforeach
                </ul>
                <ul style="display:flex; flex-direction: column; gap: 10px; padding: 0;">
                    <li style="text-align:center; background:#0098ff; color:#FFF; font-weight:900; padding:10px;">PREVISTOS(R$)</li><hr>
                    @foreach($conta_fechamentos as $key => $conta_fechamento_total)
                        <li><input type="text" name="total_fechamento[]" class="conta_fechamento_total_{{$data[$key]}}" value="{{$conta_fechamento_total->total_venda_fechamento}}" style="text-align:center; border:none; background: darkgrey; color:#FFF; padding:5px 0"></li>
                    @endforeach
                </ul>
                <ul style="display:flex; flex-direction: column; gap: 10px; padding: 0;">
                    <li style="text-align:center; background:#0098ff; color:#FFF; font-weight:900; padding:10px;">REALIZADOS(R$)</li><hr>
                    @for($i = 0; $i < count($conta_fechamentos); $i++)
                        <li><input type="text" name="valor_informado_fechamento[]" class="forma_pagamento_numero_{{$data[$i]}}" id="{{$conta_fechamentos[$i]->forma_pagamento}}" style="text-align:center; background: #ececec; border:none; padding:5px 0; outline:none"></li>
                    @endfor
                </ul>
                <ul style="display:flex; flex-direction: column; gap: 10px; padding: 0;">
                    <li style="text-align:center; background:#0098ff; color:#FFF; font-weight:900; padding:10px;">DIFERENÇAS(R$)</li><hr>
                    @for($i = 0; $i < count($conta_fechamentos); $i++)
                        <li><input type="text" name="diferenca_fechamento[]" class="diferenca_fechamento_{{$data[$i]}}" id="{{$conta_fechamentos[$i]->forma_pagamento}}" style="text-align:center; border:none; background: darkgrey; color:#FFF; padding:5px 0;"></li>
                    @endfor
                </ul>
            </div> 
        </div>
        <div class="btn_fechar_caixa">
            <button onclick="fechamentoCaixa({{$caixa_info->caixa_id}})"><i class="fas fa-lock"></i> FECHAR {{$caixa_info->descricao}}</button>
        </div>
        <hr>
        @if ($qtd_itens_vendidos > 0)
            <div class="card">
                <div class="card-header" style="display: flex;">
                    <span style="border-right: 1px solid rgba(0,0,0,0.3); padding-right:5px; font-size:17px; font-weight:bold">Resumo das vendas</span>&nbsp
                    <span>Total de itens: {{$qtd_itens_vendidos}}</span>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Valor venda</th>
                            <th>Quantidade</th>
                            <th>Total</th>
                        </thead>
                        <tbody>
                            @foreach($itens_vendidos as $item_vendido)
                            <tr>
                                <td data-title="Código">{{$item_vendido->id}}</td>
                                <td data-title="Produto">{{$item_vendido->nome}}</td>
                                <td data-title="Valor venda">R$ {{number_format($item_vendido->preco_venda, 2, ',','.')}}</td>
                                <td data-title="Quantidade">{{$item_vendido->item_qtd}}</td>
                                <td data-title="Total">R$ {{number_format($item_vendido->item_soma_total, 2, ',','.')}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="not_shell">
                <h3>Nenhuma venda realizada ao {{$caixa_info->descricao}}. <i class="fas fa-meh"></i></h3> 
            </div>
        @endif
    </div>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });   

            var cartao_credito_forma_pagamento = '';
            var cartao_debido_forma_pagamento  = '';
            var cheque_forma_pagamento         = '';
            var dinheiro_forma_pagamento       = '';
            var pix_forma_pagamento            = '';
            var is_atacado                     = '';
            var forma_pagamento_fechamento     = $("input[name='forma_pagamento_fechamento[]']")
                .map(function()
                {  
                    return $(this).val();
                    
                }).get();

            is_atacado = $('table tbody tr').find('td:eq(2)').html();
            console.log(is_atacado)
         
            for(var i = 0; i < forma_pagamento_fechamento.length; i++)
            {
                if (forma_pagamento_fechamento[i] == "DINHEIRO")
                {
                  
                    dinheiro_forma_pagamento = forma_pagamento_fechamento[i];

                    $('.forma_pagamento_fechamento_'+dinheiro_forma_pagamento).prop('disabled', true);
                    $('.conta_fechamento_total_'+dinheiro_forma_pagamento).prop('disabled', true);
                    $('.diferenca_fechamento_'+dinheiro_forma_pagamento).prop('disabled', true);

                    $('.conta_fechamento_total_'+dinheiro_forma_pagamento).mask("000.000.000.000.000,00", {reverse: true});
                    $('.forma_pagamento_numero_'+dinheiro_forma_pagamento).mask("000.000.000.000.000,00", {reverse: true});
                }
                if (forma_pagamento_fechamento[i] == "CHEQUE")
                {
                    cheque_forma_pagamento = forma_pagamento_fechamento[i];

                    $('.forma_pagamento_fechamento_'+cheque_forma_pagamento).prop('disabled', true);
                    $('.conta_fechamento_total_'+cheque_forma_pagamento).prop('disabled', true);
                    $('.diferenca_fechamento_'+cheque_forma_pagamento).prop('disabled', true);

                    $('.conta_fechamento_total_'+cheque_forma_pagamento).mask("000.000.000.000.000,00", {reverse: true});
                    $('.forma_pagamento_numero_'+cheque_forma_pagamento).mask("000.000.000.000.000,00", {reverse: true});
                }
                if (forma_pagamento_fechamento[i] == "cartao_de_credito")
                {   
                    cartao_credito_forma_pagamento = forma_pagamento_fechamento[i];
                    
                    $('.forma_pagamento_fechamento_'+cartao_credito_forma_pagamento).prop('disabled', true);
                    $('.conta_fechamento_total_'+cartao_credito_forma_pagamento).prop('disabled', true);
                    $('.diferenca_fechamento_'+cartao_credito_forma_pagamento).prop('disabled', true);

                    $('.conta_fechamento_total_'+cartao_credito_forma_pagamento).mask("000.000.000.000.000,00", {reverse: true});
                    $('.forma_pagamento_numero_'+cartao_credito_forma_pagamento).mask("000.000.000.000.000,00", {reverse: true});
                    
                    cartao_credito_forma_pagamento_val = cartao_credito_forma_pagamento.replaceAll("_", " ").toUpperCase();
                   
                    $('.forma_pagamento_fechamento_'+cartao_credito_forma_pagamento).val(cartao_credito_forma_pagamento_val)
                }
                if (forma_pagamento_fechamento[i] == "cartao_de_debito")
                {
            
                    cartao_debido_forma_pagamento = forma_pagamento_fechamento[i];
                    
                    $('.forma_pagamento_fechamento_'+cartao_debido_forma_pagamento).prop('disabled', true);
                    $('.conta_fechamento_total_'+cartao_debido_forma_pagamento).prop('disabled', true);
                    $('.diferenca_fechamento_'+cartao_debido_forma_pagamento).prop('disabled', true);

                    $('.conta_fechamento_total_'+cartao_debido_forma_pagamento).mask("000.000.000.000.000,00", {reverse: true});
                    $('.forma_pagamento_numero_'+cartao_debido_forma_pagamento).mask("000.000.000.000.000,00", {reverse: true});

                    cartao_credito_forma_pagamento_val = cartao_debido_forma_pagamento.replaceAll("_", " ").toUpperCase();
                   
                    $('.forma_pagamento_fechamento_'+cartao_debido_forma_pagamento).val(cartao_credito_forma_pagamento_val)
                }
                if (forma_pagamento_fechamento[i] == "PIX")
                {
                    pix_forma_pagamento = forma_pagamento_fechamento[i];

                    $('.forma_pagamento_fechamento_'+pix_forma_pagamento).prop('disabled', true);
                    $('.conta_fechamento_total_'+pix_forma_pagamento).prop('disabled', true);
                    $('.diferenca_fechamento_'+pix_forma_pagamento).prop('disabled', true);

                    $('.conta_fechamento_total_'+pix_forma_pagamento).mask("000.000.000.000.000,00", {reverse: true});
                    $('.forma_pagamento_numero_'+pix_forma_pagamento).mask("000.000.000.000.000,00", {reverse: true});
                }
            }
            
            $(".forma_pagamento_numero_"+dinheiro_forma_pagamento).blur(function(){
                
                var conta_fechamento_total     = $('.conta_fechamento_total_'+dinheiro_forma_pagamento).val().replace('.','').replace(',','.');
                var forma_pagamento_numero     = $('.forma_pagamento_numero_'+dinheiro_forma_pagamento).val().replace('.','').replace(',','.');
                var total_forma_pagamento      = conta_fechamento_total - forma_pagamento_numero;
            
                var valorFormatado = Intl.NumberFormat('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 3,  currency: 'BRL'}).format(total_forma_pagamento)
               
                if (total_forma_pagamento > 0 && forma_pagamento_numero != "")
                {
                    var result_total_forma_pagamento = "-"+valorFormatado;
                  
                    $('.diferenca_fechamento_'+dinheiro_forma_pagamento).map(function(){
                        $(this).val(result_total_forma_pagamento).css({'color' : 'red'});
                    });

                } else if(total_forma_pagamento < 0 && forma_pagamento_numero != "")
                {
                    var result_total_forma_pagamento = "+"+valorFormatado.replace('-', '');
                  
                    $('.diferenca_fechamento_'+dinheiro_forma_pagamento).map(function(){
                        $(this).val(result_total_forma_pagamento).css({'color' : 'green'});
                    });
                } else if(total_forma_pagamento == 0)
                {
                    $('.diferenca_fechamento_'+dinheiro_forma_pagamento).map(function(){
                        $(this).val("0,00").css({'color' : '#FFF'});
                    });
                } else {

                    $('.diferenca_fechamento_'+dinheiro_forma_pagamento).map(function(){
                        $(this).val("");
                    });
                }
                        
            });

            $(".forma_pagamento_numero_"+cartao_credito_forma_pagamento).blur(function(){

                var conta_fechamento_total     = $('.conta_fechamento_total_'+cartao_credito_forma_pagamento).val().replace('.','').replace(',','.');
                var forma_pagamento_numero     = $('.forma_pagamento_numero_'+cartao_credito_forma_pagamento).val().replace('.','').replace(',','.');
                var total_forma_pagamento      = conta_fechamento_total - forma_pagamento_numero;

                var valorFormatado = Intl.NumberFormat('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 3,  currency: 'BRL'}).format(total_forma_pagamento)

                if (total_forma_pagamento > 0 && forma_pagamento_numero != "")
                {
                    var result_total_forma_pagamento = "-"+valorFormatado;
                  
                    $('.diferenca_fechamento_'+cartao_credito_forma_pagamento).map(function(){
                        $(this).val(result_total_forma_pagamento).css({'color' : 'red'});
                    });

                } else if(total_forma_pagamento < 0 && forma_pagamento_numero != "")
                {
                    var result_total_forma_pagamento = "+"+valorFormatado.replace('-', '');
                  
                    $('.diferenca_fechamento_'+cartao_credito_forma_pagamento).map(function(){
                        $(this).val(result_total_forma_pagamento).css({'color' : 'green'});
                    });
                } else if(total_forma_pagamento == 0)
                {
                    $('.diferenca_fechamento_'+cartao_credito_forma_pagamento).map(function(){
                        $(this).val("0,00").css({'color' : '#FFF'});
                    });
                }  else {
                    
                    $('.diferenca_fechamento_'+cartao_credito_forma_pagamento).map(function(){
                        $(this).val("");
                    });
                }
                        
            })

            $(".forma_pagamento_numero_"+cartao_debido_forma_pagamento).blur(function(){
                var conta_fechamento_total     = $('.conta_fechamento_total_'+cartao_debido_forma_pagamento).val().replace('.','').replace(',','.');
                var forma_pagamento_numero     = $('.forma_pagamento_numero_'+cartao_debido_forma_pagamento).val().replace('.','').replace(',','.');
                var total_forma_pagamento      = conta_fechamento_total - forma_pagamento_numero;
               
                var valorFormatado = Intl.NumberFormat('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 3,  currency: 'BRL'}).format(total_forma_pagamento)

                if (total_forma_pagamento > 0 && forma_pagamento_numero != "")
                {
                    var result_total_forma_pagamento = "-"+valorFormatado;
                  
                    $('.diferenca_fechamento_'+cartao_debido_forma_pagamento).map(function(){
                        $(this).val(result_total_forma_pagamento).css({'color' : 'red'});
                    });

                } else if(total_forma_pagamento < 0 && forma_pagamento_numero != "")
                {
             
                    var result_total_forma_pagamento = "+"+valorFormatado.replace('-', '');
                  
                    $('.diferenca_fechamento_'+cartao_debido_forma_pagamento).map(function(){
                        $(this).val(result_total_forma_pagamento).css({'color' : 'green'});
                    });
                } else if(total_forma_pagamento == 0)
                {
                    $('.diferenca_fechamento_'+cartao_debido_forma_pagamento).map(function(){
                        $(this).val("0,00").css({'color' : '#FFF'});
                    });
                }  else {
                    
                    $('.diferenca_fechamento_'+cartao_debido_forma_pagamento).map(function(){
                        $(this).val("");
                    });
                }
                        
            });

            $(".forma_pagamento_numero_"+cheque_forma_pagamento).blur(function(){
                var conta_fechamento_total     = $('.conta_fechamento_total_'+cheque_forma_pagamento).val().replace('.','').replace(',','.');
                var forma_pagamento_numero     = $('.forma_pagamento_numero_'+cheque_forma_pagamento).val().replace('.','').replace(',','.');
                var total_forma_pagamento      = conta_fechamento_total - forma_pagamento_numero;

                var valorFormatado = Intl.NumberFormat('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 3,  currency: 'BRL'}).format(total_forma_pagamento)
                
                if (total_forma_pagamento > 0 && forma_pagamento_numero != "")
                {
                    var result_total_forma_pagamento = "-"+valorFormatado;
                  
                    $('.diferenca_fechamento_'+cheque_forma_pagamento).map(function(){
                        $(this).val(result_total_forma_pagamento).css({'color' : 'red'});
                    });

                } else if(total_forma_pagamento < 0 && forma_pagamento_numero != "")
                {             
                    var result_total_forma_pagamento = "+"+valorFormatado.replace('-', '');
                  
                    $('.diferenca_fechamento_'+cheque_forma_pagamento).map(function(){
                        $(this).val(result_total_forma_pagamento).css({'color' : 'green'});
                    });
                } else if(total_forma_pagamento == 0)
                {
                    $('.diferenca_fechamento_'+cheque_forma_pagamento).map(function(){
                        $(this).val("0,00").css({'color' : '#FFF'});
                    });
                }  else {
                    
                    $('.diferenca_fechamento_'+cheque_forma_pagamento).map(function(){
                        $(this).val("");
                    });
                }
                        
            });

            $(".forma_pagamento_numero_"+pix_forma_pagamento).blur(function(){
                var conta_fechamento_total     = $('.conta_fechamento_total_'+pix_forma_pagamento).val().replace('.','').replace(',','.');
                var forma_pagamento_numero     = $('.forma_pagamento_numero_'+pix_forma_pagamento).val().replace('.','').replace(',','.');
                var total_forma_pagamento      = conta_fechamento_total - forma_pagamento_numero;

                var valorFormatado = Intl.NumberFormat('pt-br', {minimumFractionDigits: 2, maximumFractionDigits: 3,  currency: 'BRL'}).format(total_forma_pagamento)
                
                if (total_forma_pagamento > 0 && forma_pagamento_numero != "")
                {             
                    var result_total_forma_pagamento = "-"+valorFormatado;
                  
                    $('.diferenca_fechamento_'+pix_forma_pagamento).map(function(){
                       $(this).val(result_total_forma_pagamento).css({'color' : 'red'});
                    });

                } else if(total_forma_pagamento < 0 && forma_pagamento_numero != "")
                {
         
                    var result_total_forma_pagamento = "+"+valorFormatado.replace('-', '');
                  
                    $('.diferenca_fechamento_'+pix_forma_pagamento).map(function(){
                       $(this).val(result_total_forma_pagamento).css({'color' : 'green'});
                    });
                } else if(total_forma_pagamento == 0)
                {
                    $('.diferenca_fechamento_'+pix_forma_pagamento).map(function(){
                        $(this).val("0,00").css({'color' : '#FFF'});
                    });
                } else {
                    
                    $('.diferenca_fechamento_'+pix_forma_pagamento).map(function(){
                        $(this).val("");
                    });
                }           
            });
        });
    
    function isAtacado()
    {
        $.ajax({
            url: '/isatacado/'+caixa_id,
            method:'GET',
            success:function(data)
            {
                if (data)
                {
                    window.location.href = data;
                }
                else {
                    window.location.href = '/login'
                }
            }
        });       
    }

    function fechamentoCaixa(caixa_id)
    {
        var forma_pagamento_numero = $('input[name="valor_informado_fechamento[]"]').map(
            function()
            {
                return $(this).attr('id');
                
            }).get();
        
        var forma_pagamento_fechamento = $("input[name='forma_pagamento_fechamento[]']").map(
            function()
            {  
                return $(this).val();
                
            }).get();
            
        var total_fechamento = $("input[name='total_fechamento[]']").map(
            function()
            {  
                return $(this).val();

            }).get();
            
        var valor_informado_fechamento = $("input[name='valor_informado_fechamento[]']").map(
            function()
            {  
                return $(this).val();

            }).get();

        var diferenca_fechamento = $("input[name='diferenca_fechamento[]']").map(
            function()
            {  
                return $(this).val();

            }).get();

        var data = {
            caixa_id: caixa_id,
            forma_pagamento_numero: forma_pagamento_numero,
            forma_pagamento_fechamento: forma_pagamento_fechamento,
            total_fechamento: total_fechamento,
            valor_informado_fechamento: valor_informado_fechamento,
            diferenca_fechamento: diferenca_fechamento
        }
        
        $.ajax({
            url: '/fechamento_caixa',
            method:'POST',
            data: data,
            beforeSend: () =>{
                $("#preloader_full").css({'display' : 'block'});
            }, 
            success: function(data){
                $(".errors").html("");
                if($.isEmptyObject(data.error)){
                    imprimirCupomFechamento(caixa_id);
                    setTimeout(() => {
                        caixaLogout(caixa_id);
                    }, 1000);
                    $("#preloader_full").css({'display' : 'none'});
                }else{
                    $.each(data.error, function(index, value) {
                        $(".errors").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
                        $("#preloader_full").css({'display' : 'none'});s
                    });
                }
            }
        }); 
    }

    function imprimirCupomFechamento(caixa_id) {
        var popupWindow = window.open('/imprimir_cupom_fechamento/'+caixa_id, '_blank', "width=300, height=600");
        popupWindow.focus();
        popupWindow.print(); 
    }

    function caixaLogout(caixa_id) {
        $.ajax({
            url: '/caixa_logout/'+caixa_id,
            method:'GET',
            success:function(data)
            {
                if (data)
                {
                    window.location.href = data;
                }
                else {
                    window.location.href = '/login'
                }
            }
        });         
    }
    </script>
</body>
</html>
