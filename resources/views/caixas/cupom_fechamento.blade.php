<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .titulo_cupom_fechamento{
            text-align: center;
            margin-bottom: 10px;
            font-size: 20px;
        }
        .linhas{
            width: 100%;
        }
        table{
            width:100%;
            margin-bottom: 10px;
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="titulo_cupom_fechamento">
        <span>{{$info_caixa->descricao}}</span>
    </div>
    <hr>
    <div style="padding:10px 0;">
        <span><small>Data abertura: <b>{{date("d/m/Y", strtotime($info_caixa->data_abertura))}}</b></small></span><br>
        <span><small>Hora abertura: <b>{{date("H:i:s", strtotime($info_caixa->horario_abertura))}}</b></small></span>
        <span></span>
    </div>
    <hr>
    <div style="padding:10px 0;">
        <span><small>Abertura: <b>R$ {{number_format($info_caixa->valor_abertura,'2',',','.')}}</b></small></span><br>
        <span><small>Suprimento: <b>R$ {{number_format($info_caixa->suplemento,'2',',','.')}}</b></small></span><br>
        <span><small>Sangria: <b>R$ {{number_format($info_caixa->sangria,'2',',','.')}}</b></small></span><br>
    </div>
    <hr>
    <div>
        <table>
            <thead>
                <tr>
                    <th>FORMA</th>
                    <th>PREVISTOS</th>
                    <th>REALIZDOS</th>
                    <th>DIFERENÇAS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagamentos as $pagamento)
                    <tr>
                        <td>{{$pagamento->forma}}</td>
                        <td>R$ {{number_format($pagamento->total_venda_fechamento, '2', ',', '.')}}</td>
                        <td>R$ {{number_format($pagamento->caixa_informado, '2', ',', '.')}}</td>
                        <td>R$ {{$pagamento->diferenca_pagamento}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div style="text-align:right">
        <span><small><b>R$ {{number_format($info_caixa->valor_vendido,'2',',','.')}}</b></small></span>
    </div>
    <hr>
    <div style="padding:10px 0;">
        <span><small>Total Caixa:<b> R$ {{number_format($info_caixa->total_caixa,'2',',','.')}}</b> (previsto)</small></span><br>
        <div style="width:100%; text-align:center; margin-top:30px; border:1px solid black">
            <span><b>FECHAMENTO GERAL</b></span>
            <span><h4>R$ {{number_format($fechamento,'2',',','.')}}</h4></span>
        </div>
    </div>
    <div style="text-align: center;">
        <span><b>Fechamento</b> {{date("d/m/Y", strtotime($info_caixa->data_fechamento))}} às {{date("H:i:s", strtotime($info_caixa->horario_fechamento))}}</span>
    </div>
</body>
</html>