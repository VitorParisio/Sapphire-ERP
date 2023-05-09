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
                <a href="cadastrar_nota" class="btn btn-success"><i class="fas fa-plus"></i> Cadastrar</a>
            </div>
            <div>
                <input type="text">
            </div>
        </div>
        <hr>  
        <div class="card-body lista_nota_fiscal">
            <table class="table-striped tb_notas_fiscais">
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
                                    <button type="button" class="btn btn-primary btn-sm" style="font-size:13px">Em digitação</button>
                                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item"><i class="fas fa-eye"></i> Visualizar</a>
                                        <a class="dropdown-item" onclick="transSefaz('{{$dado->nota_id}}')"><i class="fas fa-paper-plane"></i> Transmitir Sefaz</a>
                                    </div>
                                @else
                                <button type="button" class="btn btn-success btn-sm" style="font-size:13px">Concluída</button>
                                    <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item consulta-nfe"><i class="fas fa-search"></i> Consultar</a>
                                        <a class="dropdown-item" onclick="imprimeNfe('{{$dado->nota_id}}')"><i class="fas fa-print"></i> Imprimir DANFE</a>
                                        <a class="dropdown-item"><i class="fas fa-envelope-open-text"></i> Carta Correção</a>
                                        <a class="dropdown-item"><i class="fas fa-ban"></i> Cancelamento</a>
                                    </div>
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
        @include('modals.nfe.consulta')
    </div>
@stop
@push('scripts')
<script>

    $(function(){
        $('.consulta-nfe').on('click', function(){
            $('#consulta_nfe_modal').modal('show');

            $tr = $(this).closest('tr');
            console.log($tr)
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
    });

    function transSefaz(id)
    {
        $.ajax({
            url:"/gera_nfe/"+id,
            method: 'GET',
            dataType: 'json',
            success:function(data)
            {
                swal({
                text: data.message,
                icon: "success"
            }).then(() =>{
                location.reload(true);
            });
                 
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
