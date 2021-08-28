
<?php if($_SESSION['role']=='admin'){
    if(isset($_POST['chosen']))
    {
        update_loc_user($_GET['loc'],$_POST['ref_sup'],$connexion);
    }
 echo "<div class='article'>";
     $info=get_loc_info($_GET['loc'],$connexion);
     $src=get_avatar($info['id'],"avatar/","img/");
    echo"<h2>Information</h2>
    <fieldset>
    <legend><img class='pdp_info' src='$src' /></legend>
        <p><b>Departement : </b> [".$info['ref_dep']."]  ".$info['nom_dep']."</p>
        <p><b>Local :</b> ".$info['nom_loc']."</p>
        <p><b>Superviseur :</b>  ";
    if($info['id'] !=0)
       echo "[".$info['id'] ."]  ".$info['name'];
    else
      {echo "Aucun";
        
    }
    ?>
    <form action=<?php echo "index.php?loc=".$_GET['loc']; ?> method='post'> 
    <?php
       echo"
            <select name='ref_sup'>";
          
    $requete1=$connexion->prepare("
    SELECT *
    FROM utilisateur
    WHERE utilisateur.ROLE!='admin' 
    ORDER BY ID_USER");
    $requete1->execute();
    $resultat=$requete1->fetchall();
            for($i=0;$i<sizeof($resultat);$i++)
            {
    
                    echo "<option value=".$resultat[$i]['ID_USER']."> [".$resultat[$i]['ID_USER']."] ".$resultat[$i]['USERPSEUDO']."</option>";
            }
    
    
            
           echo" </select>
            <input type='submit' name='chosen' value='Modifier'>
            </from>";
    echo  "</p>
        
    </fieldset></div>" ;}
    
    ?>
    <div class="article">
    
    <h2>Equipement</h2>
 

        <?php if($_SESSION['role']=='admin'){
            echo "<center>
            <div class='options'>
                <a href='equipement/Add_equip.php?loc=".$_GET['loc']."'>Ajouter</a>
                <a href='equipement/maj_equip.php?loc=".$_GET['loc']."'>Modifier</a>
                <a href='equipement/Delete_equip.php?loc=".$_GET['loc']."'>Supprimer</a>
            </div>
            </center>  ";
        } ?>
        
        
        <?php 
        
        if($_SESSION['role']=='superviseur')
            show_table_for_sup($id,$connexion);
        else if($_SESSION['role']=='admin' && isset($_GET['loc']))
        {
            
            $id=show_table_for_adm($_GET['loc'],$connexion);
        }
            
        if(isset($_POST['Adresse_ip'])){
            $etat=recheck($_POST['Adresse_ip'],$connexion);
            $msg="Verification de l'equipement ;".$_POST['Adresse_ip']." ;$etat\n";
            write_logs($_SESSION['id'],$_SESSION['name'],$msg,"log/",$connexion);
        }
       
        
        ?>
     

    </div>
    <div class="article" >
  
        <h2>Statistiques</h2>
    
    <?php 
    if(isset($_GET['loc']))
    {
        $data=chart_js($_GET['loc'],$connexion); 
        $loc=$_GET['loc'];
    }
    else
    {
        $data=chart_js_sup($id,$connexion); 
    }
   
    if(strcmp($data,"[0,0,0]")!=0)
    {
      echo"  
   <div id='stat'>
  <div class='stat'>
  <center> ";
      if($_SESSION['role']=='admin')
              echo "<img src='graph.php?loc=$loc'/>";
      else
              echo "<img src='graph.php?id=$id'/>";  
 echo"

</center>
    </div>
    <div class='stat'>     
    <center>  
            
            <canvas id='myChart' width='10' ></canvas>
    </center>

    </div>


</div>

    
    ";
    }
    else
    {
        echo "<center><p>aucun graphe à afficher pour le moment</p></center>";
    }
    
    ?>
    </div> 
    <script >


var ctx = document.getElementById('myChart').getContext('2d');

var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [ 'Up', 'Warning','Down'],
        datasets: [{
            data: <?php echo "$data"; ?>,
            backgroundColor: [
                
                'mediumseagreen',
                'orange',
                'red'
            ],
            borderColor: [
                
                'rgba(181, 230, 29, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(255, 99, 132, 1)'
            
            ],
            options: {
                    maintainAspectRatio: false,
                },
            borderWidth: 1
        }]
    },
    
});

</script>

<?php if($_SESSION['role']=='admin')
{
    $file_name="log/".$_GET['loc'].".csv";
}   
else{
    $loc=get_loc($_SESSION['id'],$connexion);
    $file_name="log/".$loc.".csv";
}

    echo"
    <div class='article' >
<h2>Historique</h2>";


if(file_exists("$file_name"))
        { 
                $f=fopen("$file_name","r");
                
                echo "<center>
                <form action='' method='POST' >
                <select name='choose_date'>";
                $date=""; 
                while($tab=fgetcsv($f,1024,";")){

                    if(strcmp($date,$tab[0])!=0)
                    {$date=$tab[0];
                    echo "<option value=".$tab[0]." >".$tab[0]."</option>";}
                }
                fclose($f);
                echo "</select>
                <input type='submit' name='search_date' value='Rechercher'/>
                </form></center>";
        }
        if(isset($_POST['search_date']))
              get_logs($file_name,$_POST['choose_date']) ;
        else
         echo "<center><p>Vous n'avez selectionné aucune date d'historique</p></center>";
      


 ?>

</div>
