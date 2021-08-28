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
    <title>Ajouter un superviseur</title>
</head>
<body>
<?php 
include_once('../box/header.php');

include_once('../php/database.php');
?>
<div class="center">
    <h2>Ajouter un superviseur</h2>
    <form action="Add_sup.php" method="post">
    <table>
    
    
    <?php 
    $requete1=$connexion->prepare("
    SELECT *
    FROM utilisateur
    WHERE ROLE='aucun'
    ORDER BY USERPSEUDO ");
    $requete1->execute();
    $resultat=$requete1->fetchall();
    if(sizeof($resultat)>0)
    {
        echo "<tr><td><label>Nom du Superviseur :</label></td><td><select name='ref_sup'>";
            for($i=0;$i<sizeof($resultat);$i++)
            {
    
                    echo "<option value=".$resultat[$i]['ID_USER'].">".ucfirst($resultat[$i]['USERPSEUDO'])."</option>";
            }
          echo "</select>";
    }
    else
    {
        echo "<center><p style='color:red;font-size:12px;'>aucun utilisateur n'est disponible pour le moment</p></center>";
    }
    
    
    ?>
    
    
    </td></tr>
    
        <?php 
        if(sizeof($resultat)>0)
        {
            echo "
            <tr><td><label>Local:</label></td><td>
            <select name='loc' >";
            $requete2=$connexion->prepare("
            SELECT *
            FROM departement
            ORDER BY NOM_DEP ");
            $requete2->execute();
            $resultat2=$requete2->fetchall();
            if(sizeof($resultat2)>0)
            {
            
                    for($i=0;$i<sizeof($resultat2);$i++)
                    {
            
                            echo "<option style='background-color:#00acee;font-weight:bold;'value='0'>".$resultat2[$i]['NOM_DEP']."</option>";
                            $str="
                            SELECT *
                             FROM local
                             WHERE ID_USER=0 AND REF_DEP=".$resultat2[$i]['REF_DEP']."
                             ORDER BY NOM_LOC";
                            $requete3=$connexion->prepare($str);
                              $requete3->execute();
                           $resultat3=$requete3->fetchall();
                           if(sizeof($resultat3)>0)
                           {
                            for($j=0;$j<sizeof($resultat3);$j++)
                            {
                                    echo "<option value=".$resultat3[$j]['REF_LOC'].">".$resultat3[$j]['NOM_LOC']."</option>";
                           
                            }
                         }
                    }
                
            }
            echo "</select>";
        }
      
      
        ?>
    </td></tr>
    </table>
   <?php if(sizeof($resultat)>0)
    {
        echo" <input type='submit' name='Add_sup' value='Valider' />";
    } ?>
    <?php
        if(isset($_POST['Add_sup'])){
            if(isset($_POST['ref_sup']) && isset($_POST['loc']) && $_POST['loc']>0)
            {
                if(!verif_sup_exist($_POST['ref_sup'],$connexion))
                {$str="
                SELECT *
                FROM utilisateur
                WHERE ID_USER=".$_POST['ref_sup'];
                $requete=$connexion->prepare($str);
                $requete->execute();
                $resultat=$requete->fetchall();
                if(sizeof($resultat)>0)
                  add_sup($resultat[0]['ID_USER'],$resultat[0]['USERPSEUDO'],$resultat[0]['PASSWORD'],$_POST['loc'],$connexion);
                  echo "<p class='ok' >le superviseur a été bien ajouté !</p>";}
                else
                {
                    echo "<p class='no'>Cette utilisateur est déja un superviseur</p>";
                }
            }
            else
            {
                echo "<p class='no' >Vérifiez les champs !</p>";
            }
        }
    
    
    
    ?>    
</div>
</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="../Js/ScrollReveal.js"></script>
</html>