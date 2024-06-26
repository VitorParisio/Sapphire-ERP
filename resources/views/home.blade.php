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
                    <div style="display: flex; justify-content:space-between; align-items:center">
                        <h5 class="mb-0 h5-welcome" style="font-style: italic">Bem-vindo(a), {{Auth::user()->name}}.</h5>
                        <a href="javascript:void(0);" class="eye_all" onclick="changeTagAllNone();"><i class="fas fa-eye"></i> <span class="exibir_valores_box">Exibir todos</span></a>
                        <a href="javascript:void(0);" class="eye_slash_all" onclick="changeTagAllBlock();" style="display:none"><i class="fas fa-eye-slash"></i> <span class="exibir_valores_box">Ocultar todos</span></a>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <div class="mb-3" style="position: relative;">
                                        <div class="visibility_values_dashboard" id="values_vendas_dashboard" style="background: #2b302d; height:40px; width: 100%; border-radius: 5px; position:absolute; display:block"></div>
                                        <h3> R$ {{count($totalVendasMes) != 0 ? number_format($totalVendasMes[0]->total,2, ',', '.') : "0,00"}}</h3>
                                    </div>
                                    <div class="mb-3">
                                        <a style="display: flex; align-items:center; gap:10px; color:#FFF;" class="enable_disable_tag" onclick="changeTag('values_vendas_dashboard' , 'eye_vendas_dashboard')" href="javascript:void(0);"><span class="link_tags_dashboard">VENDAS</span><i class="fas fa-eye-slash eye_icon_slash_all" style="display: none" id="eye_vendas_dashboard"></i></a>
                                    </div>
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
                                    <div class="mb-3" style="position: relative;">
                                        <div class="visibility_values_dashboard" id="values_compras_dashboard" style="background: #2b302d; height:40px; width:100%; border-radius: 5px; position:absolute; display:block"></div>
                                        <h3>R$ {{count($totalCompraMes) != 0 ? number_format($totalCompraMes[0]->totalCompra,2, ',', '.') : "0,00"}}</h3>
                                    </div>
                                    <div class="mb-3">
                                        <a style="display: flex; align-items:center; gap:10px; color:#FFF;" class="enable_disable_tag" onclick="changeTag('values_compras_dashboard', 'eye_compras_dashboard')" href="javascript:void(0);"><span class="link_tags_dashboard">COMPRAS</span> <i class="fas fa-eye-slash eye_icon_slash_all" style="display: none" id="eye_compras_dashboard"></i></a>
                                    </div>
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
                                    <div class="mb-3" style="position: relative;">
                                        <div class="visibility_values_dashboard" id="values_clientes_dashboard" style="background: #2b302d; height:40px; width:100%; border-radius: 5px; position:absolute; display:block"></div>
                                        <h3>{{$clientes}}</h3>
                                    </div>
                                    <div class="mb-3">
                                        <a style="display: flex; align-items:center; gap:10px; color:#FFF;" class="enable_disable_tag" onclick="changeTag('values_clientes_dashboard', 'eye_clientes_dashboard')" href="javascript:void(0);"><span class="link_tags_dashboard">CLIENTES</span> <i class="fas fa-eye-slash eye_icon_slash_all" style="display: none" id="eye_clientes_dashboard"></i></a>
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <a href="/clientes" class="small-box-footer">IR PARA CLIENTES - <b>F8</b></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <div class="mb-3" style="position: relative;">
                                        <div class="visibility_values_dashboard" id="values_produtos_dashboard" style="background: #2b302d; height:40px; width:100%; border-radius: 5px; position:absolute; display:block"></div>
                                        <h3>{{$produtos}}</h3>
                                    </div>
                                    <div class="mb-3">
                                        <a style="display: flex; align-items:center; gap:10px; color:#FFF;" class="enable_disable_tag" onclick="changeTag('values_produtos_dashboard', 'eye_produtos_dashboard')" href="javascript:void(0);"><span class="link_tags_dashboard">PRODUTOS</span> <i class="fas fa-eye-slash eye_icon_slash_all" style="display: none" id="eye_produtos_dashboard"></i></a>
                                    </div>
                                </div>
                                <div class="icon">
                                    <i class="fab fa-product-hunt"></i>
                                </div>
                                <a href="/produtos" class="small-box-footer">IR PARA PRODUTOS - <b>F9<b/></a>
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
                    <canvas id="totalCompras"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <canvas id="totalClientes"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="row lista_mais_vendidos">
        <div class="col-md-12 mais_vendidos">
           <div class="card">
                <div class="card-header">
                    <h5>Mais vendidos - (Mês atual)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped tb_mais_vendidos mobile-tables">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Produto</th>
                                <th>Estoque atual</th>
                                <th>Qtd. vendidos</th>
                                <th>Valor unitário</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (sizeof($itens_vendidos) == 0)
                                <tr>
                                    <td colspan="5" style="font-weight:100; font-size: 19px"><i>Mais de 10x vendidos</i></td>
                                </tr> 
                            @else
                                @foreach($itens_vendidos as $values)
                                    <tr>
                                        <td data-label="Código">{{$values->id}}</td>
                                        <td data-label="Produto">{{$values->nome}}</td>
                                        <td data-label="Estoque atual">{{$values->estoque}}</td>
                                        <td data-label="Qtd. vendidos">{{$values->total_item_venda}}</td>
                                        <td data-label="Valor unitário">R$ {{number_format($values->preco_venda,2,',','.')}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
           </div>   
        </div>
    </div>
@stop
@push('chart')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function(){
        const totalVendas   = document.getElementById('totalVendas');
        const totalCompras  = document.getElementById('totalCompras');
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

            new Chart(totalCompras, {
            type: 'bar',
            data: {
                labels: {!!$mesComprasDado!!},
                datasets: [{
                label: ["{!! $totalComprasLabel !!}"],
                data: [{{ $totalMesDadoCompras }}],
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

        document.addEventListener('keyup', (e)=>{
            if (e.key === "F8")
                $(location).prop('href', '/clientes');
            
            if (e.key === "F9")
                $(location).prop('href', '/produtos');
        });
    });

    function changeTagAllNone()
    {
        $('.visibility_values_dashboard').css('display', 'none');
        $('.eye_slash_all').css('display', 'block');
        $('.eye_all').css('display', 'none');
        $('.eye_icon_slash_all').css('display', 'block') ;
    }

    function changeTagAllBlock()
    {
        $('.visibility_values_dashboard').css('display', 'block');
        $('.eye_slash_all').css('display', 'none');
        $('.eye_all').css('display', 'block');
        $('.eye_icon_slash_all').css('display', 'none') ;
    }
   
    function changeTag(tag, icon_eye)
    {
        $('.eye_slash_all').css('display', 'none');
        $('.eye_all').css('display', 'block');

        var div_tag  = document.getElementById(tag);
        var eye_icon = document.getElementById(icon_eye);
      
        if (div_tag.style.display == 'block'){
            div_tag.style.display  = 'none';
            eye_icon.style.display  = 'block';
        }else{
            div_tag.style.display  = 'block';
            eye_icon.style.display  = 'none';
        }
 
    }
</script>
@endpush