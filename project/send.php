<?php 
session_start();
include('php/database.php');
if(isset($_POST['message']))
{

    if($_SESSION['role']=='admin' && isset($_GET['loc']) )
    {

  send_message($_SESSION['id'],$_GET['user'],$_POST['message']);
   $location=$_GET['loc'];
 
    }
    else
    {
        send_message($_SESSION['id'],0,$_POST['message']);
   
    }
}

?>
<div>
    <p class='send'><?php 
    if(isset($_POST['message']))
    echo $_POST['message']; ?></p>
</div>


