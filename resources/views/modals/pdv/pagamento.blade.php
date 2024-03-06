<div class="modal fade" id="pagamento_modal" tabindex="-1" role="dialog" aria-labelledby="pagamento_title" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="pagamento_title"><i><b>FORMA DE PAGAMENTO</b></i></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="border:none; border-radius:10px">
            <span aria-hidden="true" style="font-weight: bold">X</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="pagamento_pdv">
            <select id="forma_pagamento" class="form-control forma_pagamento">
              <option value="01">Dinheiro</option>
              <option value="02">Cheque</option>
              <option value="03">Cartão de Crédito</option>
              <option value="04">Cartão de Débito</option>
              <option value="05">PIX</option>
            </select>
            <div class="valor_desconto">
              <input type="text" class="form-control" id="valor_recebido" placeholder="A RECEBER" autocomplete="off">
              <input type="text" class="form-control" id="desconto" placeholder="DESCONTO%" autocomplete="off" maxlength="3">
              <input type="hidden" class="form-control" id="valor_desconto" autocomplete="off">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="result_pagamento">
            <label for="troco" style="position: relative">
                <h2>TROCO(R$)</h2> 
                <input type="text" id="troco" autocomplete="off">
                <div id="preloader_troco"><img src="{{asset('img/preloader.gif')}}" alt="preloader..."></div>
            </label>
            <label for="total_pagamento" style="position: relative">
                <h2>TOTAL(R$)</h2>
                <input type="text" id="total_pagamento" autocomplete="off">
            </label>
          </div>
          <div class="btn_finalizar_venda">
            <button type="button" class="btn btn-success" id="disabled_btn_finaliza_venda" onclick="finalizarVenda()"><i class="fas fa-dollar-sign"></i> Finalizar venda</button>
          </div>
        </div>
      </div>
    </div>
  </div>