notifications();

function notifications(){
    $.ajax({
        url: "/notifications",
        type: 'GET',
        dataType: 'JSON',
        success: function(data){
    
            var estoque_baixo = data.produtos_count >= 1 ? true : false;
            
            if (estoque_baixo)
            {
                $('.notifications').text(" ");
                $('.list_notifications > .estoque_baixo_msg').html(data.estoque_baixo_msg);
                $('.list_notifications > .lista_produtos_estoque_baixo').html(data.lista_produtos_estoque_baixo);
                $('.estoque_baixo_modal').css('display', 'block'); 
                $('.ir_lista_produtos_estoque_baixo').css('display', 'block');
            }
            else {
                $('.notifications').html("");
                $('.list_notifications > .estoque_baixo_msg').html("");
                $('.list_notifications > .lista_produtos_estoque_baixo').html("");
                $('.estoque_baixo_modal').css('display', 'none');
                $('.ir_lista_produtos_estoque_baixo').css('display', 'none');
                $('.list_notifications > .lista_produtos_estoque_baixo').html(data.lista_produtos_estoque_baixo);
            }      
        }  
    })
}