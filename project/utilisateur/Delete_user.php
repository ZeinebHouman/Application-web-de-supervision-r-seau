<?php
if(isset($_GET['id']))
    {
        include('../php/database.php');
        if(delete_user($_GET['id'],$connexion) )
        {   
              delete_sup($_GET['id'],$connexion);
              echo "<p class='ok'>l'identifiant a été bien supprimé de la table !</p><a href='../index.php'>Revenir à la liste des équipements</a>";
        }
        else
              echo "<p class='no'>L'identifiant n'existe pas !</p>";
}
else
    {
                echo "<p class='no'>vérifiez les champs !</p>";
    }
header('location:../index.php');
?>