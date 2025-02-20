<?php
require_once("pChart2.1.4/class/pData.class.php");
require_once("pChart2.1.4/class/pDraw.class.php");
require_once("pChart2.1.4/class/pImage.class.php");


$filename ='C:/Users/navis/Downloads/PHP Quiz Question.csv';

$csvData = array_map('str_getcsv', file($filename));

$xValues = []; // To extract X and Y values from CSV data
$yValues = [];

foreach ($csvData as $row) {
    
    $xValues[] = $row[0];  // first column 
    $yValues[] = $row[1];  // second column 
}


$data = new pData();
$data->addPoints($xValues, "X");
$data->addPoints($yValues, "Y");
$data->setAxisName(0, "X Axis");
$data->setAxisName(1, "Y Axis");


$image = new pImage(500, 300, $data);
$image->setGraphArea(60, 40, 650, 400);
$image->drawScatterPlotChart();

$image->render("Scatter_Plot.png");
?>
