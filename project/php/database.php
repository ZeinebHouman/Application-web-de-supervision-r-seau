<?php 
        $serveur="localhost";
        $login="root";
        $pass="";
        try
        {
        $connexion=new PDO("mysql:host=$serveur;dbname=supervision",$login,$pass);
        $connexion -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
                echo "echec de la connexion ".$e->getMessage();
        }


        
        

function ping_adress($name,$user){

        $requete=$connexion->prepare("
        SELECT *
        FROM equipement
        WHERE ID_USER='$user' ");
        $requete->execute();
        $resultat=$requete->fetchall();
        $etat=send_ping_request($resultat[(int)$name]['ADRESSE_IP']);
        $requete=$connexion->prepare("
        UPDATE equipement
        SET ETAT=$etat;
        WHERE ID_USER='$user' ");
        $requete->execute();
}
function check_log($name,$pass,$role,$connexion){
        trim($name);
        trim($pass);
        strip_tags($name);
        strip_tags($pass);
        $requete=$connexion->prepare("
        SELECT *
        FROM utilisateur
        WHERE USERPSEUDO='$name' AND PASSWORD='$pass' AND ROLE='$role' ");
        $requete->execute();
        $resultat=$requete->fetchall();
        
        if(sizeof($resultat)>0)
        {
                $_SESSION['id']=$resultat[0]['ID_USER'];
                return $_SESSION['id'];
        }
        else
                return -1;
}

function verify_ping_int($ligne)
{
                $ligne=substr($ligne,36,20);
                $data=array();
                for($i=0;$i<strlen($ligne);$i++)
                {
                        if(is_numeric($ligne[$i]))
                        {
                                array_push($data,$ligne[$i]);
                                
                                
                        }          
                }
                
                $ch=implode($data);
              //  print_r($data);
                return $ch;
}
function send_ping_request($ip,$name_file){
        $out=shell_exec("ping $ip ");
         // echo "<pre>".$out."</pre>";
          $f=fopen($name_file,"w");
          fputs($f,$out);
          fclose($f);
          $f=fopen($name_file,"r");
          for($i=0;$i<9;$i++){
             $ligne=fgets($f);
          }
         // echo "<pre>".$ligne."</pre>";
          $data=array();
          $i=0;
          $j=0;
          while($i<3 && $j<strlen($ligne))
          {
                if(is_numeric($ligne[$j]))
                {
                        
                        array_push($data,(int)$ligne[$j]);
                        $i++;
                }
                $j++;
          }
          
          $envoye=$data[0];
          $recu=$data[1];
          $perdu=$data[2];
          $moyenne=($recu*100)/$envoye;
        // echo "<p>envoye=$envoye</p> <p>recu=$recu</p> <p>perdu=$perdu</p>";
         $ligne=fgets($f);
         $ligne=fgets($f);
      // echo "ligne avant de l'avant=$ligne<br>";
         $tmp_ping=verify_ping_int($ligne);
      //   echo "<br><br>ligne=$tmp_ping";
          $tmp_ping=(int)$tmp_ping;
          unlink($name_file);
          //  fclose($f);
          if($moyenne==100 && $tmp_ping<=50)
          {
           //   $src="fig1.png";
              $etat="up";
          }
          else if($moyenne>0 || $tmp_ping>=90)
          {
             // $src="fig2.png";
              $etat="warning";
          }
          else
          {
            //$src="fig3.png";
            $etat="down";
          }
         /* echo "<p><img src='img/$src'/>";
          echo "moyenne = ".$moyenne."%</p>";*/
          return $etat;
    }
//use it like this 
//include("php/functions.php");
//send_ping_request('192.168.1.1');

function add_equip($loc,$modele,$ref,$ip,$user,$connexion){
        

        $etat=send_ping_request($ip,"../tmp/check_result.txt");
   
        $requete="
        INSERT INTO equipement(ADRESSE_IP,REF_LOC,ID_USER,MODELE_EQUIP,REF_EQUIP,ETAT
        ) VALUES('$ip','$loc','$user','$modele','$ref','$etat')";
        $connexion->exec($requete);

        }
      /*
function delete_equip($ip,$user,$connexion)
{
        $str="
      DELETE FROM equipement
      WHERE ID_USER=$user AND ADRESSE_IP='$ip'";
        $requete=$connexion->prepare($str);
        $requete->execute();
      //if()
        return 1; 
       //else
       //return 0;
     
}
*/
function get_avatar($id,$chemin,$no_one){
        $src="$chemin$id.jpg";
        if(!file_exists($src)){
          $src=$no_one."no_one.png";
        }
        return  $src;
}

function show_table_for_sup($user,$connexion)
{
        echo "
        
        <table style='text-align:center;' >
        <tr style='font-weight:bold;'><td>Etat</td> <td>Modele</td> <td>Reference</td> <td>Adresse IP</td> <td>Check</td></tr>
        ";
      
       
        $requete=$connexion->prepare("
        SELECT *
        FROM equipement
        WHERE ID_USER='$user' ");
        $requete->execute();
        $resultat=$requete->fetchall();
                for($i=0;$i<sizeof($resultat);$i++)
                {
                        if(strcmp($resultat[$i][5],"up")==0)
                        {
                            $src="fig1.png";
                           
                         }
                         else if(strcmp($resultat[$i][5],"warning")==0)
                         {
                             $src="fig2.png";
                           }
                          else if(strcmp($resultat[$i][5],"down")==0)
                         {
                            $src="fig3.png";
                         }

                        echo "<tr><td><center><img src='img/$src'/></center></td> <td><p>".$resultat[$i][3]."</p></td> <td><p>".$resultat[$i][4]."</p></td> <td><p>".$resultat[$i][0]."</p></td> <td><form action='index.php' method='post'><button class='ping' name='Adresse_ip' value=".$resultat[$i][0].">Ping</button></form></td></tr>";
                }
        echo "</table>";
                
           
}   

function show_table_for_adm($loc,$connexion){
       
        echo "
        
        <table style='text-align:center;' >
        <tr style='font-weight:bold;'><td>Etat</td> <td>Modele</td> <td>Reference</td> <td>Adresse IP</td> <td>Check</td></tr>
        ";
        
        $requete=$connexion->prepare("
        SELECT *
        FROM equipement 
        WHERE  REF_LOC=$loc");
        $requete->execute();
        $resultat=$requete->fetchall();
        
                for($i=0;$i<sizeof($resultat);$i++)
                {
                        if(strcmp($resultat[$i][5],"up")==0)
                        {
                            $src="fig1.png";
                           
                         }
                         else if(strcmp($resultat[$i][5],"warning")==0)
                         {
                             $src="fig2.png";
                           }
                          else if(strcmp($resultat[$i][5],"down")==0)
                         {
                            $src="fig3.png";
                         }

                        echo "<tr><td><center><img src='img/$src'/></center></td> <td><p>".$resultat[$i][3]."</p></td> <td><p>".$resultat[$i][4]."</p></td> <td><p>".$resultat[$i][0]."</p></td> <td><form action='index.php?loc=".$_GET['loc']."' method='post'><button class='ping' name='Adresse_ip' value=".$resultat[$i][0].">Ping</button></form></td></tr>";
                }
        echo "</table>";
        if(sizeof($resultat)>0)
        {
                $id=$resultat[0]['ID_USER'];
                return $id;
        }
        else 
                return 0;
        
}

function data_graph($loc,$connexion){
        $etat=array('up','warning','down');
        $data=array();
        for($i=0;$i<sizeof($etat);$i++)
        {
                $str="
                SELECT count(*)
                FROM equipement
                WHERE ETAT='$etat[$i]' AND REF_LOC=$loc ";
        
                $requete=$connexion->prepare($str);
                $requete->execute();
                $resultat=$requete->fetchall();
                array_push($data,$resultat[0][0]);
        }

 
        return $data;
}
function data_graph_sup($id,$connexion){
        $etat=array('up','warning','down');
        $data=array();
        for($i=0;$i<sizeof($etat);$i++)
        {
                $str="
                SELECT count(*)
                FROM equipement
                WHERE ETAT='$etat[$i]' AND ID_USER=$id ";
        
                $requete=$connexion->prepare($str);
                $requete->execute();
                $resultat=$requete->fetchall();
                array_push($data,$resultat[0][0]);
        }

 
        return $data;
}

function Add_dep($name,$ref,$connexion){
        $requete="
        INSERT INTO departement(REF_DEP,NOM_DEP) VALUES('$ref','$name')";
        $connexion->exec($requete);

}

function show_table_departement($connexion){
        echo "
        
        <table style='text-align:center;' >
        <tr style='font-weight:bold;'><td>Reference</td> <td>Nom</td> </tr>
        ";
      
       
        $requete=$connexion->prepare("
        SELECT *
        FROM departement 
        ORDER BY REF_DEP");
        $requete->execute();
        $resultat=$requete->fetchall();
                for($i=0;$i<sizeof($resultat);$i++)
                {
        
                        echo "<tr><td>".$resultat[$i]['REF_DEP']."</td> <td><p>".ucfirst($resultat[$i]['NOM_DEP'])."</p></td></tr>";
                }
        echo "</table>";
}

function show_departement_local($connexion){
        $requete=$connexion->prepare("
        SELECT *
        FROM departement
        ORDER BY NOM_DEP
        ");
        $requete->execute();
        $resultat=$requete->fetchall();
       
        for($i=0;$i<sizeof($resultat);$i++){
                $ref_dep=$resultat[$i]['REF_DEP'];
                $nom_dep=$resultat[$i]['NOM_DEP'];
                echo "<div class='dep'><div class='dep_name'><a href='local/liste_loc.php?dep=$ref_dep'>$nom_dep</a></div>";
                $requete2=$connexion->prepare("
                SELECT *
                FROM local
                WHERE local.REF_DEP=$ref_dep
                ORDER BY NOM_LOC
                   ");
                $requete2->execute();
                $resultat2=$requete2->fetchall();
                
                echo "<div class='loc'>";
                for($j=0;$j<sizeof($resultat2);$j++){
                        
                        echo"<a style='margin-left:10%;' href='index.php?loc=".$resultat2[$j]['REF_LOC']."'>".$resultat2[$j]['NOM_LOC']."</a>";
                       
                }
                echo "</div>";
                echo"</div>";
        }
}

function show_loc_table($connexion){
        $ref_dep=$_GET['dep'];
        echo"
        <div class='options'>
        <a href='Add_loc.php?dep=$ref_dep'>Ajouter</a>
        <a href='update_loc.php?dep=$ref_dep'>Modifier</a>
        <a href='delete_loc.php?dep=$ref_dep'>Supprimer</a>
         </div>";
        
        $requete2=$connexion->prepare("
                SELECT *
                FROM local
                WHERE local.REF_DEP=$ref_dep
                   ");
                $requete2->execute();
                $resultat2=$requete2->fetchall();
                echo "
        
        <table style='text-align:center;' >
        <tr style='font-weight:bold;'><td>Reference</td> <td>Nom</td> </tr>
        ";
                for($j=0;$j<sizeof($resultat2);$j++){
                        echo"<tr><td>".$resultat2[$j]['REF_LOC']."</td><td>".ucfirst($resultat2[$j]['NOM_LOC'])."</td></tr>";
                }
        echo "</table>";
}

function show_user_table($connexion){
        $requete=$connexion->prepare("
        SELECT *
        FROM utilisateur
        ORDER BY ID_USER,USERPSEUDO ");
        $requete->execute();
        $resultat=$requete->fetchall();
        if(sizeof($resultat)>1){
        echo "
        
        <table style='text-align:center;' >
        <tr style='font-weight:bold;'><td>Photo</td><td>ID</td> <td>Nom</td> <td>Mot de passe</td> <td>Role</td> <td>Action</td></tr>
        ";
      
       
       
                for($i=1;$i<sizeof($resultat);$i++)
                {
                        if($resultat[$i]['ROLE']=='admin')
                        {
                                $img="img/admin.png";
                        }
                        else if($resultat[$i]['ROLE']=='superviseur')
                        {
                                $img="img/ok.svg";
                        }
                        else
                        {
                                $img="img/no.svg";
                        }
                        $src=get_avatar($resultat[$i]['ID_USER'],"avatar/","img/"); 
                        echo "<tr><td><img class='pdp' src='$src' /></td><td>".$resultat[$i]['ID_USER']."</td> <td><p>".ucfirst($resultat[$i]['USERPSEUDO'])."</p></td><td><p>".$resultat[$i]['PASSWORD']."</p></td><td><img width='35' src='".$img."'/></td><td><a href='utilisateur/Delete_user.php?id=".$resultat[$i]['ID_USER']."' class='btn-sup-user'>Supprimer</a></td></tr>";
                }
        echo "</table>";
        }
        else
        {
                echo "<center><p> Il n'y a aucun utilisateur pour le moment </p></center>";
        }
}

function add_user($id,$name,$pass,$connexion){
        $requete="
        INSERT INTO utilisateur(ID_USER,USERPSEUDO,PASSWORD,ROLE)
         VALUES('$id','$name','$pass','aucun')";
        $connexion->exec($requete);

}


function add_sup($id,$name,$pass,$ref_loc,$connexion){
       
        $str="
        SELECT ID_USER
        FROM local
        WHERE REF_LOC=$ref_loc;
        ";
        $requete=$connexion->prepare($str);
        $requete->execute();
        $resultat=$requete->fetchall();
        if($resultat[0]['ID_USER']>0)
        {       
                delete_sup($resultat[0]['ID_USER'],$connexion);
        }
               //vérifier l'existance d'un superviseur dans le local et le supprimer dans ce cas 
        

        $requete=$connexion->prepare("
        SELECT *
        FROM superviseur
        WHERE ID_USER=$id
        ");
        $requete->execute();
        $resultat=$requete->fetchall(); //vérifier si le nouveau superviseur est déja un superviseur dans un autre local le supprimer dans ce cas 
        if(sizeof($resultat)!=0)
        {
                delete_sup($id,$connexion);
                echo "<p style='color:red;font-size:12px>cette utilisateur est déja un superviseur dans un autre local</p>";
                }
 
        $requete="
        INSERT INTO superviseur(ID_USER,REF_LOC,ADM_ID_USER,USERPSEUDO,PASSWORD,ROLE) 
        VALUES('$id','$ref_loc','0','$name','$pass','superviseur')
        ";
        $connexion->exec($requete);
        $requete="
        UPDATE utilisateur
        SET ROLE='superviseur'
        WHERE ID_USER=$id
        ";
        $connexion->exec($requete);
        
                $requete="
        UPDATE local
        SET ID_USER=$id
        WHERE REF_LOC=$ref_loc
        ";
        $connexion->exec($requete);

        $str0="
        UPDATE equipement
        SET ID_USER=$id
        WHERE REF_LOC=$ref_loc";
        $requete0=$connexion->prepare($str0);
        $requete0->execute();
        
}

function add_loc($ref_loc,$ref_dep,$id_user,$nom_loc,$connexion){
        if($id_user!=0)
        {
        $requete="
        INSERT INTO local(REF_LOC,REF_DEP,ID_USER,NOM_LOC) VALUES('$ref_loc','$ref_dep','$id_user','$nom_loc')";
        $connexion->exec($requete);
        $requete="
        UPDATE utilisateur 
        SET ROLE='superviseur'
        WHERE ID_USER=".$id_user;
        $connexion->exec($requete);

        $str="
        SELECT *
        FROM utilisateur
        WHERE ID_USER=".$id_user;
        $requete1=$connexion->prepare($str);
        $requete1->execute();
        $resultat=$requete1->fetchall();
        add_sup($id_user,$resultat[0]['USERPSEUDO'],$resultat[0]['PASSWORD'],$ref_loc,$connexion);
        }
        else{
                $requete="
                INSERT INTO local(REF_LOC,REF_DEP,ID_USER,NOM_LOC) VALUES('$ref_loc','$ref_dep','0','$nom_loc')";
                $connexion->exec($requete);   
        }
}

function show_sup_table($connexion){
        $str="
        SELECT *
        FROM superviseur,local
        WHERE local.REF_LOC=superviseur.REF_LOC
        ORDER BY superviseur.ID_USER";
        $requete=$connexion->prepare($str);
        $requete->execute();
        $resultat=$requete->fetchall();
        if(sizeof($resultat)>0)
        {
        echo "
        
        <table style='text-align:center;' >
        <tr style='font-weight:bold;'><td>Photo</td><td>ID</td> <td>Nom</td> <td>Mot de passe</td> <td>Local</td> </tr>
        ";
      
                for($i=0;$i<sizeof($resultat);$i++)
                {
                        $src=get_avatar($resultat[$i]['ID_USER'],"../avatar/","../img/");
                        echo "<tr><td><img class='pdp' src='$src' /></td><td>".$resultat[$i]['ID_USER']."</td> <td><p>".ucfirst($resultat[$i]['USERPSEUDO'])."</p></td><td><p>".$resultat[$i]['PASSWORD']."</p></td><td>".$resultat[$i]['NOM_LOC']."</td></tr>";
                }
        echo "</table>";
        }
        else {
                echo "<center><p> Il n'ya aucun superviseur à visualiser pour le moment</p></center>";
        }
}

function get_loc_info($loc,$connexion){
        $data=array();
        $str="
        SELECT *
        FROM local,utilisateur
        WHERE local.ID_USER=utilisateur.ID_USER AND REF_LOC=$loc ";
        $requete=$connexion->prepare($str);
        $requete->execute();
        $resultat=$requete->fetchall();

        
        if(isset($resultat[0]['ID_USER']) && isset($resultat[0]['USERPSEUDO']))
        {
                $data['id']=$resultat[0]['ID_USER'];
                $data['name']=$resultat[0]['USERPSEUDO'];
        }
        else
        {
                $data['id']=0;
                $data['name']="aucun";
        }


        $str="
        SELECT *
        FROM local
        WHERE REF_LOC=$loc ";
        $requete=$connexion->prepare($str);
        $requete->execute();
        $resultat=$requete->fetchall();
        
        $data['nom_loc']=$resultat[0]['NOM_LOC'];

        $str="
        SELECT *
        FROM local,departement
        WHERE local.REF_DEP=departement.REF_DEP AND REF_LOC=$loc ";
        $requete=$connexion->prepare($str);
        $requete->execute();
        $resultat=$requete->fetchall();
        
        $data['ref_dep']=$resultat[0]['REF_DEP'];
        $data['nom_dep']=$resultat[0]['NOM_DEP'];
       // print_r($data);
        return $data;
}


/*
function get_loc_info($loc,$connexion){
        $data=array();
        $requete=$connexion->prepare("
        SELECT *
        FROM departement,local,superviseur
        where superviseur.ID_USER= local.ID_USER and local.REF_DEP=departement.REF_DEP and  local.REF_LOC=$loc  ;
        ");
        $requete->execute();
        $resultat=$requete->fetchall();
        $data['nom_loc']=$resultat[0]['NOM_LOC'];
        $data['id']=$resultat[0]['ID_USER'];
        $data['name']=$resultat[0]['USERPSEUDO'];
        $data['ref_dep']=$resultat[0]['REF_DEP'];
        $data['nom_dep']=$resultat[0]['NOM_DEP'];
       return $data;



}
*/
function delete_user($id,$connexion)
{
        $str="
        DELETE FROM utilisateur
        WHERE ID_USER='$id'";
          $requete=$connexion->prepare($str);
          $requete->execute();

          $str2="
        DELETE FROM superviseur
        WHERE ID_USER='$id'";
          $requete2=$connexion->prepare($str2);
          $requete2->execute();
          $count = $requete->rowCount();
           return  $count;
}

function delete_equip($ip,$connexion)
{
        
        $str="
      DELETE FROM equipement
      WHERE ADRESSE_IP='$ip'";
        $requete=$connexion->prepare($str);
        $requete->execute();
        
        $count = $requete->rowCount();
      
         return  $count;
}

function update_mod($modele,$ip,$connexion)
{
        $str="
        UPDATE equipement 
        SET equipement.MODELE_EQUIP='$modele' 
        WHERE ADRESSE_IP='$ip'";
          $requete=$connexion->prepare($str);
          $requete->execute();
}

function update_ref($ref,$ip,$connexion)
{
        $str="
        UPDATE equipement 
        SET REF_EQUIP= '$ref'
        WHERE ADRESSE_IP='$ip'";
          $requete=$connexion->prepare($str);
          $requete->execute();
}

function update_equip($modele,$ref,$ip,$connexion)
{
        $str="
        UPDATE equipement 
        SET REF_EQUIP= '$ref', MODELE_EQUIP='$modele' 
        WHERE ADRESSE_IP='$ip'";
          $requete=$connexion->prepare($str);
          $requete->execute();
}

function recheck($ip,$connexion){
        $etat=send_ping_request($ip,"tmp/check_result.txt");
        
        $str="
       UPDATE equipement
       SET ETAT='$etat'
       WHERE ADRESSE_IP='$ip'
       ";
       $requete=$connexion->prepare($str);
       $requete->execute();

        return $etat;
}

function chart_js($loc,$connexion)
{
        $array=data_graph($loc,$connexion);
        $i=1;
        $data="[".(string)$array[0];
        while($i<sizeof($array))      
        {
                $data.=",".(string)$array[$i];
                $i++;
        }
        $data[strlen($data)]="]";

        return $data;
}
function chart_js_sup($id,$connexion)
{
        $array=data_graph_sup($id,$connexion);
        $i=1;
        $data="[".(string)$array[0];
        while($i<sizeof($array))      
        {
                $data.=",".(string)$array[$i];
                $i++;
        }
        $data[strlen($data)]="]";

        return $data;
}

function delete_dep($ref,$connexion)
{
        $str="
        SELECT *
        FROM local
        WHERE REF_DEP=".$_POST['ref'];
        $requete1=$connexion->prepare($str);
        $requete1->execute();
        $resultat=$requete1->fetchall();
        for($i=0;$i<sizeof($resultat);$i++)
        {
           delete_loc($resultat[$i]['REF_LOC'],$connexion);
        }


        $str1="
      DELETE FROM departement
      WHERE REF_DEP='$ref'";
        $requete=$connexion->prepare($str1);
        $requete->execute();
        $count = $requete->rowCount();
      
         return  $count;
}
function Update_dep($nom_dep,$ref,$connexion)
{
        $str="
        UPDATE departement
        SET NOM_DEP= '$nom_dep'
        WHERE REF_DEP='$ref'";
          $requete=$connexion->prepare($str);
          $requete->execute();
}
function update_user($nom,$pass,$id,$connexion)
{
        $str="
        UPDATE utilisateur
        SET USERPSEUDO= '$nom', PASSWORD='$pass' 
        WHERE ID_USER='$id'";
          $requete=$connexion->prepare($str);
          $requete->execute();
}

function update_name($nom,$id,$connexion)
{
        $str="
        UPDATE utilisateur
        SET USERPSEUDO= '$nom'
        WHERE ID_USER='$id'";
          $requete=$connexion->prepare($str);
          $requete->execute();
}

function update_pass($pass,$id,$connexion)
{
        $str="
        UPDATE utilisateur
        SET PASSWORD='$pass' 
        WHERE ID_USER='$id'";
          $requete=$connexion->prepare($str);
          $requete->execute();
}

function delete_sup($id,$connexion)
{
        $str0="
       UPDATE equipement
       SET ID_USER=0
        WHERE ID_USER='$id'";
          $requete0=$connexion->prepare($str0);
          $requete0->execute();
      
        $str1="
      DELETE FROM superviseur
      WHERE ID_USER='$id'";
        $requete1=$connexion->prepare($str1);
        $requete1->execute();
        $count = $requete1->rowCount();

        $str2="
        UPDATE utilisateur 
        SET ROLE='aucun'
        WHERE ID_USER='$id'";
        $requete2=$connexion->prepare($str2);
        $requete2->execute();

       $str3="
        UPDATE local
        SET ID_USER= 0
        WHERE  local.ID_USER='$id'";
        $requete3=$connexion->prepare($str3);
        $requete3->execute();
        

      
         return  $count;
}
function write_logs($id,$name,$msg,$file_name,$connexion){
        trim($msg);
        htmlspecialchars($msg);
        strip_tags($msg);
        stripslashes($msg);
        if($msg!="")
        {

        $str="
        SELECT *
        FROM local
        WHERE ID_USER='$id' ";
        $requete=$connexion->prepare($str);
        $requete->execute();
        $resultat=$requete->fetchall();
        if(sizeof($resultat)>0)
        {
        $ref_loc=$resultat[0]['REF_LOC'];
        if($id!=0){
        $f=fopen("$file_name$ref_loc.csv","a+");
      //  $ligne="[".date("Y/m/d")."] [".date("h:i:sa")."] [".$_SERVER['REMOTE_ADDR']."] [".$name."] : $msg";
      
        $ligne=date("Y/m/d").";".date("h:i:sa").";".$_SERVER['REMOTE_ADDR'].";".$name.";$msg";
        //$ligne=array(date("Y/m/d"),date("h:i:sa"),$_SERVER['REMOTE_ADDR'],$name,$msg);
        //fputcsv($f,$ligne,";");
        fputs($f,$ligne);
        fclose($f);
        }
}
        }
}

function get_logs($file_name,$date){
        
        if(file_exists("$file_name"))
        { 
                $f=fopen("$file_name","r"); 
        echo "<table border=1 >
        <tr style='font-weight:bold;text-align:center;'><td>Date</td><td>Time</td><td>Adresse</td><td>Nom</td><td>Message</td><td>Adresse_IP</td><td>Etat</td></tr>";
        while($tab=fgetcsv($f,1024,";")){
                if(strcmp($date,$tab[0])==0)
              {  if(sizeof($tab)==7)
               echo" <tr><td>$tab[0]</td><td>$tab[1]</td><td>$tab[2]</td><td>$tab[3]</td><td>$tab[4]</td><td>$tab[5]</td><td>$tab[6]</td></tr>";
                else
                echo" <tr><td>$tab[0]</td><td>$tab[1]</td><td>$tab[2]</td><td>$tab[3]</td><td>$tab[4]</td><td></td><td></td></tr>";
       }
        }       
        echo "</table>";
        fclose($f);
}
else
{
        echo "<center><p>Pas d'historique enregistré pour le moment</p></center>";
}
        
}
function get_loc($id,$connexion){
        $str="
    SELECT *
    FROM local
    WHERE ID_USER='$id'";
    $requete=$connexion->prepare($str);
        $requete->execute();
        $resultat=$requete->fetchall();
        if(sizeof($resultat)>0)
        {
            return $resultat[0]['REF_LOC'];
        }
}
function check($loc,$connexion){
        $requete=$connexion->prepare("
        SELECT *
        FROM equipement , local
        WHERE equipement.ID_USER=local.ID_USER AND local.REF_LOC=$loc");
        $requete->execute();
        $resultat=$requete->fetchall();
        
                for($i=0;$i<sizeof($resultat);$i++)
                {
                        recheck($resultat[$i]['ADRESSE_IP'],$connexion);
                        
                }

}

function update_loc_user($ref_loc,$ref_sup,$connexion)
{

        $str="
        SELECT *
        FROM utilisateur
        WHERE ID_USER=".$ref_sup;
        $requete1=$connexion->prepare($str);
        $requete1->execute();
        $resultat=$requete1->fetchall();
        add_sup($ref_sup,$resultat[0]['USERPSEUDO'],$resultat[0]['PASSWORD'],$ref_loc,$connexion);
        $str="
        UPDATE local
        SET ID_USER= '$ref_sup'
        WHERE REF_LOC='$ref_loc'";
         $requete=$connexion->prepare($str);
        $requete->execute();

        $str2="
        UPDATE utilisateur 
        SET ROLE='superviseur'
        WHERE ID_USER=".$ref_sup;
        $requete=$connexion->prepare($str2);
         $requete->execute();

       


        $str="
        UPDATE equipement
        SET ID_USER= '$ref_sup'
        WHERE REF_LOC='$ref_loc'";
          $requete=$connexion->prepare($str);
          $requete->execute();

}

function Update_loc_name($nom_loc,$ref,$connexion)
{
        $str="
        UPDATE local
        SET NOM_LOC= '$nom_loc'
        WHERE REF_LOC='$ref'";
          $requete=$connexion->prepare($str);
          $requete->execute();
}
function notification($ref,$connexion){
                
       $str="
       SELECT *
       FROM equipement
       WHERE REF_LOC=$ref AND (ETAT='down' OR ETAT='warning')";
       $requete1=$connexion->prepare($str);
       $requete1->execute();
       $resultat=$requete1->fetchall();
       if(sizeof($resultat)>0)
       {
               echo"<div class='notif'>";
               
                for($i=0;$i<sizeof($resultat);$i++){
                        if($resultat[$i]['ETAT']=='down')
                        {
                                echo "<div  style='background-color:#F08080;border:1px solid red;'>";
                             
                        
                        }
                        else
                        {
                                echo "<div  style='background-color:lemonchiffon;border:1px solid yellow;'>";
                        }
                        
                        
                        
                        ?>
                        <a href="#top"class='exit'>&#10006;</a>
                       <?php  echo"<p >Un probleme est survenu lors de la connexion à l'equipement :<b> ".$resultat[$i]['MODELE_EQUIP']." ".$resultat[$i]['REF_EQUIP']." </b></p></div>";
                      
                }
              
                echo "</div>";
       }
}

function data_chart_all_equip($connexion){
        $etat=array('up','warning','down');
        $array=array();
        for($i=0;$i<sizeof($etat);$i++)
        {
                $str="
                SELECT count(*)
                FROM equipement
                WHERE ETAT='$etat[$i]'";
        
                $requete=$connexion->prepare($str);
                $requete->execute();
                $resultat=$requete->fetchall();
                array_push($array,$resultat[0][0]);
        }
        
        $i=1;
        $data="[".(string)$array[0];
        while($i<sizeof($array))      
        {
                $data.=",".(string)$array[$i];
                $i++;
        }
        $data[strlen($data)]="]";
        return $data;
}
function data_chart_all_user($connexion){
        $etat=array('superviseur','aucun');
        $array=array();
        for($i=0;$i<sizeof($etat);$i++)
        {
                $str="
                SELECT count(*)
                FROM utilisateur
                WHERE ROLE='$etat[$i]'";
        
                $requete=$connexion->prepare($str);
                $requete->execute();
                $resultat=$requete->fetchall();
                array_push($array,$resultat[0][0]);
        }
        
        $i=1;
        $data="[".(string)$array[0];
        while($i<sizeof($array))      
        {
                $data.=",".(string)$array[$i];
                $i++;
        }
        $data[strlen($data)]="]";
        return $data;
}

function verif_equip_exist($ip,$connexion){

        $requete=$connexion->prepare("
        SELECT *
        FROM equipement
        WHERE ADRESSE_IP='$ip' ");
        $requete->execute();
        $resultat=$requete->fetchall();

        if(sizeof($resultat)>0)
        {
                return 1;
        }
        else
        {
                return 0;
        }
}

function verif_user_exist($id,$connexion){

        $requete=$connexion->prepare("
        SELECT *
        FROM utilisateur
        WHERE ID_USER='$id' ");
        $requete->execute();
        $resultat=$requete->fetchall();

        if(sizeof($resultat)>0)
        {
                return 1;
        }
        else
        {
                return 0;
        }
}
function verif_sup_exist($id,$connexion){

        $requete=$connexion->prepare("
        SELECT *
        FROM superviseur
        WHERE ID_USER='$id' ");
        $requete->execute();
        $resultat=$requete->fetchall();

        if(sizeof($resultat)>0)
        {
                return 1;
        }
        else
        {
                return 0;
        }
}

function verif_loc_exist($ref,$nom,$connexion){

        $requete=$connexion->prepare("
        SELECT *
        FROM local
        WHERE REF_LOC='$ref' OR NOM_LOC='$nom'");
        $requete->execute();
        $resultat=$requete->fetchall();

        if(sizeof($resultat)>0)
        {
                return 1;
        }
        else
        {
                return 0;
        }
}

function verif_dep_exist($ref,$nom,$connexion){

        $requete=$connexion->prepare("
        SELECT *
        FROM departement
        WHERE REF_DEP='$ref' OR NOM_DEP='$nom'");
        $requete->execute();
        $resultat=$requete->fetchall();

        if(sizeof($resultat)>0)
        {
                return 1; 
        }
        else
        {
                return 0;
        }
}

function add_avatar($id,$avatar_file,$chemin){
move_uploaded_file($avatar_file['tmp_name'], "$chemin$id.jpg");
echo "<p class='ok' >L'envoi a bien été effectué !</p>";
} 
function delete_loc($ref,$connexion)
{
        $str="
        SELECT *
        FROM superviseur
        WHERE REF_LOC='$ref'";
        $requete1=$connexion->prepare($str);
        $requete1->execute();
        $resultat=$requete1->fetchall();
        if(sizeof($resultat)>0)
        delete_sup($resultat[0]['ID_USER'],$connexion);

        $str="
        DELETE FROM local
        WHERE REF_LOC='$ref'";
          $requete=$connexion->prepare($str);
          $requete->execute();
          $count = $requete->rowCount();
        
           return  $count;
}

function send_message($id1,$id2,$msg){
        if(file_exists("msg/$id1$id2.csv"))
         $f=fopen("msg/$id1$id2.csv","a");
        else if(file_exists("msg/$id2$id1.csv"))
        $f=fopen("msg/$id2$id1.csv","a");   
        else
        $f=fopen("msg/$id1$id2.csv","w");
        $ligne=date("Y/m/d").";".date("h:i:sa").";".$id1.";$msg\n";       
        fputs($f,$ligne);
        fclose($f);
        
}

function show_message($id1,$id2,$src){
        if(file_exists("msg/$id1$id2.csv"))
         $f=fopen("msg/$id1$id2.csv","r");
        else if(file_exists("msg/$id2$id1.csv"))
        $f=fopen("msg/$id2$id1.csv","r");   
        else
         {
                 echo "<center><p style='font-size:12px;'>Aucune conversation</p></center>";
                 return;
         }  
         if(file_exists("msg/$id1$id2.csv") || file_exists("msg/$id2$id1.csv"))
         {

         while($tab=fgetcsv($f,1024,";")){
                if(isset($tab[0]))
                {

                if($tab[2]==$id1)
                {
                        echo "<div ><p  class='send' alt='".$tab[0]."'>".$tab[3]."</p></div>";
                }
                else
                {
                        echo "<div ><img src='$src' style='width:25px;height:25px;border-radius:50%;' alt='".$tab[0]."'/><p  class='get' >".$tab[3]."</p></div>";
                }
        }
         }

                fclose($f);
        }
}

function search_ref_dep($connexion){
        $requete=$connexion->prepare("
        SELECT MAX(REF_DEP)
        FROM departement");
        $requete->execute();
        $resultat=$requete->fetchall();
      //  print_r($resultat);
       // echo $resultat[0][0]+1;
        return $resultat[0][0]+1;
}

function search_ref_loc($connexion){
        $requete=$connexion->prepare("
        SELECT MAX(REF_LOC)
        FROM local");
        $requete->execute();
        $resultat=$requete->fetchall();
      //  print_r($resultat);
       // echo $resultat[0][0]+1;
        return $resultat[0][0]+1;
}
function check_number_notif($connexion,$id){
if($id!=0)
 {     
  $str="
    SELECT *
    FROM equipement
    WHERE (ETAT='warning' OR ETAT='down') AND ID_USER='$id'";}
else
{
        $str="
        SELECT *
        FROM equipement
        WHERE (ETAT='warning' OR ETAT='down') ";
}
    $requete1=$connexion->prepare($str);
    $requete1->execute();
    $resultat=$requete1->fetchall();
    return sizeof($resultat);
}
?>