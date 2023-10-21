<div class="modal fade" id="editar_cliente_modal" tabindex="-1" role="dialog" aria-labelledby="editar_cliente_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editar_cliente_title"><i><b>Editar cliente</b></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="editar_cliente">
          <div class="dados_editar">
            <div class="errors_editar_cliente"></div>
            <form id="form_edit_cliente" method="POST">
              @csrf
              <div style="display:flex; justify-content: space-between;">
                <input type="hidden" class="id_editar" />
                <div>
                  <label for="cliente_editar">Cliente:*</label>
                  <input type="text" class="cliente_editar" id="cliente_editar" name="nome" autocomplete="off"/>
                </div>
                <div style="text-align:right">
                  <label for="cpf_cnpj_editar">CPF/CNPJ:</label>
                  <input type="text" class="cpf_cnpj_editar" id="cpf_cnpj_editar" name="cpf_cnpj" style="text-align:right" autocomplete="off"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="rg_ie_editar">RG/Isnc. Estadual:</label>
                  <input type="text" class="rg_ie_editar" id="rg_ie_editar" name="rg_ie" autocomplete="off"/>
                </div>
                <div style="text-align:right">
                  <label for="email_editar">E-mail:</label>
                  <input type="text" class="email_editar" id="email_editar" name="email" style="text-align:right" autocomplete="off"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="fone">Telefone:</label><br>
                  <input type="text" class="fone_editar" id="fone_editar" name="fone" autocomplete="off"/>
                </div>
              </div>
              <hr>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="cep_editar">CEP:</label>
                  <input type="text" class="cep_editar" id="cep_editar" name="cep"/>
                </div>
                <div style="text-align:right">
                  <label for="logradouro_editar">Logradouro:</label>
                  <input type="text" class="logradouro_editar" id="logradouro_editar" name="rua" style="text-align:right"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="numero_editar">Número:</label>
                  <input type="text" class="numero_editar" id="numero_editar" name="numero"/>
                </div>
                <div style="text-align:right">
                  <label for="complemento_editar">Complemento:</label>
                  <input type="text" class="complemento_editar" id="complemento_editar" name="complemento" style="text-align:right"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="bairro_editar">Bairro:</label>
                  <input type="text" class="bairro_editar" id="bairro_editar" name="bairro"/>
                </div>
                <div style="text-align:right">
                  <label for="cidade_editar">Cidade:</label>
                  <input type="text" class="cidade_editar" id="cidade_editar" name="cidade" style="text-align:right"/>
                </div>
              </div>
              <div>
                <label for="uf_editar">UF:</label><br>
                <input type="text" class="uf_editar" id="uf_editar" name="uf"/>
              </div>
              <hr>
              <button type="submit" class="btn btn-primary">Editar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>