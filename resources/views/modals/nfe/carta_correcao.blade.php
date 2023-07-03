<div class="modal fade" id="carta_correcao_nfe_modal" tabindex="-1" role="dialog" aria-labelledby="carta_correcao_nfe_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="carta_correcao_nfe_title"><i><b>Carta correção da NF-e</b></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="carta_correcao_nfe">
          <div class="errors_carta_correcao_nfe"></div>
          <form id="form_carta_correcao_nfe" method="POST">
            <div class="form-group">
              <label for="id_carta_correcao">ID:
                <input type="text" name="id" id="id_carta_correcao" class="form-control text-center" readonly/>
              </label>
              <label for="numero_carta_correcao">Nº NF-e:
                <input type="text" name="nro_nfe" id="numero_carta_correcao" class="form-control text-center" readonly/>
              </label>
            </div>
            <div class="form-group">
              <label for="serie_carta_correcao">Série:
                <input type="text" name="serie_nfe" id="serie_carta_correcao" class="form-control text-center" readonly/>
              </label>
              <label for="protocolo_carta_correcao">Protocolo:
                <input type="text" name="nProt" id="protocolo_carta_correcao" class="form-control text-center" readonly/>
              </label>
            </div>
            <div>
              <label for="justificativa_carta_correcao">RETIFICAR:</label>
              <textarea name="xJust" id="justificativa_carta_correcao" placeholder="Caracteres especiais aceitáveis: pontos(.), vígurlas(,) e dois-pontos(:)" cols="30" rows="7"></textarea>
            </div>
            @csrf
            <hr>
              <button type="submit" class="btn btn-secondary">Enviar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>