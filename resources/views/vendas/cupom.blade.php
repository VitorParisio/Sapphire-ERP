<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <h5>{{$emitente->nome_fantasia}}</h5>
        <p>CNPJ: {{$emitente->cnpj}}</p>
    </div>
   
    @foreach($cupom as $data)
    
        <div>{{$data->descricao}}</div>
    @endforeach
    @foreach($itens as $iten)
    
        <div>{{$iten->sub_total}}</div>
    @endforeach
</body>
</html>