@extends('adminlte::page')

@section('title', 'AdminLTE')

@section('content_header')
<h5 class="m-0 text-dark"><i class="fas fa-play-circle"></i> Relatórios</h5>
@stop

@section('content')
   <div class="title_relatorio_produto">
      <h3>Produtos</h3>    
   </div>
   <div class="card card-primary card-outline">
      <div class="card-body">
         <div style="display: flex; gap: 6px">
            <form id="rel_produto_filtro">
               De:&nbsp<input type="date" name="data_de_produto" id="data_de_produto"> Até:&nbsp<input type="date" name="data_ate_produto" id="data_ate_produto">&nbsp&nbsp<button class="rel_prod_btn_filtro" type="submit">Ir</button>
            </form>
            <div class="rel_produto_filtro_erro"></div>
            <a href="/importar_pdf_produto" class="importar_pdf_produto">dsad</a>
         </div>
         <hr>
         <div class="no_data_filtro_prod"></div>
         <div class="show_table_data_prod">
            <div id="preloader_itens_vendas"><img src="{{asset('img/preloader.gif')}}" alt=""></div>
            <table id="rel_prod_tabela" class="table">
               <thead>
                  <tr>
                     <th>Código</th>
                     <th>Categoria</th>
                     <th>Produto</th>
                     <th>Qtd</th>
                     <th>Custo</th>
                     <th>Unitário</th>
                  </tr>
               </thead>
               <tbody>
               </tbody>
            </table>
         </div>
      </div>
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
         $('.no_data_filtro_prod').append('<span><i>Nenhuma pesquisa realizada.</i></span>');

         $(document).on('submit', '#rel_produto_filtro', function(e){
            e.preventDefault();
       
            var data_de_produto  = $('#data_de_produto').val();
            var data_ate_produto = $('#data_ate_produto').val();
            
            var dados_data_rel_produto = {
               'data_de_produto' : data_de_produto,
               'data_ate_produto' : data_ate_produto
            }

            $.ajax({
               method: 'GET',
               url: '/filtro_produto',
               data:dados_data_rel_produto,
               dataType: 'json',
               beforeSend: () =>{
                    $("#preloader_itens_vendas").css({'display' : 'block'});
                }, 
               success: function(data){  
                  if (data.msg_erro)
                  {
                     $("#preloader_itens_vendas").css({'display' : 'none'});
                     $('.rel_produto_filtro_erro').css({'display' : 'block'});
                     $('.rel_produto_filtro_erro').html(data.msg_erro);
                     setTimeout(() => {
                        $('.rel_produto_filtro_erro').css({'display' : 'none'})
                     }, 3000);
                  } else {
                     
                     $("#preloader_itens_vendas").css({'display' : 'none'});
                     $('.no_data_filtro_prod').css({'display' : 'none'});
                     $('.show_table_data_prod').css({'display' : 'block'});
                     $('#rel_prod_tabela tbody').html(data.dados_filtro_prod);
                  }
               }
            })
         });

         $('.importar_pdf_produto').on('click', function(e){
            e.preventDefault();

            alert('srs')
         })
      })
   </script>
@endpush