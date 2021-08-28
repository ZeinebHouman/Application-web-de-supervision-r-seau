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
    <title>Liste des superviseurs</title>
</head>
<body>
<?php 
include_once('../box/header.php');

include_once('../php/database.php');
?>


    <div id="section">
        <div class="article">
        <h2>Superviseurs :</h2>
            <div class="options">
                <a href="Add_sup.php">Ajouter</a>
                 <a href="delete_sup.php">Supprimer</a>
             </div>
             <?php show_sup_table($connexion);?>
        </div>
    </div>
</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="../Js/ScrollReveal.js"></script>
</html>