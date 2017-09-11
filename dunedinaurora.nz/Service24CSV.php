<?php
// THis PHP file generates a CSV file of data from the G857

// Include connection information and user defined functions
include 'connect.inc.php';
$queryIntervalMinutes = 1440;

// filetype is needed otherwise the header will not attach and break your output
$fileType = "CSV";

$selectQuery = "SELECT * FROM DataF WHERE DataF.timestamp > DATE_SUB(NOW(), INTERVAL ".$queryIntervalMinutes." MINUTE);";
$resultG857 = mysqli_query($connection, $selectQuery);

//include 'ServicesHEADER.php';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=DunedinAuroraCSV.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('Datetime (UTC)', 'Full Field Reading (nT)'));

while ($row = mysqli_fetch_array($resultG857)) 
{	
	$timeNX = (string)($row['timestamp']);
	$result = array($timeNX,$row["dataF"]);
	fputcsv($output, $result);
}

//echo("}")
//echo json_encode($dataF);

?>