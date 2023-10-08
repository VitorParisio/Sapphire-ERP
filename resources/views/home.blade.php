@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
<h5 class="m-0 text-dark"><i class="fas fa-play-circle"></i> Dashboard</h5>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-0" style="font-style: italic">Bem-vindo(a), {{Auth::user()->name}}.</h5>
                    <hr>
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner"> 
                                    <h3><span style></span> R$ {{count($totalVendasMes) != 0 ? number_format($totalVendasMes[0]->total,2, ',', '.') : "0,00"}}</h3>
                                    <p>VENDAS</p>
                                </div>
                                <div class="icon">
                                    <i class="fab fa-sellcast"></i>
                                </div>
                                <span class="small-box-footer">MÊS ATUAL</span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>R$ {{count($totalCompraMes) != 0 ? number_format($totalCompraMes[0]->total,2, ',', '.') : "0,00"}}</h3>
                                    <p>COMPRAS</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <span class="small-box-footer">MÊS ATUAL</span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                <h3>{{$clientes}}</h3>
                                    <p>CLIENTES</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <a href="/clientes" class="small-box-footer">Ir para clientes <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                <h3>{{$produtos}}</h3>
                                    <p>PRODUTOS</p>
                                </div>
                                <div class="icon">
                                    <i class="fab fa-product-hunt"></i>
                                </div>
                                <a href="/produtos" class="small-box-footer">Ir para produtos <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <canvas id="totalVendas"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <canvas id="totalClientes"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
           <div class="card">
                <div class="card-header">
                    <h5>Mais vendidos - (Mês atual)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Produto</th>
                                <th>Estoque atual</th>
                                <th>Qtd. vendidos</th>
                                <th>Valor unitário</th>
                            </tr>
                        </thead>
                        <body>
                            @foreach($itens_vendidos as $values)
                                @if ($values->total_item_venda <= 10)
                                <tr>
                                    <td colspan="5" style="font-weight:100; font-size: 19px"><i>Produtos mais de 10x vendidos.</i></td>
                                </tr>
                                @else
                                    <tr>
                                        <td>{{$values->id}}</td>
                                        <td>{{$values->nome}}</td>
                                        <td>{{$values->estoque}}</td>
                                        <td>{{$values->total_item_venda}}</td>
                                        <td>R$ {{number_format($values->preco_venda,2,',','.')}}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </body>
                    </table>
                </div>
           </div>   
        </div>
    </div>
@stop
@push('chart')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const totalVendas  = document.getElementById('totalVendas');
    const totalClientes = document.getElementById('totalClientes');
  
    new Chart(totalVendas, {
      type: 'bar',
      data: {
        labels: {!!$mesVendasDado!!},
        datasets: [{
          label: ["{!! $totalVendasLabel !!}"],
          data: [{{ $totalMesDado }}],
          backgroundColor: ['rgba(75, 192, 192, 0.2)'],
          borderColor:['rgb(75, 192, 192)'],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
            y: {
            beginAtZero: true,
            ticks:{
                callback: (value, index, values) => {
                    return new Intl.NumberFormat('br-BR',{
                        style:'currency',
                        currency: 'BRL',
                        maximumSignificantDigits:3
                    }).format(value);
                }
            }
          }
        }
      }
    });

    new Chart(totalClientes, {
      type: 'line',
      data: {
        labels: {!!$mesClienteDados!!},
        datasets: [{
          label: ["{!! $clienteLabel !!}"],
          data: [{{ $totalMesCliente }}],
          borderWidth: 1,
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
});
</script>
<script>
    
</script>
@endpush