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
              <label for="certificado_digital_editar">
                <span class="texto">Editar Certificado</span> 
              </label>
              <input type="file" name="certificado_digital" id="certificado_digital_editar">
              <div style="display:flex; justify-content: space-between">
                <input type="hidden" class="id_editar" />
                <div>
                  <label for="cnpj_editar">CNPJ:</label>
                  <input type="text" class="cnpj_editar" id="cnpj_editar" name="cnpj"/>
                </div>
                <div>
                  <label for="ie_editar">Isnc. Estadual:</label>
                  <input type="text" class="ie_editar" id="ie_editar" name="ie"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="razao_editar">Razão Social:</label>
                  <input type="text" class="razao_editar_editar" id="razao_editar" name="razao_social"/>
                </div>
                <div>
                  <label for="preco_venda">Preço venda:</label>
                  <input type="text" class="preco_venda_editar" id="preco_venda" name="preco_venda"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="estoque">Estoque:</label>
                  <input type="text" class="estoque_editar" id="estoque" name="estoque"/>
                </div>
                <div>
                  <label for="descricao">Descrição:</label>
                  <input type="text" class="descricao_editar" id="descricao" name="descricao"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="select_categoria">Categoria:</label>
                  <select type="text" class="select_categoria" id="select_categoria" name="category_id"></select>
                </div>
                <div>
                  <label for="validade">Validade:</label>
                  <input type="date" class="validade_editar" id="validade" name="validade"/>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Editar</button>
            </form>
          </div>
        </div>
      </div>
      <div class="errors_editar_empresa"></div>
    </div>
  </div>
</div>