<div class="modal fade" id="cancela_venda_modal" tabindex="-1" role="dialog" aria-labelledby="cancela_venda_title" aria-hidden="true">
    <div class="modal-dialog" role="document" style="position: absolute">
      <div class="modal-content" style="width:100vw; left:0; margin:0; padding:0; height:70vh;">
        <div class="modal-header">
          <div style="display: flex; gap: 14px;">
            <h5 class="modal-title" id="cancela_venda_title"><i><b>CANCELAR VENDA</b></i></h5>
            <input type="text" class="list_venda_pdv_table_search" placeholder="Nº CUPOM"/>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="border:none; border-radius:10px">
            <span aria-hidden="true" style="font-weight: bold">X</span>
          </button>
        </div>
        <div class="modal-body" style="overflow: auto">
            <div class="cancela_venda_pdv">
              <table class="table table-bordered tabela_vendas_pdv">
                <div class="preloader" id="preloader_itens_vendas"><img src="{{asset('img/preloader.gif')}}" alt=""></div>
                <thead>
                  <tr>
                    <th>Nº Cupom</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Vendedor</th>
                    <th>Total venda</th>
                    <th>Situação</th>
                    <th>Cancelar</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
        </div>
        <div class="modal-footer">
        </div>
      </div>
    </div>
  </div>