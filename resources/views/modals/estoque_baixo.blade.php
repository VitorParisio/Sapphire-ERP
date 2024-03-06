<div class="modal fade" id="estoque_baixo_modal" tabindex="-1" role="dialog" aria-labelledby="estoque_baixo_title" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="estoque_baixo_title"><i><b>Produtos - Estoque baixo</b></i></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="estoque_baixo_empresa">
            <div class="dados_estoque_baixo" style="height: 200px;width:100%;overflow:auto;">
              <table class="table table-striped mobile-tables">
                <thead>
                  <tr>
                    <th>Código</th>
                    <th>Produto</th>
                    <th>Estoque atual</th>
                    <th>Estoque mínimo</th>
                  </tr>
                </thead>
                <tbody class="list_produtos_estoque_baixo"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>