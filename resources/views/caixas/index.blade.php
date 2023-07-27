@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <div style="display:flex; justify-content:space-between;">
        <h5 class="m-0 text-dark"><i class="fas fa-play-circle"></i> Caixas</h5>
        <ol class="breadcrumb float-sm-right" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Caixas</li>
        </ol>
    </div>
@stop
@section('content')
    <div class="card card-success">
        <div class="card-header" style="background-color: #2b5a7a;">
            <span class="card-title"></span>
            <div class="card-tools">
                <button style="border:none"><i class="fas fa-cog"></i> Gerenciar caixas</button>
            </div>
        </div>
        <div class="card-body" style="display:flex; gap: 0 20px; flex-wrap:wrap">
        </div>
    </div>
    <div>
        @include('modals.caixa.abertura_caixa')
        @include('modals.caixa.caixa_aberto')
    </div>
@stop
@push('scripts')
<script>
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.valor_abertura_caixa').mask("000.000.000.000.000,00", {reverse: true});

        $(document).on('click', '.abrir_caixa_link', function(){
            $('#abertura_modal').modal('show');

            $caixa_escolhido = $(this).children().children().children().html();
            
            $('.numero_caixa').val($caixa_escolhido)
            
        });

        $(document).on('click', '.caixa_aberto_link', function(){
            $('#caixa_aberto_modal').modal('show');

            var id_user_caixa_aberto = $('.id_user_caixa_aberto').text();
           
            $.ajax({
                url:'/get_caixa_aberto/'+id_user_caixa_aberto,
                method: 'GET',
                dataType: 'json',
                success:function(data)
                {     
                    $('.aberto_caixa_dados div .usuario_abertura').text(data.usuario_nome);
                    $('.aberto_caixa_dados div .data_abertura').text(data.data_caixa_aberto);
                    $('.aberto_caixa_dados div .horario_abertura').text(data.horario_abertura);
                    $('.aberto_caixa_dados div .valor_abertura').text(data.valor_abertura);
                    $('.aberto_caixa_dados div .total_caixa').text(data.total_caixa);
            
                }
            });  
        });

        $(document).on('click', '.abre_caixa', function(){
            var data = {
                        "numero_caixa"         : $('.numero_caixa').val(),
                        "valor_abertura_caixa" : $('.valor_abertura_caixa').val()
                       };
            $.ajax({
                type: 'POST',
                url: '/abertura_caixa',
                data: data, 
                success: function(data)
                {
                    if($.isEmptyObject(data.error)){
                        swal({
                            text: data.message,
                            icon: "success"
                        }).then(() =>{
                            location.reload(true);
                        });
                    }else{
                        $.each(data.error, function( index, value) {
                            $(".errors_abertura_caixa").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div><hr>');
                        });
                    }  
                }
            });
        });

        $('.close').click(function(){
            $(".errors_abertura_caixa").html("");
        });

        caixaDisponivel();
    });

    function caixaDisponivel()
    {   
        $.ajax({
            url:"{{ route('get_caixas.caixa') }}",
            method: 'GET',
            dataType: 'json',
            success:function(data)
            {   
                $('.card-title').html('Caixas disponíveis: ' + data.total_caixa_disponivel)
                $('.card-body').html(data.output);   
            }
        });
    }

    function descricaoAberturaCaixa(descricao)
    {
        var descricao_caixa = $(descricao).attr('id');

        $('.abertura_caixa_dados h5').html("<i class='fas fa-cash-register'></i> " + descricao_caixa)
       
    }

    function descricaoCaixaAberto(descricao, id)
    {
        var descricao_caixa = $(descricao).attr('id');

        $('.id_user_caixa_aberto').text(id);
        $('#caixa_aberto_title b').html("<i class='fas fa-cash-register'></i> " + descricao_caixa)
    }
</script>

@endpush