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
    <title>Notification</title>
</head>
<?php include("php/database.php");  ?>


<body>


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
<div class="article" >
  
        <h2>Notification</h2>
<?php 
if($_SESSION['role']=='admin') 
{
    $str="
    SELECT *
    FROM equipement ,local,departement
    WHERE departement.REF_DEP=local.REF_DEP AND local.REF_LOC=equipement.REF_LOC AND (ETAT='warning' OR ETAT='down')
    ORDER BY local.REF_LOC 
    ";
    $requete1=$connexion->prepare($str);
    $requete1->execute();
    $resultat=$requete1->fetchall();
   /* echo"<pre>";
    print_r($resultat);
    echo"</pre>";*/
    
    echo"<table>";
    if(sizeof($resultat)>0)
    {
        $ref_lpred=-1;
        $ref_dpred=-1;
        for($i=0;$i<sizeof($resultat);$i++){
            if($resultat[$i]['ETAT']=='warning'){
                $img="<img src='img/fig2.png' alt='' />";
            }
            else
            {
                $img="<img src='img/fig3.png' alt='' />";
            }
            if($ref_dpred!=$resultat[$i]['REF_DEP'])
            {
                echo "<tr class='tr_dep'  ><td colspan='2'><b>".$resultat[$i]['NOM_DEP']."</b></td></tr>";
                $ref_dpred=$resultat[$i]['REF_DEP'];
            }
            if($ref_lpred!=$resultat[$i]['REF_LOC'])
            {
                echo "<tr class='tr_loc' ><td colspan='2'><b>".$resultat[$i]['NOM_LOC']."</b></td></tr>";
                $ref_lpred=$resultat[$i]['REF_LOC'];
            }
            echo"<tr><td>$img</td><td><p>Un probleme est survenu lors de la connexion à l'equipement :<b> ".$resultat[$i]['MODELE_EQUIP']." ".$resultat[$i]['REF_EQUIP']." </b></p></div></td></tr>";

        }
    }
} 
else if($_SESSION['role']=='superviseur'){
    $str="
    SELECT *
    FROM equipement
    WHERE (ETAT='warning' OR ETAT='down') AND ID_USER='".$_SESSION['id']."'";
    $requete1=$connexion->prepare($str); 
    $requete1->execute();
    $resultat=$requete1->fetchall();
    echo"<table>";
    if(sizeof($resultat)>0)
    {
        for($i=0;$i<sizeof($resultat);$i++){
            if($resultat[$i]['ETAT']=='warning'){
                $img="<img src='img/fig2.png' alt='' />";
            }
            else
            {
                $img="<img src='img/fig3.png' alt='' />";
            }
            echo"<tr><td>$img</td><td><p>Un probleme est survenu lors de la connexion à l'equipement :<b> ".$resultat[$i]['MODELE_EQUIP']." ".$resultat[$i]['REF_EQUIP']." </b></p></div></td></tr>";
        }
    }
}

?>



</div>

</div>   



</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="Js/ScrollReveal.js"></script>

<script src="jquery-3.4.1.js"></script>
<script src="Js/nav.js"></script>




</html>