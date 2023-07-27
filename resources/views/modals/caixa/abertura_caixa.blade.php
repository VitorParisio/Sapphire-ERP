<div class="modal fade" id="abertura_modal" tabindex="-1" role="dialog" aria-labelledby="abertura_title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="abertura_title"><i><b>Abertura do caixa</b></i></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="abertura_caixa">
                <div class="abertura_caixa_dados">
                    <div class="errors_abertura_caixa"></div>
                    <h5 style="background: #2b5a7a; padding: 5px; color:#FFF;"></h5>
                    <hr>
                    <div style="display:flex; align-items:center; justify-content:space-around">
                        <div>
                            <input type="hidden" class="numero_caixa">
                            <label for="valor_abertura_caixa" style="font-weight:100;"><b>Valor de fundo(R$):</b></label>
                            <input type="text" class="valor_abertura_caixa">
                        </div>
                        <div>
                            <button class="abre_caixa" style="border: none; padding: 10px; background: #0d643c; color: #FFF; text-transform: uppercase; font-weight: 700; margin-bottom: 5px;"><i class="fas fa-cash-register"></i>&nbspAbrir caixa</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>