@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
    <div style="display:flex; justify-content:space-between" >
        <h5 class="m-0 text-dark"><i class="fas fa-play-circle"></i> Notas Fiscais</h5>
        <ol class="breadcrumb float-sm-right" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Notas Fiscais</li>
        </ol>
    </div>
    <div class="errors"></div>
@stop
@section('content')
    <div class="card card-primary notas_fiscais">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-left:10px;margin-top:15px; margin-right:10px;">
            <div>
                <a href="cadastrar_nota" class="btn btn-primary"><i class="fas fa-plus"></i> Nova nota</a>
            </div>
            <div>
                <input type="text" placeholder="Cliente" style="outline: none">
            </div>
        </div>
        <hr>  
        <div class="card-body lista_nota_fiscal">
            <table class="table table-striped tb_notas_fiscais">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Retorno</th>
                        <th>Nº NF-e</th>
                        <th>Série</th>
                        <th>Protocolo</th>
                        <th colspan="2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach($dados as $dado)
                    <tr>
                        <td>{{$dado->nota_id}}</td>
                        <td>{{$dado->nome}}</td>
                        <td>{{$dado->xMotivo}}</td>
                        <td>{{$dado->nro_nfe}}</td>
                        <td>{{$dado->serie_nfe}}</td>
                        <td>{{$dado->nProt}}</td>
                        <td style="display:none">{{$dado->chave_nfe}}</td>
                        <td style="display:none">{{$dado->digVal}}</td>
                        <td style="display:none">{{$dado->dataRecibo}}</td>
                        <td style="display:none">{{$dado->horaRecibo}}</td>
                        <td style="display:none">{{$dado->cStat}}</td>
                        <td>
                            <div class="btn-group">
                                @if($dado->status_id == 2)
                                    <button type="button" class="btn btn-secondary btn-sm" style="font-size:13px">Em digitação</button>
                                    <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item itens-nota-nfe"><i class="fas fa-th-list"></i> Itens da nota</a>
                                        <a class="dropdown-item" onclick="transSefaz('{{$dado->nota_id}}')"><i class="fas fa-paper-plane"></i> Transmitir Sefaz</a>
                                    </div>
                                @elseif ($dado->status_id == 4)
                                    <button type="button" class="btn btn-success btn-sm" style="font-size:13px">Concluída</button>
                                    <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item consulta-nfe"><i class="fas fa-search"></i> Consultar</a>
                                        <a class="dropdown-item" onclick="imprimeNfe('{{$dado->nota_id}}')"><i class="fas fa-print"></i> Imprimir DANFE</a>
                                        <a class="dropdown-item carta-correcao-nfe"><i class="fas fa-envelope-open-text"></i> Carta Correção</a>
                                        <a class="dropdown-item cancelamento-nfe"><i class="fas fa-ban"></i> Cancelamento</a>
                                    </div>
                                @else
                                    <button type="button" class="btn btn-danger btn-sm" style="font-size:13px">Cancelada</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div>
        @include('modals.nfe.itens_nota')
    </div>
    <div>
        @include('modals.nfe.consulta')
    </div>
    <div>
        @include('modals.nfe.cancelamento')
    </div>
    <div>
        @include('modals.nfe.carta_correcao')
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

        $('.consulta-nfe').on('click', function(){
            $('#consulta_nfe_modal').modal('show');

            $tr = $(this).closest('tr');
            
            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            $('#chave_nfe').html(data[6]);
            $('#protocolo_nfe').html(data[5]);
            $('#digval_nfe').html(data[7]);
            $('#data_recibo_nfe').html(data[8]);
            $('#hora_recibo_nfe').html(data[9]);
            $('#codigo_retorno_nfe').html(data[10]);
            $('#motivo_retorno_nfe').html(data[2]);
        })

        $('.itens-nota-nfe').on('click', function(){
            $('#itens_nota_nfe_modal').modal('show');
        })

        $('.cancelamento-nfe').on('click', function(){
            $('#cancelamento_nfe_modal').modal('show');

            $tr = $(this).closest('tr');
            
            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            $('#id_cancelamento').val(data[0]);
            $('#serie_cancelamento').val(data[4]);
            $('#numero_cancelamento').val(data[3]);
            $('#protocolo_cancelamento').val(data[5]);
        })

        $('.carta-correcao-nfe').on('click', function(){
            $('#carta_correcao_nfe_modal').modal('show');

            $tr = $(this).closest('tr');
            
            var data = $tr.children("td").map(function(){
                return $(this).html();
            }).get();

            $('#id_carta_correcao').val(data[0]);
            $('#serie_carta_correcao').val(data[4]);
            $('#numero_carta_correcao').val(data[3]);
            $('#protocolo_carta_correcao').val(data[5]);
        })

        $(document).on('submit', '#form_cancela_nfe', function(e){
            e.preventDefault();
            var cancelaFormNfe = new FormData($('#form_cancela_nfe')[0]);
           
            $.ajax({
                type: 'POST',
                url: '/cancela_nfe',
                data: cancelaFormNfe,
                processData: false,  
                contentType: false,  
                dataType: 'json',
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
                        $.each(data.error, function(index, value) {
                            $(".errors_cancela_nfe").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
                        });
                    }
                }
            });
        });

        $(document).on('submit', '#form_carta_correcao_nfe', function(e){
            e.preventDefault();
            var cartaCorrecaoFormNfe = new FormData($('#form_carta_correcao_nfe')[0]);
           
            $.ajax({
                type: 'POST',
                url: '/carta_correcao_nfe',
                data: cartaCorrecaoFormNfe,
                processData: false,  
                contentType: false,  
                dataType: 'json',
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
                        $.each(data.error, function(index, value) {
                            $(".errors_carta_correcao_nfe").html('<div style="background: red; color: #FFF; padding:10px; font-weight: bold; font-size: 14px">'+value+'</div>');
                        });
                    }
                }
            });
        });

        $('.close').click(function(){
            $(".errors_cancela_nfe").html("");
            $('#form_cancela_nfe').find('#id_cancelamento').prop("readonly",true);
            $('#form_cancela_nfe').find('#numero_cancelamento').prop("readonly",true);
            $('#form_cancela_nfe').find('#serie_cancelamento').prop("readonly",true);
            $('#form_cancela_nfe').find('#protocolo_cancelamento').prop("readonly",true);

            $('#form_carta_correcao_nfe').find('#id_carta_correcao').prop("readonly",true);
            $('#form_carta_correcao_nfe').find('#numero_carta_correcao').prop("readonly",true);
            $('#form_carta_correcao_nfe').find('#serie_carta_correcao').prop("readonly",true);
            $('#form_carta_correcao_nfe').find('#protocolo_carta_correcao').prop("readonly",true);
        });

    });

    // function itensNotNfe(id)
    // {
       
    //     $.ajax({
    //         url:"/itens_nota_nfe/"+id,
    //         method: 'GET',
    //         dataType: 'json',
    //         success:function(data)
    //         {
    //             if($.isEmptyObject(data.error))
    //             {   
    //                 swal({
    //                     text: data.message,
    //                     icon: "success"
    //                     }).then(() =>{
    //                         location.reload(true);
    //                     });
    //             } else {
    //                 swal({
    //                     text: data.error,
    //                     icon: "warning"
    //                 });
    //             }  
    //         }
    //     });
    // }

    function transSefaz(id)
    {
       
        $.ajax({
            url:"/gera_nfe/"+id,
            method: 'GET',
            dataType: 'json',
            success:function(data)
            {
                if($.isEmptyObject(data.error))
                {   
                    swal({
                        text: data.message,
                        icon: "success"
                        }).then(() =>{
                            location.reload(true);
                        });
                } else {
                    swal({
                        text: data.error,
                        icon: "warning"
                    });
                }  
            }
        });
    }

    function imprimeNfe(id)
    {
        $.ajax({
            url:"/imprime_nfe/"+id,
            method: 'GET',
            success:function(data)
            {
              window.open("/imprime_nfe/"+id, "IMIPRIMIR DANFE", "width=800,height=800");
              return false;
            }
        });
    }  

</script>
@endpush
