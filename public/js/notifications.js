$(function(){
    notifications();
})

function notifications(){
    $.ajax({
        url: "/notifications",
        type: 'GET',
        success: function(data){
    
            var estoque_baixo = data.produtos_count >= 1 ? true : false;
            if (estoque_baixo)
                $('.notifications').html("!").css({'background' : 'red', 'color' : 'white'})
            else
                $('.notifications').html("")
        }  
    })
}