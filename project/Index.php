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

    <script src="Chart.min.js"></script>
    <title>Index</title>
</head>
<?php include("php/database.php");  ?>


<body>
<?php/* if(isset($_GET['loc'])){ 
            
            header("Refresh:300");
            
            check($_GET['loc'],$connexion);
                }*/
    ?>

<header id="header">
          
    <?php if($_SESSION['role']=='admin')
    {
        echo "<img id='menu_button'  src='img/defile.png'><div id='header_name'><h1 >
        
        
          Network Monitoring</h1>
        </div>
        ";
    }
    else
    {
        echo "<div id='header_name' style='margin-right:50%'>
        
        <h1>Network Monitoring</h1>
      </div>";
    }
 ?>
        
        <div id="menu">
        <a href="notification.php" ><img src="img/notif.svg" alt='notif' style="width:25px;" /><span class="badge"><?php $notif=check_number_notif($connexion,$_SESSION['id']); echo $notif;?></span></a>     

        <a href='index.php' style='margin-right:10px;'><img src='img/home.svg' alt='home' style='width:25px;'></a>
  
        <a href="Authentification.php"><img src='img/logout.svg' alt='log out' style='width:25px;'></a>
        </div>
</header>
<?php if($_SESSION['role']=='admin')
{
    echo "<nav id='nav'>
    
    <a href='superviseur/liste_sup.php'>consulter la liste des superviseurs</a>
    <br><a href='departement/liste_dep.php'><h2 >Liste des départements</h2></a>";
     show_departement_local($connexion); 
    
    echo "
          </nav>";
} 
?>


    
<div id="section">
<?php
$id=$_SESSION['id'];

?>

<?php if($_SESSION['role']=='admin' && !isset($_GET['loc'])) 
{
    include('utilisateur/liste_user.php');
    echo" <div class='notif'>";

    echo "<div><a href='#top'class='exit'>&#10006;</a><p> Bienvenue ".$_SESSION['name']." , vous êtes connecté(e) autant que ".$_SESSION['role']."</p></div>"; 
     echo" </div>";
  
} 
else if($_SESSION['role']=='admin' && isset($_GET['loc']))
{
    include('local/loc_information.php');
}
else
{
    include('local/loc_information.php');
    echo" <div class='notif'>";

    echo "<div><a href='#top'class='exit'>&#10006;</a><p> Bienvenue ".$_SESSION['name']." , vous êtes connecté(e) autant que ".$_SESSION['role']."</p></div>"; 
     echo" </div>";
}


?>

</div>   
<?php 
if(!isset($_GET['loc']) AND $_SESSION['role']=='superviseur'){
    $str="
    SELECT *
    FROM local
    WHERE ID_USER='".$_SESSION['id']."'";
    $requete1=$connexion->prepare($str);
    $requete1->execute();
    $resultat=$requete1->fetchall();
    if(sizeof($resultat)>0)
    {
        $_GET['loc']=$resultat[0]['REF_LOC'];
    }
}
if(isset($_GET['loc']))
   notification($_GET['loc'],$connexion); 

   if($_SESSION['role']=='admin' && isset($_GET['loc']) && $info['id']!=0 OR $_SESSION['role']=='superviseur')
   {
      ?>
        
<div id="chat">
<div style='display:flex;'>
    <img src="img/close.svg" id="close_butt"/>
    <?php if($_SESSION['role']=='admin' && isset($_GET['loc']))
    {
        $src=get_avatar($info['id'],"avatar/","img/"); 
        
echo"  <p id='name_client' style='font-size:14px;text-align:center;font-weight:bold;'>".$info['name']."</p><img src='$src' id='pdp_chat' />
    
    <div id='chat_info'>
    <p id='name_client' style='font-size:14px;text-align:center;font-weight:bold;'>Discutez avec ".$info['name']."</p> 
    </div>";}
    else
    {
        $src=get_avatar('0',"avatar/","img/"); 
        echo"  <p id='name_client' style='font-size:14px;text-align:center;font-weight:bold;'>Admin</p><img src='$src' id='pdp_chat' />
    
    <div id='chat_info'>
    <p id='name_client' style='font-size:14px;text-align:center;font-weight:bold;'>Discutez avec l'Admin</p>
    </div>";
    }
    ?>
</div>
<div id="message_content">
<?php
 
 if($_SESSION['role']=='admin' && isset($_GET['loc']))
 {
    $src=get_avatar($info['id'],"avatar/","img/"); 
    show_message($_SESSION['id'],$info['id'],$src);
  if(isset($_POST['message']))
        echo "<div ><p  class='send' >".$_POST['message']."</p></div>";
 }
 else
 {
    $src=get_avatar('0',"avatar/","img/"); 
    show_message($_SESSION['id'],'0',$src);
    if(isset($_POST['message']))
        echo "<div ><p  class='send' >".$_POST['message']."</p></div>";
 }
?>

</div>

<form <?php   
if($_SESSION['role']=='admin') 
echo" action='send.php?loc=".$_GET['loc']."&user=".$info['id']."'";
else if($_SESSION['role']=='superviseur') 
echo" action='send.php' ";   ?>  

            method="POST" class='form-send-msg'>
<textarea name="message"  id="message" placeholder="Ecrivez un message ..."></textarea>
<button name="envoyer" id="envoyer" class='btn-send-msg'  type='submit'><img src='img/envoyer.svg'/></button>
</form>
</div>


      <?php
   }

?>


</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="Js/ScrollReveal.js"></script>
<script src="jquery-3.4.1.js"></script>
<script src="Js/nav.js"></script>
<script src="Js/ajax.js"></script>



</html>