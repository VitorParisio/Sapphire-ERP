<div class="modal fade" id="detalhe_empresa_modal" tabindex="-1" role="dialog" aria-labelledby="detalhe_empresa_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detalhe_empresa_title"><i><b>Detalhes da empresa</b></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="detalhes_empresa">
          <div class="dados_detalhe">
            <h5 style="background: teal; padding: 5px; color:#FFF;"><i class="fas fa-info-circle"></i> Informações</h5>
            <div style="display:flex; justify-content: space-between;">
              <div style="display:flex; flex-direction:column">
                <label for="id_detalhe">ID:</label>
                <span class="id_empresa_detalhe"></span>
              </div>
              <div style="display:flex; flex-direction:column; text-align:right"> 
                <label for="id_detalhe">CNPJ:</label>
                <span class="cnpj_detalhe"></span>
              </div>
            </div>
            <hr>
            <div style="display:flex; justify-content: space-between">
              <div style="display:flex; flex-direction:column">
                <label for="razao_detalhe">Razão Social:</label>
                <span class="razao_detalhe"></span>
              </div>
              <div style="display:flex; flex-direction:column; text-align:right">
                <label for="fantasia_detalhe">Fantasia:</label>
                <span class="fantasia_detalhe"></span>
              </div>
            </div>
            <hr>
            <div style="display:flex; justify-content: space-between">
              <div style="display:flex; flex-direction:column">
                <label for="ie_detalhe">Inscrição Estadual:</label>
                <span class="ie_detalhe"></span>
              </div>
              <div style="display:flex; flex-direction:column; text-align:right">
                <label for="im_detalhe">Inscrição Municipal:</label>
                <span class="im_detalhe"></span>
              </div>
            </div>
            <hr>
            <div style="display:flex; justify-content: space-between">
              <div>
                <label for="cnae_detalhe">CNAE:</label>
                <span class="cnae_detalhe"></span>
              </div>
          </div>
          <div class="end_empresa_detalhe">
            <h5 style="background: teal; padding: 5px; color:#FFF;"><i class="fas fa-map-marker-alt"></i> Endereço</h5>
            <div style="display:flex; justify-content: space-between">
              <div style="display:flex; flex-direction:column">
                <label for="cep">CEP:</label>
                <span class="cep"></span>
              </div>
              <div style="display:flex; flex-direction:column; text-align:right">
                <label for="logradouro">Logradouro:</label>
                <span class="logradouro"></span>
              </div>
            </div>
            <hr>
            <div style="display:flex; justify-content: space-between">
              <div style="display:flex; flex-direction:column">
                <label for="numero">Número:</label>
                <span class="numero"></span>
              </div>
              <div style="display:flex; flex-direction:column; text-align:right">
                <label for="complemento">Complemento:</label>
                <span class="complemento"></span>
              </div>
            </div>
            <hr>
            <div style="display:flex; justify-content: space-between">
              <div style="display:flex; flex-direction:column">
                <label for="bairro">Bairro:</label>
                <span class="bairro"></span>
              </div>
              <div style="display:flex; flex-direction:column; text-align:right">
                <label for="cidade">Cidade:</label>
                <span class="cidade"></span>
              </div>
            </div>
            <hr>
            <div style="display:flex; justify-content: space-between">
              <div style="display:flex; flex-direction:column;">
                <label for="uf">UF:</label>
                <span class="uf"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>