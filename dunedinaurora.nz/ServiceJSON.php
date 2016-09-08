<?php
// THis PHP file generates a JSON encoded file of data from the G857

// Include connection information and user defined functions
include 'connect.inc.php';
$queryIntervalMinutes = 120;

// filetype is needed otherwise the header will not attach and break your output
$fileType = "JSON";
header('Content-Type: application/json');
header('Content-Disposition: attachment; filename=DunedinAurora.json');
echo('{');

include 'ServicesHEADER.php';

echo('"Data":[');


$selectQuery = "SELECT * FROM DataF WHERE DataF.timestamp > DATE_SUB(NOW(), INTERVAL ".$queryIntervalMinutes." MINUTE);";
$resultG857 = mysqli_query($connection, $selectQuery);

// How many rows are we getting back?
$numberOfRows = mysqli_num_rows($resultG857);
$counter = 0;

// Dont forget to remove the trailing comma from the last key:value pair
while ($row = mysqli_fetch_array($resultG857)) 
{	
	$counter++;
	$timeNX =  (string)($row['timestamp']);

	if($counter == $numberOfRows)
	{
		//echo("Last Row");
		echo('{"Time UTC":"'.$timeNX.'","Full Field Reading nT":'.$row['dataF'].'}');
	}
	else
	{
		//echo($counter." ");	
		echo('{"Time UTC":"'.$timeNX.'","Full Field Reading nT":'.$row['dataF'].'},');
	}
}

echo("]}")

?>