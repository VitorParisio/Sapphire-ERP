@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <div style="display:flex; justify-content:space-between;">
        <h5 class="m-0 text-dark"><i class="fas fa-play-circle"></i> Caixas</h5>
        <ol class="breadcrumb float-sm-right" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Caixas</li>
        </ol>
    </div>
@stop
@section('content')
    <div class="errors_aberto_caixa"></div>
    <div class="card card-success">
        <div class="card-header" style="background-color: #2b5a7a;">
            <span class="card-title"></span>
            {{-- <div class="card-tools">
                <button style="border:none"><i class="fas fa-cog"></i> Gerenciar caixas</button>
            </div> --}}
        </div>
        <div class="card-body" style="display:flex; gap: 0 20px; flex-wrap:wrap">
        </div>
    </div>
    <div>
        @include('modals.caixa.abertura_caixa')
        @include('modals.caixa.caixa_aberto')
        @include('modals.caixa.sangria')
        @include('modals.caixa.suplemento')
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
        $('.valor_suprimento').mask("000.000.000.000.000,00", {reverse: true});
        $('.valor_sangria').mask("000.000.000.000.000,00", {reverse: true});

        $(document).on('click', '.abrir_caixa_link', function(){
            $('#abertura_modal').modal('show');

            $caixa_escolhido = $(this).children().children().children().html();
            
            $('.numero_caixa').val($caixa_escolhido);
            
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
                    if (data.cx_aberto)
                    {
                        $('#abertura_modal').modal('hide');
                        $(".errors_aberto_caixa").html(data.cx_aberto).css({'background-color' : 'red', 'padding' : '10px', 'color' : '#FFF', 'font-weight' : 'bold', 'margin-bottom' : '5px'});
                        
                        return false;
                    }
                     
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
                    $('.aberto_caixa_dados div .suprimento').text(data.suplemento);
                    $('.aberto_caixa_dados div .sangria').text(data.sangria);
                    $('.aberto_caixa_dados div .valor_abertura').text(data.valor_abertura);
                    $('.aberto_caixa_dados div .total_caixa').text(data.total_caixa);
                }
            });  
        });

        $(document).on('click', '.suprimento_master', function(){
            $('#caixa_suprimento_modal').modal('show');
            $('#caixa_aberto_modal').modal('hide');

            var descrica_caixa =  $('.numero_caixa_suprimento').val();
        
            $.ajax({
                url:'/suprimento_valores/'+descrica_caixa,
                method: 'GET',
                dataType: 'json',
                success:function(data)
                {     
                    $('.saldo_atual_caixa').val(data);
                }
            });  
        });

        $(document).on('click', '.sangria_master', function(){
            $('#caixa_sangria_modal').modal('show');
            $('#caixa_aberto_modal').modal('hide');

            var descrica_caixa =  $('.numero_caixa_sangria').val();
        
            $.ajax({
                url:'/sangria_valores/'+descrica_caixa,
                method: 'GET',
                dataType: 'json',
                success:function(data)
                {     
                    $('.saldo_atual_caixa').val(data);
                }
            });  
        });

        $(document).on('click', '.btn_suprimento', function(){
            var numero_caixa_suprimento =  $('.numero_caixa_suprimento').val();
            var valor_suprimento        =  $('.valor_suprimento').val();
            var saldo_apos_suprimento   =  $('.saldo_apos_suprimento').val();

            $.ajax({
                url:'/suprimento',
                method: 'POST',
            
                data: {numero_caixa_suprimento : numero_caixa_suprimento, valor_suprimento : valor_suprimento, saldo_apos_suprimento : saldo_apos_suprimento},
                beforeSend: ()=>{
                    $('#preloader_suprimento').css({'display' : 'block'});
                },
                success:function(data)
                {     
                    $('#preloader_suprimento').css({'display' : 'none'});
                    if($.isEmptyObject(data.error)){
                        swal({
                            text: data.message,
                            icon: "success"
                        }).then(() =>{
                            location.reload(true);
                        });
                    }else{
                        $.each(data.error, function(index, value) {
                            $(".erro_valores_suprimento").html(value);
                            $('.saldo_apos_suprimento').val("");
                        });
                            setTimeout(() => {
                                $(".erro_valores_suprimento").html("");
                            }, 3000);
                    }  
                }
            });  
        });

        $(document).on('click', '.btn_sangria', function(){
            var numero_caixa_sangria =  $('.numero_caixa_sangria').val();
            var valor_sangria        =  $('.valor_sangria').val();
            var saldo_apos_sangria   =  $('.saldo_apos_sangria').val();

            $.ajax({
                url:'/retirada_caixa',
                method: 'POST',
            
                data: {numero_caixa_sangria : numero_caixa_sangria, valor_sangria : valor_sangria, saldo_apos_sangria : saldo_apos_sangria},
                beforeSend: ()=>{
                    $('#preloader_sangria').css({'display' : 'block'});
                },
                success:function(data)
                {     
                    $('#preloader_sangria').css({'display' : 'none'});
                    if($.isEmptyObject(data.error)){
                        swal({
                            text: data.message,
                            icon: "success"
                        }).then(() =>{
                            location.reload(true);
                        });
                    }else{
                        $.each(data.error, function(index, value) {
                            $(".erro_valores_sangria").html(value);
                        });
                            setTimeout(() => {
                                $(".erro_valores_sangria").html("");
                            }, 3000);
                    }  
                }
            });  
        });

        $('.valor_suprimento').blur(function(){
            var saldo_atual_caixa = parseFloat($('.saldo_atual_caixa').val().replaceAll('.', '').replace(',', '.'));
            var valor_suprimento  = parseFloat($(this).val().replaceAll('.', '').replace(',', '.'));
            var soma_total_caixa  = saldo_atual_caixa + valor_suprimento;
            
            var options = { 
                            currency: 'BRL', 
                            minimumFractionDigits: 2, 
                            maximumFractionDigits: 3 
                        };

            var formatNumber = new Intl.NumberFormat('pt-BR', options);
       
            soma_total_caixa = formatNumber.format(soma_total_caixa)

            $('.saldo_apos_suprimento').val(soma_total_caixa); 
        });
        
        $('.valor_sangria').blur(function(){
            var saldo_atual_caixa     = $('.saldo_atual_caixa').val().replaceAll('.', '').replace(',', '.');
            var valor_sangria         = $(this).val().replaceAll('.', '').replace(',', '.');
            var diferenca_total_caixa = saldo_atual_caixa - valor_sangria;
            var options = { 
                            currency: 'BRL', 
                            minimumFractionDigits: 2, 
                            maximumFractionDigits: 3 
                        };

            var formatNumber = new Intl.NumberFormat('pt-BR', options);
       
            if (diferenca_total_caixa < 0)
            {
                $('.erro_sangria').html("Excedeu o valor do caixa.");
                $(this).val("");
                $('.saldo_apos_sangria').val("");

                setTimeout(() => {
                    $('.erro_sangria').html("");
                }, 3000);
               
            } else {
                diferenca_total_caixa = formatNumber.format(diferenca_total_caixa)

                $('.saldo_apos_sangria').val(diferenca_total_caixa);
            }   
        });

        $('.close').click(function(){
            $(".erro_sangria").html("");
            $(".erro_valores").html("");
            $('.saldo_apos_sangria').val("");
            $('.valor_sangria').val("");
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
        $('#caixa_aberto_title b').html("<i class='fas fa-cash-register'></i> " + descricao_caixa);
        suprimentoDescricaoCaixa(descricao_caixa);
        sangriaDescricaoCaixa(descricao_caixa);
    }

    function suprimentoDescricaoCaixa(descricao_caixa)
    {
        $('.numero_caixa_suprimento').val(descricao_caixa);
        $('#caixa_suprimento_title b').html("<i class='fas fa-cash-register'></i> " + descricao_caixa)
    }

    function sangriaDescricaoCaixa(descricao_caixa)
    {
        $('.numero_caixa_sangria').val(descricao_caixa);
        $('#caixa_sangria_title b').html("<i class='fas fa-cash-register'></i> " + descricao_caixa)
    }
</script>

@endpush