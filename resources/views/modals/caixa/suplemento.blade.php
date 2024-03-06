<div class="modal fade" id="caixa_suprimento_modal" tabindex="-1" role="dialog" aria-labelledby="caixa_suprimento_title" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content mobile_modal_content_suprimento" style="width: 582px;">
          <div class="modal-header" style="background:teal; color:#FFF;">
              <h5 class="modal-title"><b>Suprimento</b></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <div class="suprimento_caixa">
                <div class="suprimento_caixa_dados">
                    <div id="preloader_suprimento"><img src="{{asset('img/preloader.gif')}}" alt=""></div>
                    <div class="errors_suprimento_caixa"></div>
                    <h5 id="caixa_suprimento_title" style="background: #2b5a7a; padding: 5px; color:#FFF;"><b></b></h5>
                    <hr>
                    <div class="suprimento_caixa_body_mobile" style="display:flex; align-items:center; justify-content:space-between; margin: 15px; text-align: center;">
                        <div>
                            <img src="{{asset('img/img_suprimento.png')}}" alt="img_suprimento" style="width:125px;">
                        </div>
                        <div class="suprimento_caixa_dados_mobile" style="display: flex; flex-direction: row;">
                            <input type="hidden" class="numero_caixa_suprimento">
                            <div style="display: flex; flex-direction:column">
                                <label for="finalizadora" style="font-weight:100;"><b>Finalizadora:</b></label>
                                <input type="text" class="finalizadora" id="finalizadora" value="01 - DINHEIRO" disabled />
                                <label for="valor_suprimento" style="font-weight:100;"><b>Valor do suprimento(R$):</b></label>
                                <input type="text" class="valor_suprimento"  id="valor_suprimento" autocomplete="off"/>
                            </div>
                            <div style="display: flex; flex-direction:column">
                                <label for="saldo_atual_caixa" style="font-weight:100;"><b>Saldo atual(R$):</b></label>
                                <input type="text" class="saldo_atual_caixa" id="saldo_atual_caixa" disabled/>
                                <label for="saldo_apos_suprimento" style="font-weight:100;"><b>Saldo após suprimento(R$):</b></label>
                                <input type="text" class="saldo_apos_suprimento" id="saldo_apos_sangria" disabled/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <div style="display: flex; align-items:center; justify-content: space-between; width:100%">
                <div>
                    <span class="erro_valores_suprimento" style="color: red; font-weight:900"></span>
                </div>
                <button class="btn_suprimento" style="border: none; padding: 10px; background: #0d643c; color: #FFF; text-transform: uppercase; font-family: monospace; font-weight: 700; margin-bottom: 5px;">Confirmar</button>
            </div>
          </div>
      </div>
    </div>
  </div>