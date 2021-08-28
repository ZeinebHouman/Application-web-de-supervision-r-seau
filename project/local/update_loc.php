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
    <title>Modifier un local</title>
</head>
<body>
 <?php include_once('../box/header.php');

 ?>
    
    <div class="center">
    <h2>Modifier un local :</h2>
    <form action=<?php echo "update_loc.php?dep=".$_GET['dep']; ?> method='post'> 
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
    </select></td></tr>

    <tr><td><label>Nom du Local :</label></td><td><input type="text" name="nom_loc"></td></tr>
    <tr><td><label>Nom du Superviseur :</label></td><td>
    <select name="ref_sup">
    <?php 
    $requete1=$connexion->prepare("
    SELECT *
    FROM utilisateur
    WHERE utilisateur.ROLE!='admin' ;");
    $requete1->execute();
    $resultat=$requete1->fetchall();
            for($i=0;$i<sizeof($resultat);$i++)
            {
    
                    echo "<option value=".$resultat[$i]['ID_USER'].">".$resultat[$i]['USERPSEUDO']."</option>";
            }
    
    ?>
    </select>
    
    </td></tr>
    </table>
    <input type="submit" name="update_loc" value="Valider" />
        </form>
    <?php 
        if(isset($_POST['update_loc']))
        {
            if(isset($_POST['ref_loc']))
            {
                if(strlen($_POST['nom_loc'])!=0 && isset($_POST['ref_sup']) )
                { 
                update_loc_name($_POST['nom_loc'],$_POST['ref_loc'],$connexion);
                update_loc_user($_POST['ref_loc'],$_POST['ref_sup'],$connexion);
                  }
                   else if(isset($_POST['nom_loc']) && strlen($_POST['nom_loc'])!=0)
               {
                update_loc_name($_POST['nom_loc'],$_POST['ref_loc'],$connexion);
                }
                else if(isset($_POST['ref_sup']))
                {
                    update_loc_user($_POST['ref_loc'],$_POST['ref_sup'],$connexion);
               /*     $str="
                    SELECT *
                    FROM utilisateur
                    WHERE ID_USER=".$_POST['ref_sup'];
                    $requete1=$connexion->prepare($str);
                    $requete1->execute();
                    $resultat=$requete1->fetchall();
                    add_sup($_POST['ref_sup'],$resultat[0]['USERPSEUDO'],$resultat[0]['PASSWORD'],$_POST['ref_loc'],$connexion);
            */
                    echo "<p class='ok' >superviseur modifié !</p>";
                }
                else
                {
                    echo "<p class='no' >erreur</p>";
                }


            }
        }
    
    
    ?>
    </div> 
</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="../Js/ScrollReveal.js"></script>
</html>