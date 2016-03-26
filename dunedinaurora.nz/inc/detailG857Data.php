<?php
// Include connection information and user defined functions
include '../connect.inc.php';

$selectQuery = "SELECT * FROM DataF WHERE DataF.timestamp > DATE_SUB(NOW(), INTERVAL 24 HOUR);";
$result = mysqli_query($connection, $selectQuery);

while ($row = mysqli_fetch_array($result)) 
{	
	$dataF[] = array($row['timestamp'],$row['dataF']);
	//$dataF[] = $row['dataF'];
}

echo json_encode($dataF, JSON_NUMERIC_CHECK);	
?>