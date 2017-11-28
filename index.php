<!--    example -  https://www.freegamesplay.ru/study/ --> 
<!DOCTYPE html>
<html>
<head>
    <title>JSON</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
 	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
 <style>
body{
	background-color:#dfdcdc;
	margin:0;
}
a{
	color:#000;
	text-decoration:none;
	font-size:20px;
}
a:hover{
	color:#FFF;
	text-decoration:underline;
}
.month{
	display: block;
	background-color:#9f9d9d;
	color:#473f3f;
	height:30px;
}
li{
	float:left;
	list-style-type: none;
	padding:0 20px 0 20px;
	height:30px;
}
li:hover{
	background-color:#7f7b7b;
}
.subjects{
	display: block;
	background-color:#b8b4b4;
	color:#473f3f;
	height:30px;
}
#chart_div{
	background-color: #dfdcdc;
}

</style>
 	
</head>
<body>
<?php 

$j = @file_get_contents("data.json");

$data = json_decode(iconv('windows-1251', 'utf-8', $j),1);

$keys = array_keys($data);
$firstKey = $keys[0];

$month = $_GET["month"];
$subject = $_GET["subject"];
if (!$month && !$subject){
	$month = $firstKey;
}
elseif($subject){
	$month = "";
}
else{
	$subject = "";
}

?>
<div class="month">

<?php 
foreach ($data as $month2 => $subjects2)
{
	?><a href="?month=<?= $month2 ?>"><li><?= $month2 ?></li></a> <?php 
}

?>
</div>

<div class="subjects">

<?php 
foreach ($data[$firstKey] as $subjects => $orders)
{
	?><a href="?subject=<?= $subjects ?>"><li><?= $subjects ?></li></a> <?php 
}

?>
</div>
<br clear="all">
<script type="text/javascript">

// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart', 'bar']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {

<?php if ($month){ ?>
	
	// Create the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Subjects');
	data.addColumn('number', 'Orders');
	data.addRows([
<?php 

foreach ($data[$month] as $key => $orders){
	

	$q .= "['" . $key . "', " . $orders . "],\n";
 
}
$q = substr($q, 0, strlen($q)-2);
?>
<?= $q ?>
	]);
	
	// Set chart options
	var options = {'title':'Orders in  <?= $month ?> ',
	'backgroundColor': '#dfdcdc',
	'width':800,
	'height':600};

	// Instantiate and draw our chart, passing in some options.
<?php 
}
else
{
?>	
var data = google.visualization.arrayToDataTable([
<?
$q = "['Subject', 'Orders'],\n";
foreach ($data as $month => $datas)
{	
	foreach ($datas as $subjects => $orders)
	{
		if ($subject == $subjects)
		{
			$q .= "['" . $month . "', " . $orders . "],\n";
		}
	}
}

$q = substr($q, 0, strlen($q)-2);
?>
<?= trim($q) ?>
                                                ]);
var options = {
        title: 'Data of orders',
        backgroundColor: '#dfdcdc',
        chartArea: {width: '70%', height: "700"},
        isStacked: true,
        hAxis: {
          title: 'Total orders',
          minValue: 0,
        },
        vAxis: {
          title: '<?= $subject ?>'
        }
      };	
<?php 	
}
if ($month && !$subject){?>

	var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
	chart.draw(data, options);
<?php }else{ ?>	

      var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
      chart.draw(data, options);
<?php }?>	
	
}
</script>
<div id="chart_div"></div>


</body>