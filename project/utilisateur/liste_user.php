
<div class="article">
<h2>Liste des utilisateurs</h2>
<div class="options">
    <a href="utilisateur/Add_user.php">Ajouter</a>
    <a href="utilisateur/update_user.php">Modifier</a>
   
</div>
<?php show_user_table($connexion) ;?>
</div>


<div class="article">
<h2>Statistique:</h2>
<div id="stat">
    <div class="stat">
        <?php $data1=data_chart_all_equip($connexion) ;
            if(strcmp($data1,"[0,0,0]")!=0)
                echo "<canvas id='myChart1' width='10' ></canvas>";
            else
            echo "<center><p>Aucun graphe pour le moment</p></center>";
        ?>
    
    </div>
    <div class='stat'>
    <?php $data2=data_chart_all_user($connexion);
    
    if(strcmp($data2,"[0,0]")!=0)
    echo "<canvas id='myChart2' width='10' ></canvas>";
    else
    echo "<center><p>Aucun graphe pour le moment</p></center>";
    ?>
    </div>
    
    
</div>
</div>

<script >


var ctx = document.getElementById('myChart1').getContext('2d');

var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: [ 'Up', 'Warning','Down'],
        datasets: [{
            data: <?php echo "$data1"; ?>,
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

<script>
var ctx2 = document.getElementById('myChart2').getContext('2d');

var myChart2 = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: [ 'Superviseur','Sans Role'],
        datasets: [{
            data: <?php echo "$data2"; ?>,
            backgroundColor: [
                '#00acee',
                '#c6e3fa',
                
                
                
            ],
            borderColor: [
                
                'white',
                'white',
                
            
            ],
            options: {
                    maintainAspectRatio: false,
                },
            borderWidth: 1
        }]
    },
    
});

</script>