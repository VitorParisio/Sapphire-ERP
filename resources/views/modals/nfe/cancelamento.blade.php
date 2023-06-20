<div class="modal fade" id="cancelamento_nfe_modal" tabindex="-1" role="dialog" aria-labelledby="cancelamento_nfe_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="cancelamento_nfe_title"><i><b>Cancelamento da NF-e</b></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="cancelamento_nfe">
          <div>
            <label for="id_cancelamento">ID:</label><br>
            <input type="text" readonly value="ts"/>
          </div>
          <div>
            <label for="serie_cancelamento">Série:</label>
            <input type="text" readonly />
          </div>
          <div>
            <label for="numero_cancelamento">Número:</label>
            <input type="text" readonly />
          </div>
          <div>
            <label for="chave_cancelamento">Chave:</label>
            <input type="text" readonly />
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>