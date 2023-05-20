<div class="modal fade" id="editar_empresa_modal" tabindex="-1" role="dialog" aria-labelledby="editar_empresa_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editar_empresa_title"><i><b>Editar empresa</b></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="editar_empresa">
          <div class="dados_editar">
            <form id="form_edit_empresa" method="POST" enctype="multipart/form-data">
            @csrf
              <div style="display:flex; justify-content: space-between;">
                <input type="hidden" class="id_editar" />
                <div>
                  <label for="cnpj_editar">CNPJ:*</label>
                  <input type="text" class="cnpj_editar" id="cnpj_editar" name="cnpj"/>
                </div>
                <div style="text-align:right">
                  <label for="ie_editar">Isnc. Estadual:*</label>
                  <input type="text" class="ie_editar" id="ie_editar" name="ie" style="text-align:right"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="razao_editar">Razão Social:*</label>
                  <input type="text" class="razao_editar" id="razao_editar" name="razao_social"/>
                </div>
                <div style="text-align:right">
                  <label for="nome_fantasia">Fantasia:*</label>
                  <input type="text" class="fantasia_editar" id="nome_fantasia" name="nome_fantasia" style="text-align:right"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="im">Isnc. Municipal:</label>
                  <input type="text" class="im_editar" id="im_editar" name="im"/>
                </div>
                <div style="text-align:right">
                  <label for="cnae">CNAE:</label>
                  <input type="text" class="cnae_editar" id="cnae_editar" name="cnae" style="text-align:right"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <input type="file" name="certificado_a1" id="file_empresa_input_editar">
                  <label for="file_empresa_input_editar">
                    <span class="texto">Certificado:</span> 
                  </label>
                  <label for="file_empresa_input_editar" class="file_empresa_input_editar">
                    <span>Procurar</span>
                    <span>Selecionar certificado</span>
                  </label>
                </div>
                <div style="text-align:right">
                  <label for="senha_certificado_editar">Senha (certificado):
                      <input type="password" name="senha_certificado" id="senha_certificado_editar" value="" style="text-align: right">
                  </label>
                </div>
              </div>
              <hr>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="cep_editar">CEP:*</label>
                  <input type="text" class="cep_editar" id="cep_editar" name="cep"/>
                </div>
                <div style="text-align:right">
                  <label for="logradouro_editar">Logradouro:*</label>
                  <input type="text" class="logradouro_editar" id="logradouro_editar" name="rua" style="text-align:right"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="numero_editar">Número:*</label>
                  <input type="text" class="numero_editar" id="numero_editar" name="numero"/>
                </div>
                <div style="text-align:right">
                  <label for="complemento_editar">Complemento:</label>
                  <input type="text" class="complemento_editar" id="complemento_editar" name="complemento" style="text-align:right"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="bairro_editar">Bairro:*</label>
                  <input type="text" class="bairro_editar" id="bairro_editar" name="bairro"/>
                </div>
                <div style="text-align:right">
                  <label for="cidade_editar">Cidade:*</label>
                  <input type="text" class="cidade_editar" id="cidade_editar" name="cidade" style="text-align:right"/>
                </div>
              </div>
              <div>
                <label for="uf_editar">UF:*</label><br>
                <input type="text" class="uf_editar" id="uf_editar" name="uf"/>
              </div>
              <hr>
              <button type="submit" class="btn btn-primary">Editar</button>
            </form>
          </div>
        </div>
      </div>
      <div class="errors_editar_empresa"></div>
    </div>
  </div>
</div>