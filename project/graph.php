<?php // content="text/plain; charset=utf-8"
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_pie.php');
require_once ('jpgraph/jpgraph_pie3d.php');

include('php/database.php');
if(isset($_GET['loc']))
    $data=data_graph($_GET['loc'],$connexion);
else
    $data=data_graph_sup($_GET['id'],$connexion);


// Create the Pie Graph. 
$graph = new PieGraph(350,250);

$theme_class= new VividTheme;
$graph->SetTheme($theme_class);



// Create
$p1 = new PiePlot3D($data);
$graph->Add($p1);
$p1->SetSliceColors(array('mediumseagreen','orange','red'));
$p1->ShowBorder();
$p1->SetColor('black');
$p1->ExplodeSlice(2);
$graph->Stroke();

?>