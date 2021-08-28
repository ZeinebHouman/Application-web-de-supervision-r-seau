
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
    <title>Modifier un utilisateur</title>
</head>
<body>
<?php 
include_once('../box/header.php');
?>

     
    
    </script>
<div class="center">
    <h2>Modifier un utilisateur</h2>
    <form action="update_user.php" method="post" enctype="multipart/form-data">
    <table>
    <tr><td><label>Cin :</label></td>
    <td><select name="id" >
        <?php 
            $str="
                SELECT *
                FROM utilisateur
                WHERE ROLE!='admin'
                ORDER BY USERPSEUDO";
                $requete=$connexion->prepare($str);
                $requete->execute();
                $resultat=$requete->fetchall();
                for($i=0;$i<sizeof($resultat);$i++){
                    echo "<option value='".$resultat[$i]['ID_USER']."'> [".$resultat[$i]['ID_USER']."] ".$resultat[$i]['USERPSEUDO']."</option>";
                }

        
        ?>
    </select></td>

</td></tr>
    <tr><td><label>Photo de l'Utilisateur :</label></td><td><input type="file" name="avatar" /></td></tr>
    <tr><td><label>Nom de l'Utilisateur :</label></td><td><input type="text" name="name" placeholder="Modifier le nom (optionnel)"></td></tr>
    <tr><td><label>Mot de passe :</label></td><td><input type="password"  name="pass"  placeholder="Modifier le mot de passe (optionnel)"></td></tr>
    </table>
    <button name="delete_pdp">Supprimer la photo de profil</button>
  
    <input type="submit" name="update_user" value="Valider" />
    <?php 
           if(isset($_POST['delete_pdp']) && file_exists("../avatar/".$_POST['id'].".jpg")) 
          {unlink("../avatar/".$_POST['id'].".jpg");
              echo "<p class='ok'>La photo de profil a été supprimer</p>";
          } 
          else if(isset($_POST['delete_pdp']) && !file_exists("../avatar/".$_POST['id'].".jpg"))
          {
            echo "<p class='no'>L'utilisateur n'a pas de photo de profil</p>";
          }
          
        $str="
        SELECT ID_USER
        FROM utilisateur;";
               
        $requete=$connexion->prepare($str);
        $requete->execute();
        $resultat=$requete->fetchall();
       /* for($i=0;$i<sizeof($resultat);$i++)
              {echo $resultat[$i]["ID_USER"]; 
              echo"<br>";}*/
           if(isset($_POST['update_user']))
           {
            if(isset($_FILES['avatar']) && $_FILES['avatar']['error']==0)   
            {add_avatar($_POST['id'],$_FILES['avatar'],"../avatar/")   ;
            echo "<p class='ok' > La photo a été bien modifiée !</p>";}
            if(isset($_POST['id']))
            {
               if(!empty($_POST['name']) && !empty($_POST['password']))
               {
             
               $i=0;
               while($i<sizeof($resultat) && $resultat[$i]["ID_USER"]!=$_POST['id'])
                       $i++;
               if($i<sizeof($resultat))
               {
                    update_user($_POST['name'],$_POST['password'],$_POST['id'],$connexion);
                   echo "<p class='ok'>l'equipement a été bien modifiée !</p><a href='../index.php'>Revenir à la liste des équipements</a>";
                }
                else 
                    echo "<p class='no'>L'adresse IP n'existe pas !</p>";
                    
               }
               else if(!empty($_POST['name']))
               {
                   $j=0;
                   while($j<sizeof($resultat) && $resultat[$j]["ID_USER"]!=$_POST['id'])
                           $j++;
                   if($j<sizeof($resultat))
                   {
                        update_name($_POST['name'],$_POST['id'],$connexion);
                       echo "<p class='ok'>Le nom de l'utilisateur a été bien modifié !</p><a href='../index.php'>Revenir à la liste des équipements</a>";
                    }
                    else 
                        echo "<p class='no'>L'identifiant n'existe pas !</p>";
               }
               else if(!empty($_POST['pass']))
               {
                   $i=0;
                   while($i<sizeof($resultat) && $resultat[$i]["ID_USER"]!=$_POST['id'])
                           $i++;
                   if($i<sizeof($resultat))
                   {
                        update_pass($_POST['pass'],$_POST['id'],$connexion);
                       echo "<p class='ok'>le mot de passe de l'utilisateur a été bien modifié !</p><a href='../index.php'>Revenir à la liste des équipements</a>";
                    }
                    else 
                        echo "<p class='no'>L'identifiant n'existe pas !</p>";
               }
               else
                   echo "<p class='no'> Vous n'avez rien changé ! </p><a href='../index.php'>Revenir à la liste des équipements</a>";
   
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