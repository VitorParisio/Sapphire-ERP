<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Abrir Caixa - SapphireRP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <style>
        body{
            margin:0;
        }
    </style>
</head>
<body>
    <div style="display: flex; flex-direction:column;">
        <img src="{{asset('img/background_abertura_caixa_op.jpg')}}" style=" width: 100%; height: 100%; z-index:-1; object-fit: cover; position:absolute;">
        <div style="display: flex; flex-direction:column; justify-content: center; padding: 30px 0; flex-wrap: wrap; align-items: center; width: 50%; position: relative; background: rgba(0,0,0,0.3); margin: 0px auto;">
            
            <div>
                <img src="{{asset('img/balcao_caixa.png')}}" alt="" style="width:300px">
            </div>
            <div style="display: flex; flex-direction:column">
                <label for="user_name_op" style="font-size: 22px; color:#fff;">Operador:</label>
                <input type="text" value="{{Auth::user()->name}}" id="user_name_op" style="height:30px; border:none; outline:none; text-align:center;"><br>
                <label for="numero_caixa" style="font-size: 22px; color:#fff;">Caixa:</label>
                <select id="numero_caixa" style="height:30px; border:none; outline:none; text-align:center;">
                    <option value="CAIXA 01">CAIXA 01</option>
                    <option value="CAIXA 02">CAIXA 02</option>
                </select>
                <br>
                <label for="valor_fundo" style="font-size: 22px; color:#fff">Valor do fundo(R$):</label>
                <input type="text" class="valor_abertura_caixa" style="height:30px; border:none; outline:none; text-align:center;"/>
            </div>
        </div>
        <div style="height: auto; width: 300px; font-size: 30px; background: teal; color: #FFF; font-weight: bold; border: none; padding: 10px; margin: 10px auto; text-align:center;">
            <a href="javascript:void(0)" style="color:#FFF; text-decoration:none">ABRIR CAIXA</a>
         </div>
    </div>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(function(){
            $('#user_name_op').prop( "disabled", true );
            $('.valor_abertura_caixa').mask("000.000.000.000.000,00", {reverse: true});
        })
    </script>
</body>
</html>
