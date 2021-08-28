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
    <form action="update_loc.php" method='post'> 
    <table>
    <tr><td><label>Référence du Département :</label></td><td><input type="text" name="ref_dep" value=<?php 
    if(isset($_GET['dep']))
    echo $_GET['dep'];?>></td></tr>
    <tr><td><label>Référence du Local :</label></td><td><input type="text" name="ref_loc"></td></tr>
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
    <?php 
     $str2="
     SELECT *
     FROM local;";
     $requete2=$connexion->prepare($str2);
     $requete2->execute();
     $resultat2=$requete2->fetchall();

        if(!empty($_POST['update_loc']))
        {
            if(!empty($_POST['ref_loc']) && !empty($_POST['nom_loc']))
            {
                $j=0;
                while($j<sizeof($resultat2) && $resultat2[$j]["REF_LOC"]!=$_POST['ref_loc'])
                        $j++;
                if($j<sizeof($resultat2))
                {
                update_loc_name($_POST['nom_loc'],$_POST['ref_loc'],$connexion);

                $str1="
                SELECT *
                FROM superviseur;";
                $requete=$connexion->prepare($str1);
                $requete->execute();
                $resultat1=$requete->fetchall();

                $str="
                SELECT *
                FROM utilisateur
                WHERE ID_USER=".$_POST['ref_sup'];
                $requete3=$connexion->prepare($str);
                $requete3->execute();
                $resultat=$requete3->fetchall();

              

                $i=0;
                while($i<sizeof($resultat1) && $_POST['ref_loc']!=$resultat1[$i]['REF_LOC'])
                    $i++;      
                if($i<sizeof($resultat1))  // il existe un sup dans le local 
                {    
                    delete_sup($resultat1[$i]['ID_USER'],$connexion);  //supp le sup dans le local
                    if($resultat[0]['ROLE']=='aucun')
                   { 
                    update_loc_user($_POST['ref_loc'],$_POST['ref_sup'],$connexion);
                     echo  "<p style='color:green;font-family:arial;font-size:12px'>Le local a été bien modifié ! </p>";}
                    else // if($resultat[0]['ROLE']=='superviseur')
                    {          
                        delete_sup($_POST['ref_sup'],$connexion);
                        update_loc_user($_POST['ref_loc'],$_POST['ref_sup'],$connexion);
                        echo  "<p style='color:green;font-family:arial;font-size:12px'>Le local a été bien modifié* ! </p>";
                     }
               }
               else // le local est vide 
               {
                if($resultat[0]['ROLE']=='aucun')
                { update_loc_user($_POST['ref_loc'],$_POST['ref_sup'],$connexion);
                 echo  "<p style='color:green;font-family:arial;font-size:12px'>Le local a été bien modifié ! </p>";}
                 else //if($resultat[0]['ROLE']=='superviseur')
                 {          
                     delete_sup($_POST['ref_sup'],$connexion);
                     update_loc_user($_POST['ref_loc'],$_POST['ref_sup'],$connexion);
                     echo  "<p style='color:green;font-family:arial;font-size:12px'>Le local a été bien modifié* ! </p>";
                  }

               }

                   
                }
                else 
                echo "<p style='color:red'>Le local n'existe pas !</p>";

          }
          else if(!empty($_POST['nom_loc']))
          {
            update_loc_name($_POST['nom_loc'],$_POST['ref_loc'],$connexion);
            echo  "<p style='color:green;font-family:arial;font-size:12px'>Le nom du local a été bien modifié ! </p>";

          }
          else if((!empty($_POST['ref_loc'])))
          {
            $j=0;
            while($j<sizeof($resultat2) && $resultat2[$j]["REF_LOC"]!=$_POST['ref_loc'])
                    $j++;
            if($j<sizeof($resultat2))
            {

            $str1="
            SELECT *
            FROM superviseur;";
            $requete=$connexion->prepare($str1);
            $requete->execute();
            $resultat1=$requete->fetchall();

            $str="
            SELECT *
            FROM utilisateur
            WHERE ID_USER=".$_POST['ref_sup'];
            $requete3=$connexion->prepare($str);
            $requete3->execute();
            $resultat=$requete3->fetchall();

          

            $i=0;
            while($i<sizeof($resultat1) && $_POST['ref_loc']!=$resultat1[$i]['REF_LOC'])
                $i++;      
            if($i<sizeof($resultat1))  // il existe un sup dans le local 
            {    
                delete_sup($resultat1[$i]['ID_USER'],$connexion);  //supp le sup dans le local
                if($resultat[0]['ROLE']=='aucun')
               { 
                 update_loc_user($_POST['ref_loc'],$_POST['ref_sup'],$connexion);
                 echo  "<p style='color:green;font-family:arial;font-size:12px'>Le local a été bien modifié ! </p>";
                 echo  "<p style='color:green;font-family:arial;font-size:12px'>L'utilisateur ". $resultat[0]['USERPSEUDO']. " a été bien modifié* ! </p>";}
                else // if($resultat[0]['ROLE']=='superviseur')
                {          
                    delete_sup($_POST['ref_sup'],$connexion);
                    update_loc_user($_POST['ref_loc'],$_POST['ref_sup'],$connexion);
                    echo  "<p style='color:green;font-family:arial;font-size:12px'>Le local a été bien modifié* ! </p>";
                    echo  "<p style='color:green;font-family:arial;font-size:12px'>Le superviseur ". $resultat[0]['USERPSEUDO']. " a été bien modifié* ! </p>";
                 }
           }
           else // le local est vide 
           {
            if($resultat[0]['ROLE']=='aucun')
            {  update_loc_user($_POST['ref_loc'],$_POST['ref_sup'],$connexion);
             echo  "<p style='color:green;font-family:arial;font-size:12px'>Le local a été bien modifié ! </p>";
             echo  "<p style='color:green;font-family:arial;font-size:12px'>L'utilisateur ". $resultat[0]['USERPSEUDO']. " a été bien modifié* ! </p>";
            }
             else //if($resultat[0]['ROLE']=='superviseur')
             {          
                 delete_sup($_POST['ref_sup'],$connexion);
                 update_loc_user($_POST['ref_loc'],$_POST['ref_sup'],$connexion);
                 echo  "<p style='color:green;font-family:arial;font-size:12px'>Le local a été bien modifié* ! </p>";
                 echo  "<p style='color:green;font-family:arial;font-size:12px'>Le superviseur ". $resultat[0]['USERPSEUDO']. " a été bien modifié* ! </p>";
              }

           }
          }
          else 
          echo "<p style='color:red'>Le local n'existe pas !</p>";
        }


        else 
         echo "<p style='color:red'>vérifiez les champs !</p>";
            
       }
       
    
    
    ?>           
    </div> 
</body>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="../Js/ScrollReveal.js"></script>
</html>