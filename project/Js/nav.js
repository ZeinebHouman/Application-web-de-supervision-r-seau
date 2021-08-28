
$(document).ready(function(){
 
  $("#section").click(function(){
    $("#section").css( "filter","blur(0px)");
    $("#section").css( "left","15%");
    $("nav div,nav a,nav h2").animate({opacity:"0"});
    $("nav").css("background-color","transparent").css("border","none"); //#00acee
    $("nav").css("width","0");
    $("#header_name").animate({left:"0"},250);
  });
});


$("#menu_button").click(function(){
  $("nav").css("visibility","visible");
  $("#section").css( "filter","blur(2px)");
  $("#section").css( "left","25%");
  $("nav div,nav a,nav h2").animate({opacity:"1"},"fast");
  $("nav").css("background-color","#4dccff");
  $("nav").css("width","300px");
  $("#header_name").animate({left:"300px"},250);


});



window.onscroll=function(){
  scrollfunction()
};

function scrollfunction(){
  if(document.body.scrollTop>50 || document.documentElement.scrollTop>50){
    document.getElementById("header").style.height="0";
    document.getElementById("header").style.opacity="0";
    $("nav div,nav a,nav h2").css("opacity","0");
    $("nav").css("opacity","0");
    $("#section").css( "filter","blur(0px)");
    $("nav").css("width","0");
    $("nav").css("background-color","transparent").css("border","none");
    if($("nav").width()>0)
    {
      
      $("#header_name").css("left","0");
    }
    $(".notif").css("opacity","0");
  }
  else
  {
  
    document.getElementById("header").style.height="auto";
    document.getElementById("header").style.opacity="1";
    $("nav").css("opacity","1");
    
   
    
  }

}

$(".exit").click(function(){
  $(this).parent().fadeOut(100);

});


$(document).ready(function(){
  $(".ping").click(function(){

    // $('html, body').css("scrollTop", '0px');
    document.documentElement.scrollTop = 0;
    setTimeout(function(){
      
      $('<div id="loader"><img src="img/puff.svg" width="100" alt=""><p>Veuillez patientez ...</p>').insertAfter("#section");
    ScrollReveal().reveal('#loader', {
      origin:'top',
      duration:1000,
      distance:"1000px",
      interval: 500 });
    $("body").css("overflow","hidden");
    
   $(this).hide();




},500);

});

  $("#close_butt").click(function(){
    $("#chat form").hide();
    $("#message_content").hide();
    $("#chat").animate({height:"45px"});
    $("#chat_info").css("visibility","visible");
    $(this).hide();
    $("#name_client").hide();
    
   $("#pdp_chat").show();
   $("#pdp_chat").css("width","30px").css("height","30px");
  });

  $("#chat_info").click(function(){
    $("#chat form").show();
    $("#message_content").show();
    $("#chat").animate({height:"330px"});
    $("#close_butt").show();
    $(this).css("visibility","hidden");
    $("#name_client").show();
  
    $("#pdp_chat").hide();
  });
  
  

});
