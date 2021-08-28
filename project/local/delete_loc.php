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
    <title>Supprimer un local</title>
</head>
<body>
 <?php include_once('../box/header.php');

 ?>
    
    <div class="center">
    <h2>Supprimer un local :</h2>
    <form action=<?php echo "delete_loc.php?dep=".$_GET['dep']; ?> method='post'> 
    <table>
    
    <tr><td><label>Référence du Local :</label></td>
    <td><select name="ref_loc" >
        <?php 
            $str="
                SELECT *
                FROM local
                WHERE REF_DEP=".$_GET['dep']."
                ORDER BY NOM_LOC";
                $requete=$connexion->prepare($str);
                $requete->execute();
                $resultat=$requete->fetchall();
                for($i=0;$i<sizeof($resultat);$i++){
                    echo "<option value='".$resultat[$i]['REF_LOC']."'>".$resultat[$i]['NOM_LOC']."</option>";
                }

        
        ?>
 </table>
    <input type="submit" name="delete_loc" value="Valider" />
        </form>
    <?php 
        if(isset($_POST['delete_loc']))
        {
            if(isset($_POST['ref_loc']))
            {
                
                if(delete_loc($_POST['ref_loc'],$connexion) )      
                     
                    echo "<p class='ok'>Le local a été bien supprimé de la table !</p><a href='../index.php'>Revenir à la liste des équipements</a>";
                
                   else

                     echo "<p class='no'>L'identifiant n'existe pas !</p>";
       
                   
            } 
             else
             {
                 echo "<p class='no'>vérifiez les champs !</p>";
             }
         }
       