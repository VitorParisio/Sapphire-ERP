<li class="nav-item">
    <a class="nav-link nav-link-display-notifications" data-widget="notification" href="javascript:void(0);" role="button">
        <i class="fas fa-bell"></i>
        <span class="badge badge-light notifications" style="position: absolute; background: red; border-radius: 10px; height: 10px; margin: 0 -5px; color: white;"></span>
    </a>
</li>
<div id="list_notifications" class="list_notifications" style="display: none; position: absolute; text-align:center; border-radius: 10px; margin: 33px 83px; box-shadow: 0px 3px 2px 0px rgba(0,0,0,0.3); background: gainsboro; padding: 10px;">
    <div class="estoque_baixo_msg"></div>
    <div class="lista_produtos_estoque_baixo" style="overflow-y: auto; height: auto; max-height:100px; text-align: center; background: #FFF;">
        <ul class="lista_produtos_baixo"></ul>
    </div>
    <div class="ir_lista_produtos_estoque_baixo" style="text-align: center; margin-top: 10px; display: none">
        <a href="{{route('produtos')}}">Ir para lista</a>
    </div>
</div>


