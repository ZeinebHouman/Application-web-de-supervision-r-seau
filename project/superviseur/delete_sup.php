<?php session_start() ?>
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
    <title>Supprimer un utilisateur</title>
</head>
<?php include_once('../box/header.php');
   
?>

    
    <div class="center">
        <h2>Supprimer un superviseur  </h2>
    <form action="Delete_sup.php" method="post" >
        <table>
        <tr><td><label>Selectionner un superviseur : </label></td><td>
        <select name="id">
        <?php 
         $requete=$connexion->prepare("
         SELECT *
         FROM superviseur
            ");
         $requete->execute();
         $resultat=$requete->fetchall();
            for($i=0;$i<sizeof($resultat);$i++)
            {
                
                echo "<option value=".$resultat[$i]['ID_USER']." >[".$resultat[$i]['ID_USER']."] ".$resultat[$i]['USERPSEUDO']."</option>";
            }
        ?>
        </select>
    
    </td></tr>
        </table>
        <input type="submit" name="Delete_sup" value="Valider" />
        <?php 
        if(isset($_POST['Delete_sup']))
        {
            if(isset($_POST['id']))
            {
            
            if(delete_sup($_POST['id'],$connexion))
              echo "<p class='ok' >lesuperviseur a été bien supprimé !</p><a href='../index.php'>Revenir à la liste des équipements</a>";

            else
              echo "<p class='no'>L'identifiant n'existe pas !</p>";

            
            }
            else
            {
                echo "<p class='no'>vérifiez les champs !</p>";
            }
        }

    
    ?>
    </form>
    </div>
</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="../Js/ScrollReveal.js"></script>
</html>