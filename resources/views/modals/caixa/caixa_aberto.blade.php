<div class="modal fade" id="caixa_aberto_modal" tabindex="-1" role="dialog" aria-labelledby="caixa_aberto_title" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header" style="background:teal; color:#FFF;">
              <h5 class="modal-title" id="caixa_aberto_title"><b></b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="caixa_aberto">
                  <div class="aberto_caixa_dados" style="display:flex; flex-direction:column">
                    <span class="id_user_caixa_aberto" style="visibility: hidden;"></span>
                    <div>
                        <label for="usuario_abertura">Aberto por:</label>
                        <span class="usuario_abertura"></span>
                    </div>
                    <div>
                        <label for="data_abertura">Data de abertura:</label>
                        <span class="data_abertura"></span> às <span class="horario_abertura"></span>
                    </div>
                    <div>
                        <label for="valor_abertura">Valor de fundo quando aberto:</label>
                        R$ <span class="valor_abertura"></span>
                    </div>
                    <div>
                        <label for="total_caixa">Valor de fundo atual:</label>
                        R$ <span class="total_caixa"></span> 
                    </div>
                  </div>
              </div>
          </div>
          <hr>
          <div class="modal-footer" style="justify-content: flex-start">
            <div class="bg-primary" style="padding:10px">
                <i class="fas fa-plus-square"></i>&nbsp<a href="http://">Suprimento</a>
            </div>
            <div class="bg-danger" style="padding:10px">
                <i class="fas fa-minus-square"></i>&nbsp<a href="http://">Sangria</a>
            </div>
            <div class="bg-secondary" style="padding:10px">
                <i class="fas fa-cash-register"></i>&nbsp<a href="http://">Fechar caixa</a>
            </div>
          </div>
      </div>
    </div>
  </div>