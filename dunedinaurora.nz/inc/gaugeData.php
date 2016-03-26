<?php
	include '../connect.inc.php';
	// QUERY INTERVAL - should be divisible by two. The full datyaset will be used to init the graphs, but live updates will
	// be based on the most recent half value.
	$queryInterval = 30; 
	

	$selectQuery = "SELECT * FROM DataF WHERE DataF.timestamp > DATE_SUB(NOW(), INTERVAL ".$queryInterval." minute);";
	$result = mysqli_query($connection, $selectQuery);

	while ($row = mysqli_fetch_array($result))
	{
		$dataF[] = $row['dataF'];
	}
	echo json_encode($dataF, JSON_NUMERIC_CHECK);	
	//echo json_encode($dataF);	
?>