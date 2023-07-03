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
          <div class="errors_cancela_nfe"></div>
          <form id="form_cancela_nfe" method="POST">
            <div class="form-group">
              <label for="id_cancelamento">ID:
                <input type="text" name="id" id="id_cancelamento" class="form-control text-center" readonly/>
              </label>
              <label for="numero_cancelamento">Nº NF-e:
                <input type="text" name="nro_nfe" id="numero_cancelamento" class="form-control text-center" readonly/>
              </label>
            </div>
            <div class="form-group">
              <label for="serie_cancelamento">Série:
                <input type="text" name="serie_nfe" id="serie_cancelamento" class="form-control text-center" readonly/>
              </label>
              <label for="protocolo_cancelamento">Protocolo:
                <input type="text" name="nProt" id="protocolo_cancelamento" class="form-control text-center" readonly/>
              </label>
            </div>
            <div>
              <label for="justificativa_cancelamento">JUSTIFICATIVA:</label>
              <textarea name="xJust" id="justificativa_cancelamento" placeholder="Caracteres especiais aceitáveis: pontos(.), vígurlas(,) e dois-pontos(:)" cols="30" rows="7"></textarea>
            </div>
            @csrf
            <hr>
              <button type="submit" class="btn btn-danger text-right">Cancelar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>