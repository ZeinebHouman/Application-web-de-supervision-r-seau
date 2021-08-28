<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Style/Style.css" />
    <link rel="stylesheet" href="../Style/ClassStyle.css" />
    <link rel="stylesheet" href="../Style/IdStyle.css" />
    <link rel="stylesheet" href="../Style/BoxStyle.css" />
    <link rel="stylesheet" href="../Style/PoliceStyle.css" />
    <link rel="shortcut icon" type="image/x-icon" href="../icon2.png" />
    <title>Ajouter un local</title>
</head>
<body>
 <?php include_once('../box/header.php');

 ?>
    
    <div class="center">
    <h2>Ajouter un local :</h2>
   <?php echo"  <form action='Add_loc.php?dep=".$_GET['dep']."' method='post'>" ;?>
    <table>

    <tr><td><label>Nom du Local :</label></td><td><input type="text" placeholder="Saisir le nom du local ..." name="nom_loc"></td></tr>
    <tr><td><label>Nom du Superviseur :</label></td><td>
    <select name="ref_sup">
    <option  value="0">Selectionner un utilisateur</option>
    <?php 
    $requete1=$connexion->prepare("
    SELECT *
    FROM utilisateur
    " );
    $requete1->execute();
    $resultat=$requete1->fetchall();
            for($i=1;$i<sizeof($resultat);$i++)
            {
                if($resultat[$i]['ROLE']=='aucun')
                    echo "<option value=".$resultat[$i]['ID_USER'].">".$resultat[$i]['USERPSEUDO']."</option>";
                else
                echo "<option style='background-color:red;' value=".$resultat[$i]['ID_USER'].">".$resultat[$i]['USERPSEUDO']."</option>";
            }
    
    ?>
    </select>
    
    </td></tr>
    </table>
    <input type="submit" name="Add_loc" value="Valider" />
    <?php 
        if(isset($_POST['Add_loc'])){
            if(isset($_POST['nom_loc'])){
                $loc=search_ref_loc($connexion);
                if($_POST['ref_sup']>0)
                {
                $str="
                SELECT *
                FROM utilisateur
                WHERE ID_USER=".$_POST['ref_sup'];
                $requete3=$connexion->prepare($str);
                $requete3->execute();
                $resultat=$requete3->fetchall();
                
                add_loc($loc,$_GET['dep'],$_POST['ref_sup'],$_POST['nom_loc'],$connexion);
                
                }
                else
                {
                    add_loc($loc,$_GET['dep'],0,$_POST['nom_loc'],$connexion);
                }

                
                echo  "<p class='ok'>Le local a été bien ajouté ! </p>";
            }
            else
            {
                echo "<p class='no'>Vérifiez les champs ! </p>";
            }
            
        }
    
    ?>           
    </div> 
</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="../Js/ScrollReveal.js"></script>
</html>