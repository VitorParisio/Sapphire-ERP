<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="text-align:center">
        <span style="font-size:15px;">{{$emitente->nome_fantasia}}</span>
        <div style="font-size:11px;">
            <span><b>CNPJ:</b> {{$emitente->cnpj}}</span>
            <span><b>IE:</b> {{$emitente->ie}}</span>
        </div>
       <span style="font-size:11px;">{{$emitente->rua}}, {{$emitente->numero}}, {{$emitente->bairro}}, {{$emitente->cidade}}-{{$emitente->uf}}</span>
       <span style="font-size:11px;">CEP: {{$emitente->cep}}</span><br>
       <span style="font-size:11px;">------------------------------------------------------</span>
    </div>
    <div>
        <table style="font-size:11px; text-align:center;">
            <thead style="font-size:9px;">
                <td>DESCRIÇÃO</td>
                <td>QTD</td>
                <td>VL. UNIT</td>
                <td>VL. TOTAL</td>
            </thead>
                <tbody>
                    @foreach($itens as $data)
                        <tr>
                            <td>{{$data->nome}}</td>
                            <td>{{$data->qtd}}</td>
                            <td>{{number_format($data->preco_venda, 2, ',','.')}}</td>
                            <td>{{number_format($data->sub_total, 2, ',','.')}}</td>
                        </tr>    
                    @endforeach
            </tbody>
        </table>
        <span style="font-size:11px;">------------------------------------------------------</span>
    </div>
    <div>
        <span style="font-size:9px;">Total itens: <b>{{$qtd_itens}}</b></span><br>
        <span style="font-size:9px;">Total venda (R$): <b>{{number_format($total, 2, ',','.')}}</b></span><br>
        <span style="font-size:9px;">A pagar (R$): <b>{{number_format($cupom[0]->total_venda, 2, ',','.')}}</b></span><br>
        <span style="font-size:9px;">Desconto (R$): <b>{{number_format($cupom[0]->desconto, 2, ',','.')}}</b></span><br>
        <span style="font-size:9px;">Valor pago (R$): <b>{{number_format($cupom[0]->valor_recebido, 2, ',','.')}}</b></span><br>
        <span style="font-size:9px;">Troco (R$): <b>{{number_format($cupom[0]->troco, 2, ',','.')}}</b></span>
        <span style="font-size:11px;">------------------------------------------------------</span>
    </div>
    <div>
        <span style="font-size:9px;">{{$descricao_caixa->descricao_caixa}}</span><br>
        <span style="font-size:9px;">Operador: {{$cupom[0]->name}}</span>
        <span style="font-size:11px;">------------------------------------------------------</span>
    </div>
    <div style="text-align: center;">
        <span style="font-size:11px;">A {{$emitente->nome_fantasia}} agradece sua presença.</span>
        <span style="font-size:11px;">Volte sempre!</span><br>
        <span style="font-size:11px;">{{date('d/m/Y H:i:s', strtotime($cupom[0]->created_at))}}</span>
    </div>
</body>

</html>