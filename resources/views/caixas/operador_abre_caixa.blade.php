<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Abrir Caixa - SapphireRP</title>
    <style>
        body{
            margin:0;
        }
    </style>
</head>
<body>
    <div style="display: flex;">
        <img src="{{asset('img/background_abertura_caixa_op.jpg')}}" style=" width: 100%; height: 100%; z-index:-1; object-fit: cover; position:absolute;">
        <div style="display: flex; flex-direction:column; justify-content: center; flex-wrap: wrap; align-items: center; width: 50%; position: relative; background: rgba(0,0,0,0.3); margin: 30px auto;">
            <span>Abertura do Caixa</span>
            <div>
                <img src="{{asset('img/balcao_caixa.png')}}" alt="" >
            </div>
            <div style="display: flex; flex-direction:column">
                <label for="user_name_op">Operador:</label>
                <input type="text" value="{{Auth::user()->name}}" id="user_name_op">
                <label for="numero_caixa">CAIXA:</label>
                <select id="numero_caixa">
                    <option value="CAIXA 01">CAIXA 01</option>
                    <option value="CAIXA 02">CAIXA 02</option>
                </select>
                <label for="valor_fundo">Valor do fundo:</label>
                    <input type="text" class="valor_abertura_caixa" />
            </div>
        </div>
    </div>
</body>
</html>