//SCROLL REVEAL
const sr= ScrollReveal();


sr.reveal('#login_box',{
    origin:'bottom',
    distance:'100px',
    duration:2000,
    reset:true,
    delay:1300

});

sr.reveal('#description',{
    origin:'left',
    distance:'100px',
    duration:2000,
    reset:true,
    delay:500


});


sr.reveal('nav ',{
    origin:'left',
    distance:'50px',
    duration:2000,
    delay:500
});
    
sr.reveal('#welcome ',{
    origin:'top',
    distance:'50px',
    duration:2000,
    reset:true
});

sr.reveal('#description',{
    origin:'left',
    distance:'100px',
    duration:2000,
    reset:true,
    delay:500


});
/*sr.reveal('.article',{
    origin:'top',
    distance:'100px',
    duration:1000,
    reset:true


});*/

sr.reveal('#fleche',{
    origin:'top',
    distance:'500px',
    scale:0.5,
    duration:1200,
    reset:true,
    delay:1300


});

function desapear(){
    setTimeout(function(){
        $(".notif div").animate({opacity:"0"},4000);},10000 );

}

ScrollReveal().reveal('.article', {
    origin:'left',
    duration:500,
    distance:"100px",
    interval: 500 });

ScrollReveal().reveal('.notif div', {
    origin:'right',
    duration:500,
    distance:"100px",
    delay: 600,
    interval: 1000 ,
    afterReveal : desapear});


      