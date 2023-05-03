<div class="modal fade" id="editar_produto_modal" tabindex="-1" role="dialog" aria-labelledby="editar_produto_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editar_produto_title"><i><b>Editar produto</b></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="editar_produto">
          <div>
            <span class="img_editar"></span>
          </div>
          <div class="dados_editar">
            <form id="form_edit_produto" method="POST" enctype="multipart/form-data">
              <label for="img_produto_editar">
                <span class="texto">Atualizar imagem</span> 
              </label>
              <input type="file" name="img" id="img_produto_editar">
              <div style="display:flex; justify-content: space-between">
                <input type="hidden" class="id_editar" />
                <div>
                  <label for="cod_barra">Cód. barra:</label>
                  <input type="text" class="cod_barra_editar" id="cod_barra" name="cod_barra"/>
                </div>
                <div>
                  <label for="produto">Produto:</label>
                  <input type="text" class="produto_editar" id="produto" name="nome"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="preco_compra">Preço custo:</label>
                  <input type="text" class="preco_compra_editar" id="preco_compra" name="preco_compra"/>
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
      <div class="errors_editar_produto"></div>
    </div>
  </div>
</div>