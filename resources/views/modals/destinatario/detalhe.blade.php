<div class="modal fade" id="detalhe_cliente_modal" tabindex="-1" role="dialog" aria-labelledby="detalhe_cliente_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detalhe_cliente_title"><i><b>Detalhes do cliente</b></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="detalhes_cliente">
          <div class="dados_detalhe">
            <h5 style="background: teal; padding: 5px; color:#FFF;"><i class="fas fa-info-circle"></i> Informações</h5>
            <div style="display:flex; justify-content: space-between;">
              <div style="display:flex; flex-direction:column">
                <label for="id_cliente_detalhe">ID:</label>
                <span class="id_cliente_detalhe"></span>
              </div>
              <div style="display:flex; flex-direction:column; text-align:right"> 
                <label for="cliente_detalhe">Cliente:</label>
                <span class="cliente_detalhe"></span>
              </div>
            </div>
            <hr>
            <div style="display:flex; justify-content: space-between">
              <div style="display:flex; flex-direction:column">
                <label for="cpf_cnpj_detalhe">CPF/CNPJ:</label>
                <span class="cpf_cnpj_detalhe"></span>
              </div>
              <div style="display:flex; flex-direction:column; text-align:right">
                <label for="rg_ie_detalhe">RG/Insc. Estadual:</label>
                <span class="rg_ie_detalhe"></span>
              </div>
            </div>
            <hr>
            <div style="display:flex; justify-content: space-between">
              <div style="display:flex; flex-direction:column">
                <label for="email_detalhe">E-mail:</label>
                <span class="email_detalhe"></span>
              </div>
              <div style="display:flex; flex-direction:column; text-align:right">
                <label for="fone_detalhe">Telefone:</label>
                <span class="fone_detalhe"></span>
              </div>
            </div>
          </div>
          <hr>
          <div class="end_cliente_detalhe">
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