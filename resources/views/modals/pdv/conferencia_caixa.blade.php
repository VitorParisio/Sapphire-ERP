<div class="modal fade" id="conferencia_caixa_modal" tabindex="-1" role="dialog" aria-labelledby="conferencia_caixa_title" aria-hidden="true">
    <div class="modal-dialog" role="document" style="position: absolute">
      <div class="modal-content" style="width:100vw; left:0; margin:0; padding:0; height:70vh;">
        <div class="modal-header">
          <div style="display: flex; gap: 14px;">
            <h5 class="modal-title" id="conferencia_caixa_title"><i><b>CONFERÊNCIA CAIXA</b></i></h5>
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="border:none; border-radius:10px">
            <span aria-hidden="true" style="font-weight: bold">X</span>
          </button>
        </div>
        <div class="modal-body" style="overflow: auto">
            <div class="conferencia_caixa_pdv">
              <table class="table table-bordered tabela_conferencia_caixa">
                <thead>
                  <tr>
                    <th>Nº Cupom</th>
                    <th>Valor Recebido</th>
                    <th>Troco</th>
                    <th>Desconto</th>
                    <th>Total Venda</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
        </div>
        <div class="modal-footer">
          <div style="display: flex; justify-content: space-between; align-items:center; width:100%">
            <div>
              <span><b>TOTAL REALIZADOS:</b>&nbsp</span><span class="total_values_conferencia_caixa"></span>
            </div>
            <div style="background: darkcyan; color: #FFF; font-size: 20px; padding: 10px;">
              <span><b>TOTAL:</b>&nbspR$&nbsp</span><span class="total_venda_conferencia_caixa"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>