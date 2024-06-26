<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="text-align:center">
        <span style="font-size:17px;">{{ucfirst($emitente->nome_fantasia)}}</span>
        <div style="font-size:11px;">
            @if (strlen($emitente->cnpj) > 11)
                <span><b>CNPJ:</b> {{$emitente->cnpj}}</span>
            @else
                <span><b>CPF:</b> {{$emitente->cnpj}}</span>
            @endif
            @if ($emitente->ie)
                <span><b>IE:</b> {{$emitente->ie}}</span>
            @endif
        </div>
        @if ($emitente->rua && $emitente->numero && $emitente->bairro && $emitente->cidade)
            <span style="font-size:11px;">{{$emitente->rua}}, {{$emitente->numero}}, {{$emitente->bairro}}, {{$emitente->cidade}}-{{$emitente->uf}}</span>
        @endif
        @if ($emitente->cep)
            <span style="font-size:11px;">CEP: {{$emitente->cep}}</span><br>
        @endif
        <span style="font-size:11px;">Cupom: {{$cupom[0]->nro_cupom}}</span><br>
    </div>
    <span style="font-size:11px; font-weight:bold;">------------------------------------------------------</span>
    <div>
        <table style="font-size:11px; text-align:center; width:100%">
            <thead style="font-size:9px;">
                <tr>
                    <th>DESCRIÇÃO</th>
                    <th>Qtd.</th>
                    <th>VL. UNIT</th>
                    <th>VL. TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($itens as $data)
                    <tr>
                        <td>{{ucfirst($data->nome)}}</td>
                        <td>{{$data->qtd}}</td>
                        <td>{{number_format($data->preco_venda, 2, ',','.')}}</td>
                        <td>{{number_format($data->sub_total, 2, ',','.')}}</td>
                    </tr>    
                @endforeach
            </tbody>
        </table>
        <span style="font-size:11px; font-weight:bold;">------------------------------------------------------</span>
    </div>
    <div>
        <span style="font-size:9px;">Total itens: <b>{{$qtd_itens}}</b></span><br>
        <span style="font-size:9px;">Total venda (R$): <b>{{number_format($total, 2, ',','.')}}</b></span><br>
        <span style="font-size:9px;">A pagar (R$): <b>{{number_format($cupom[0]->total_venda, 2, ',','.')}}</b></span><br>
        <span style="font-size:9px;">Desconto (R$): <b>{{number_format($cupom[0]->desconto, 2, ',','.')}}</b></span><br>
        <span style="font-size:9px;">Valor pago (R$): <b>{{number_format($cupom[0]->valor_recebido, 2, ',','.')}}</b></span><br>
        <span style="font-size:9px;">Troco (R$): <b>{{number_format($cupom[0]->troco, 2, ',','.')}}</b></span>
        <span style="font-size:11px; font-weight:bold;">------------------------------------------------------</span>
    </div>
    <div style="text-align: center;">
        <span style="font-size:9px;">{{$descricao_caixa->descricao_caixa}}</span><br>
        <span style="font-size:9px;">Operador: {{ucfirst($cupom[0]->name)}}</span>
        <span style="font-size:11px; font-weight:bold;">------------------------------------------------------</span>
    </div>
    <div style="text-align: center;">
        <span style="font-size:11px;">A {{ucfirst($emitente->nome_fantasia)}} agradece sua presença.</span>
        <span style="font-size:11px;">Volte sempre!</span><br>
        <span style="font-size:11px;">{{date('d/m/Y H:i:s', strtotime($cupom[0]->created_at))}}</span>
    </div>
</body>

</html>