<div class="modal fade" id="detalhe_produto_modal" tabindex="-1" role="dialog" aria-labelledby="detalhe_produto_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detalhe_produto_title"><i><b>Detalhes do produto</b></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        
        <div class="detalhes_produto">
          <div sytle="display:flex; flex-direction:column; justify-content:center; border:1px solid">
            <span class="produto_detalhe"></span>
            <span class="img_detalhe"></span> 
          </div>
          <div class="dados_detalhe">
            <div style="display:flex; justify-content: space-between">
              <div>
                <label for="id_detalhe">ID:</label>
                <span class="id_detalhe"></span>
              </div>
              <div>
                <label for="cod_barra_detalhe" style="text-align:right">Cod. Barra:</label>
                <span class="cod_barra_detalhe"></span>
              </div>
            </div>
            <div style="display:flex; justify-content: space-between">
              <div>
                <label for="preco_custo_detalhe">Preço custo:</label>
                <span class="preco_custo_detalhe"></span>
              </div>
              <div>
                <label for="preco_venda_detalhe" style="text-align:right">Preço venda:</label>
                <span class="preco_venda_detalhe"></span>
              </div>
            </div>
            <div style="display:flex; justify-content: space-between">
              <div>
                <label for="estoque_detalhe">Estoque:</label>
                <span class="estoque_detalhe"></span>
              </div>
              <div>
                <label for="descricao_detalhe" style="text-align:right">Descrição:</label> 
                <span class="descricao_detalhe"></span>
              </div>
            </div>
            <div style="display:flex; justify-content: space-between">
              <div>
                <label for="unidade_detalhe">Unidade/Medida:</label>
                <span class="unidade_detalhe"></span>
              </div>
              <div>
                <label for="validade_detalhe" style="text-align:right">Validade:</label> 
                <span class="validade_detalhe"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>