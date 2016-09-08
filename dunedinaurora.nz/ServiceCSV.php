<?php
// THis PHP file generates a CSV file of data from the G857

// Include connection information and user defined functions
include 'connect.inc.php';
$queryIntervalMinutes = 120;

// filetype is needed otherwise the header will not attach and break your output
$fileType = "CSV";

$selectQuery = "SELECT * FROM DataF WHERE DataF.timestamp > DATE_SUB(NOW(), INTERVAL ".$queryIntervalMinutes." MINUTE);";
$resultG857 = mysqli_query($connection, $selectQuery);

include 'ServicesHEADER.php';

echo("Datetime (UTC), Full Field Reading (nT)"."\r\n");

while ($row = mysqli_fetch_array($resultG857)) 
{	
	$timeNX =  (string)($row['timestamp']);
	echo($timeNX.",".$row["dataF"]."\r\n");
}

//echo("}")
//echo json_encode($dataF);

?>