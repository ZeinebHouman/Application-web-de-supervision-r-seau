

/* verifie si les inputs ne contiennent pas des caracteres interdits */
function check_log(log)
{
    var tab=Array('*','/','<','>',',','@','#');
    for(i=0;i<log.length;i++)
    {
        for(j=0;j<tab.length;j++)
        {
            if(log[i]==tab[j])
                return false;
        }
    }
    return true;
}
/*Ajouter une couleur au input */
function log_color(login)
{
    if(!check_log(login.value))
    {
        login.style.border='0.5px solid red';
        login.style.backgroundColor='rgb(255,172,172)';
        return false;
    }
    else if(login.value.length<3)
    {
        login.style.border='0.5px solid orange';
        login.style.backgroundColor='rgb(255,198,140)';
        return false;
    }
    else
    {
        login.style.border='0.5px solid green';
        login.style.backgroundColor='rgb(159,255,159)';
        return true;
    }
}

function button_allow(log)
{
    var button=document.getElementById('login_check');
    if(!log_color(log))
    {
        button.disabled=true;
    }
    else
    {
        button.disabled=false;
    }

}


document.getElementById('pass').addEventListener('keyup',function(){
    var pass=document.getElementById('pass');
    button_allow(pass);
})

document.getElementById('login').addEventListener('keyup',function(){
    var login=document.getElementById('login');
    button_allow(login);
    console.log(login.value);
})

