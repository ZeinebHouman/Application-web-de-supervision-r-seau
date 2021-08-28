<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Style/Style.css" />
    <link rel="stylesheet" href="Style/ClassStyle.css" />
    <link rel="stylesheet" href="Style/IdStyle.css" />
    <link rel="stylesheet" href="Style/BoxStyle.css" />
    <link rel="stylesheet" href="Style/PoliceStyle.css" />
    <link rel="shortcut icon" type="image/x-icon" href="icon2.png" />
 
    <title>Login</title>
</head>
<body style='background-color:white;'>
 <header style="box-shadow: none;background-color:transparent;">
        <div>
            <h1>Network Monitoring</h1>
        </div>
     
</header>
   <?php require_once('php/database.php') ?>
<div id="img_auth" id="">
<div id="particles-js">
<center>
    <h1 id="welcome" >Bienvenue </h1>
    

</center>
</div>
</div>
<div id="log_content">
    <center><a href="#description"><img id="fleche" src="img/fleche.png" /></a></center>
<div id="description"><p>Bienvenue sur Network Monitoring<br>Connectez-vous pour pouvoir utiliser l'application.</p></div>
<div id="login_box" >

        <h1>Se connecter</h1>
        <div style="margin-left:25px;">
        <form action="Authentification.php" method="POST" >
            <input type="text" name="username" autocomplete="off"   id="login" placeholder="Saisir votre Login ..." /><br />
            <input type="password" name="password" autocomplete="off"  id="pass" placeholder="Saisir votre Mot de passe..."/><br />
            <input type="radio" name="role"  value="admin" checked><label>Administrateur</label>
            <input type="radio" name="role"  value="superviseur"><label>Superviseur</label>
            <input type="submit" name="log" value="Se connecter" id="login_check" />
        </form>
        <?php 
if(sizeof($_SESSION)>0)
{
    $msg="deconnecte(e) \n";
    write_logs($_SESSION['id'],$_SESSION['name'],$msg,"log/",$connexion);
    session_destroy();
    echo "<p style='color:green;font-family:arial;font-size:12px;'>Vous etes bien déconnecté(e)</p>";
    
}

?>
        <?php

if (isset($_POST['role']) && isset($_POST['username']) && isset($_POST['password']))
{
    $role=$_POST['role'];
    $name=$_POST['username'];
    $id=check_log($name,$_POST['password'],$role,$connexion);
    if($id>=0)
    {
        $_SESSION['name']=$name;
        $_SESSION['role']=$role;
        $_SESSION['id']=$id;

        
        $msg="connexion \n";
    write_logs($_SESSION['id'],$_SESSION['name'],$msg,"log/",$connexion);
        header('Location:index.php');
    }
    else
    {
        echo "<p style='color:red;font-size:12px;'>connexion echoué </p>";
        session_destroy();
    }
}

?>
</div>
    </div>


    <div class="wave wave1"></div>
<div class="wave wave2"></div>
<div class="wave wave3"></div>
<div class="wave wave4"></div>
</div>

</body>
<script src="Js/login_script.js"></script>
<script src="jquery-3.4.1.js"></script>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="Js/ScrollReveal.js"></script>
<script src="Js/particles.min.js"></script>
<script src="Js/stats.js"></script>
<script src="Js/app.js"></script>
</html>