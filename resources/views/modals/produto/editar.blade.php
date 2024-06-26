<div class="modal fade" id="editar_produto_modal" tabindex="-1" role="dialog" aria-labelledby="editar_produto_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal_editar_produto" style="width: 700px;">
      <div class="modal-header">
        <h5 class="modal-title" id="editar_produto_title"><i><b>Editar produto</b></i></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="editar_produto">
          <div class="produto_imagem_editar">
            <span class="img_editar"></span>
            <label for="img_produto_editar">
              <span class="texto">Atualizar imagem</span> 
            </label>
          </div>
          <div class="dados_editar">
            <div class="errors_editar_produto"></div>
            <form id="form_edit_produto" method="POST" enctype="multipart/form-data">
              <div>
                <h4 style="background:teal; color:#FFF; padding:5px; font-size:16px;"><i class="fas fa-info-circle"></i> Informações básicas</h4>
              </div>
                <input type="file" name="img" id="img_produto_editar">
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="select_categoria">Categoria*</label>
                  <select type="text" class="select_categoria" id="select_categoria" name="category_id"></select>
                </div>
                <div>
                  <label for="produto">Produto*</label>
                  <input type="text" class="produto_editar" id="produto" name="nome" autocomplete="off"/>
                </div>
                <div>
                  <label for="cod_barra">EAN (Cód. barra)</label>
                  <input type="text" class="cod_barra_editar" id="cod_barra" name="cod_barra" autocomplete="off"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="preco_compra">Preço custo(R$)*</label>
                  <input type="text" class="preco_compra_editar" id="preco_compra" name="preco_compra" autocomplete="off"/>
                </div>
                <div>
                  <label for="preco_venda">Preço venda(R$)*</label>
                  <input type="text" class="preco_venda_editar" id="preco_venda" name="preco_venda" autocomplete="off"/>
                </div>
                <div>
                  <label for="preco_minimo">Preço mínimo(R$)</label>
                  <input type="text" class="preco_minimo_editar" id="preco_minimo" name="preco_minimo" autocomplete="off"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="lucro_real_editar">Lucro Real(R$)</label>
                  <input type="text" class="lucro_real_editar" id="lucro_real_editar" name="margem_lucro" autocomplete="off" readonly="readonly"/>
                </div>
                <div>
                  <label for="lucro_per_editar">Lucro %</label>
                  <input type="text" class="lucro_per_editar" id="lucro_per_editar" name="margem_lucro_per" autocomplete="off" readonly="readonly"/>
                </div>
                <div>
                  <label for="descricao">Descrição</label>
                  <input type="text" class="descricao_editar" id="descricao" name="descricao" autocomplete="off"/>
                </div>
              </div>
              <div style="display:flex; justify-content: space-between">
                <div>
                  <label for="estoque">Estoque atual*</label>
                  <input type="text" class="estoque_editar" id="estoque" name="estoque" autocomplete="off"/>
                </div>
                <div>
                  <label for="estoque_minimo">Estoque mínmo*</label>
                  <input type="text" class="estoque_minimo_editar" id="estoque_minimo" name="estoque_minimo" autocomplete="off"/>
                </div>
                <div>
                  <label for="unidade_medida">Unidade/Medida</label>
                  <input type="text" class="unidade_medida_editar" id="unindade_medida" name="unindade_medida" />
                </div>
              </div>
              <div style="display:flex; justify-content: start; gap:36px">
                <div>
                  <label for="qtd_atacado">Qtd. Atacado</label>
                  <input type="text" class="qtd_atacado_editar" id="qtd_atacado" name="qtd_atacado" autocomplete="off"/>
                </div>
                <div>
                  <label for="preco_atacado">Preço Atacado(R$)</label>
                  <input type="text" class="preco_atacado_editar" id="preco_atacado" name="preco_atacado" autocomplete="off"/>
                </div>
                <div>
                  <label for="validade">Validade</label>
                  <input type="date" class="validade_editar" id="validade" name="validade"/>
                </div>
              </div>
              <div style="position:relative; margin-top: 4px; display:flex; justify-content: space-between; width:100%">
                <div>
                  <label for="validade">Entrada de produto?</label>
                  <div class="entrada_produto_editar">
                    Sim <input type="radio" name="entrada" value="yes">
                    Não <input type="radio" name="entrada" value="no">
                  </div>
                </div>
                <button style="position: absolute; right:0; top: 15px;" type="submit" class="btn btn-primary btn_editar_produto">Editar produto - <b>F5</b></button>
              </div>
              <div>
                <h4 style="background:teal; color:#FFF; padding:5px; font-size:16px; margin-top:10px;"><i class="fas fa-file-invoice-dollar"></i> Dados fiscais</h4>
              </div>
              <div style="display:flex; justify-content: space-between;">
                <input type="hidden" class="id_editar" />
                  <div>
                    <label for="ncm_editar">NCM</label>
                    <input type="text" class="ncm_editar" id="ncm_editar" name="ncm" autocomplete="off"/>
                  </div>
                  <div>
                    <label for="cest">CEST</label>
                    <input type="text" class="cest_editar" id="cest_editar" name="cest" autocomplete="off"/>
                  </div>
                  <div>
                    <label for="extipi">IPI:</label>
                    <input type="text" class="extipi_editar" id="extipi_editar" name="extipi" autocomplete="off"/>
                  </div>
              </div>
              <div style="display:flex; justify-content: start; gap:41px">
                  <div>
                    <label for="cfop">CFOP</label>
                    <input type="text" class="cfop_editar" id="cfop_editar" value="5101"/>
                  </div>
                  <div>
                    <label for="origem">Origem</label>
                    <input type="text" class="origem_editar" id="origem_editar" value="0 - Nacional"/>
                  </div>
              </div>
              <div>
                <label for="situacao_tributaria">Situacao tributária</label>
                <input type="text" class="situacao_tributaria_editar" id="situacao_tributaria_editar" style="width:100%" value="102 - Tributada pelo Simples Nacional sem permissão de crédito"/>
              </div>
              <label for="ceantrib" style="display: none">EAN Unid. Tributável
                <input type="text" class="form-control" name="ceantrib" id="ceantrib_editar">
              </label>
              <label for="qtrib" style="display: none">Qtd. Tributável
                  <input type="text" class="form-control" name="qtrib" id="qtrib_editar">
              </label>
              <label for="vuntrib" style="display: none">Valor Unid. Tributável(R$)*
                  <input type="text" class="form-control" name="vuntrib" id="vuntrib_editar">
              </label>
              <input type="hidden" class="id_editar"/>
              <div style="margin-top:10px; float: right;">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>