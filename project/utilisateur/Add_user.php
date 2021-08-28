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
    <title>Ajouter un utilisateur</title>
</head>
<body>
<?php 
include_once('../box/header.php');


?>
<div class="center">
    <h2>Ajouter un utilisateur</h2>

    <form action="Add_user.php" method="post" enctype="multipart/form-data">
    <table>
    <tr><td><label>Photo de l'Utilisateur :</label></td><td><input type="file" name="avatar" /></td></tr>
    <tr><td><label>Nom de l'Utilisateur :</label></td><td><input type="text" name="name" placeholder="Saisir le nom ici..."></td></tr>
    <tr><td><label>Mot de passe :</label></td><td><input type="password" name="pass" placeholder="Saisir le mot de passe ici..."></td></tr>
    <tr><td><label>cin :</label></td><td><input type="text" name="id" placeholder="Saisir le cin ici..."></td></tr>
    </table>
    <input type="submit" name="add_user" value="Valider" />
    <?php 
        if(isset($_POST['add_user'])){
            if(isset($_POST['name']) && isset($_POST['pass']) && isset($_POST['id'])){
                if(!verif_user_exist($_POST['id'],$connexion))
               { 
                if(isset($_FILES['avatar']) && $_FILES['avatar']['error']==0)   
                   { add_avatar($_POST['id'],$_FILES['avatar'],"../avatar/")   ;
                echo "<p class='ok'> La photo a été bien importée ! </p>";}
                else
                    echo "<p class='no'>aucune photo n'a pas été importée</p>";
                add_user($_POST['id'],$_POST['name'],$_POST['pass'],$connexion);
                echo  "<p class='ok' >L'utilisateur a été bien ajouté ! </p>";}
                else{
                    echo  "<p class='no' >L'utilisateur existe déja ! </p>";}
                }
            
            else
            {
                echo "<p class='no'>Vérifiez les champs ! </p>";
            }
        }
    
    ?>
    </form>
</div>
</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="../Js/ScrollReveal.js"></script>
</html>