function cashVerify(id)
{
    $.ajax({
        url:"/cash_verify/"+id,
        method: 'GET',
        success:function(data)
        {   
            if (data.message)
            {
                swal({
                    text: data.message,
                    icon: "warning"
               })
            } else {
                window.location.href = "/pdv";
            }
        }
    });
}