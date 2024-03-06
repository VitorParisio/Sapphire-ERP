<div class="modal fade" id="editar_categoria_modal" tabindex="-1" role="dialog" aria-labelledby="editar_categoria_title" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="editar_categoria_title"><i><b>Editar categoria</b></i></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <div class="editar_categoria">
            <form id="form_edit_categoria" method="POST">
               <div class="dados_editar_categoria">
                  <div class="edit_categoria_inputs">
                     <div>
                        <label for="categoria_editar">Categoria:
                        <input type="text" class="categoria_editar" id="categoria_editar" name="categoria"/>
                     </label>
                     </div>
                     <div>
                        <label for="descricao_categoria">Descrição:</label>
                        <textarea type="text" rows="3" cols="31" class="descricao_categoria" id="descrica_categoria" name="descricao"/></textarea>
                     </div>
                     <input type="hidden" class="id_editar_categoria" />
                  </div>
               </div>
               <hr>  
               <button type="submit" class="btn_edit_categoria">Editar</button>
            </form>
            </div>
         </div>
         <div class="errors_editar_categoria" style="margin-top:10px"></div>
      </div>
   </div>
</div>