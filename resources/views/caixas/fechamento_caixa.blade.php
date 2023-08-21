<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fechamento Caixa - SapphireRP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <style>
        body{
            margin:0;
        }
        .fechamento_caixa .header_fechamento_caixa {
            text-align: center;
            background-color: teal;
            padding: 15px;
        }
        
        .fechamento_caixa .header_fechamento_caixa span{
           color:#FFF;
           font-weight: bold;
           font-size: 25px;
        }
    </style>
</head>
<body>
    <div class="fechamento_caixa" style="margin: 0 auto; width:1000px; border:1px solid">
        <div class="header_fechamento_caixa">
            <span><i class="fas fa-cash-register"></i>&nbsp{{$caixa_info->descricao}} - FECHAMENTO</span>
        </div>
        <div style="display: flex;">
            <div>
                <ul>
                    <li>Faturamento: R$ {{number_format($caixa_info->valor_vendido, 2,',','.')}}</li>
                    <li>Valor inicial: R$ {{number_format($caixa_info->valor_abertura, 2,',','.')}}</li>
                    <li>Retirada(sangria): R$ {{number_format($caixa_info->sangria, 2,',','.')}}</li>
                    <li>Suplemento: R$ {{number_format($caixa_info->suplemento, 2,',','.')}}</li>
                    <li>Total caixa: R$ {{number_format($caixa_info->total_caixa, 2,',','.')}}</li>
                </ul>
            </div>
            <div>
                <ul>
                    @foreach($data as $forma_pagamento_fechamento)
                        <li><input type="text" class="forma_pagamento_fechamento_{{$forma_pagamento_fechamento}}" value="{{$forma_pagamento_fechamento}}"></li>
                    @endforeach
                </ul>
                <ul>
                    @foreach($conta_fechamentos as $conta_fechamento_total)
                        <li><input type="text" class="conta_fechamento_total" value="{{$conta_fechamento_total->total_venda_fechamento}}"></li>
                    @endforeach
                </ul>
                <ul>
                    @for($i = 0; $i < count($conta_fechamentos); $i++)
                        <li><input type="text" class="forma_pagamento_numero" id="{{$conta_fechamentos[$i]->forma_pagamento}}"></li>
                    @endfor
                </ul>
            </div> 
        </div>
        <div>
            <button onclick="fechamentoCaixa({{$caixa_info->venda_cupom_id}})">FECHAR {{$caixa_info->descricao}}</button>
        </div>
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
            fechamentoCaixa()
        });

        function fechamentoCaixa(venda_id)
        {
            var forma_pagamento_descricao = $('.forma_pagamento_fechamento').val();
            var conta_fechamento_total    = $('.conta_fechamento_total').val();
            var forma_pagamento_numero    = $(".forma_pagamento_numero").val($(this).attr("id"));

            console.log(forma_pagamento_descricao)
            console.log(conta_fechamento_total)
            console.log(forma_pagamento_numero)
        }
    </script>
</body>
</html>
