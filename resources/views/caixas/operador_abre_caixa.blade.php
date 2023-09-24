<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Abrir Caixa - SapphireRP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <style>
        body{
            margin:0;
        }
        #preloader_full{
            display: none;
            position: absolute;
            height:100vh;
            width: 100%;
            overflow: none;
            z-index: 1000;
            background-color: rgba(0,0,0,.6);
            text-align: center;
        }
        #preloader_full > img{
            margin: 290px auto;
            position: relative;
            height: 100px;
        }

    </style>
</head>
<body>
    <div id="preloader_full"><img src="{{asset('img/preloader.gif')}}"></div>
    <div style="display: flex; flex-direction:column;">
        <img src="{{asset('img/background_abertura_caixa_op.jpg')}}" style=" width: 100%; height: 100%; z-index:-1; object-fit: cover; position:absolute;">
        <center><h1 style="color:#FFF; ">Abertura do Caixa</h1></center>
        <div style="display: flex; justify-content: space-between; padding: 30px 0; flex-wrap: wrap; text-align: center; align-items: center; width: 50%; position: relative; background: rgba(0,0,0,0.3); margin: 0px auto;">
            <div class="errors_abertura_caixa"></div>
            <div style="position: absolute; left: 0; margin-left: 50px;">
                <img src="{{asset('img/balcao_caixa.png')}}" alt="" style="width:269px">
            </div>
            <div style="display: flex; flex-direction:column; margin-right:50px;">
                <label for="user_name_op" style="font-size: 22px; color:#fff;">Operador:</label>
                <input type="text" value="{{Auth::user()->name}}" id="user_name_op" style="height:30px; font-size: 20px; border:none; outline:none;  color: #FFF; text-align:center; height: 40px;"><br>
                <label for="numero_caixa" style="font-size: 22px; color:#fff;">Caixa:</label>
                <select id="numero_caixa" style="height:30px; font-size: 20px; border:none; outline:none; text-align:center; height: 40px;">
                    @foreach($caixas as $caixa)
                        <option value="{{$caixa->descricao}}">{{$caixa->descricao}}</option>
                    @endforeach
                </select>
                <br>
                <label for="valor_fundo" style="font-size: 22px; color:#fff">Valor do fundo(R$):</label>
                <input type="text" id="valor_fundo" class="valor_abertura_caixa" style="height:30px; font-size: 20px; border:none; outline:none; text-align:center; height: 40px;"/>
            </div>
        </div>
        <div style="height: auto; width: 300px; font-size: 30px; background: teal; color: #FFF; font-weight: bold; border: none; padding: 10px; margin: 10px auto; text-align:center; display:flex; flex-direction:column">
            <a href="javascript:void(0)" class="abre_caixa" style="color:#FFF; text-decoration:none">ABRIR CAIXA</a>
            <a href="javascript:void(0)" class="op_logout">Sair</a>
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
            
            $('#user_name_op').prop( "disabled", true );
            $('.valor_abertura_caixa').mask("000.000.000.000.000,00", {reverse: true});

            $(document).on('click', '.abre_caixa', function(){
                
                var data = {
                        "numero_caixa"         : $('#numero_caixa').val(),
                        "valor_abertura_caixa" : $('.valor_abertura_caixa').val()
                    };
                $.ajax({
                    type: 'POST',
                    url: '/abertura_caixa_op',
                    data: data, 
                    success: function(data)
                    {
                        window.location.href = "/pdv";
                    }
                });
            });

            $(document).on('click','.op_logout', function(){
                $.ajax({
                    method:'GET',
                    url:'/op_logout',
                    beforeSend: ()=>{
                        $('#preloader_full').css({'display' : 'block'});
                    },
                    success:()=>{
                        window.location.href="/";
                        $('#preloader_full').css({'display' : 'none'});
                    }
                })
            });
        })
    </script>
</body>
</html>
