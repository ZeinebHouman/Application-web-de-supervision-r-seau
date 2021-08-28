(function($){


$('.btn-sup-user').on('click',function(e){
    e.preventDefault();
    var $a=$(this);
    var url=$a.attr('href');
    $.ajax(url,{
        success :function(){
           $a.parents('tr').fadeOut(); 
        }

    });
});



$('.form-send-msg').on('submit',function(e){
    e.preventDefault();
    var $form=$(this);
    $.post($form.attr('action'),$form.serializeArray())
    .done(function(data,text,jqxhr){
        var $height= $(window).height()+$(document).height()+$('#message_content').height();
        $('#message_content').append(jqxhr.responseText).fadeIn(2000).animate( { scrollTop: $height+=$(window).height()+$(document).height()+$('#message_content').height() },1500);
        $('#message').val("");
    })
    .fail(function(jqxhr){
        alert(jqxhr.responseText);
    })


    }); 
    
   

})(jQuery);