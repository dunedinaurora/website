<?php
// *********************************************
// U  S E R   D E F I N E D   F U N C T I O N S 
// *********************************************


	// **************************
	// UDF clean up form strings
	// **************************
	function cleanString($stringToCheck)
	{
		//strip injection attack 
		$stringToCheck = strip_tags($stringToCheck);
		
		//get rid of empty string values
		if (strlen(trim($stringToCheck)) == 0)
		{
			$stringToCheck = "NO VALUE";
		}
		else if (empty($stringToCheck))
		{
			$stringToCheck = "NO VALUE";
		}
		return $stringToCheck;
	}

	// ********************************
	// UDF to to parse date strings
	// ********************************	
	function parsedate($datestring)
	{


	}

	// ********************************
	// UDF to to display search results
	// ********************************
	
	function displayResults($connection, $startdate, $enddate, $readingtype)
	{
		// Open the text file for writing
		$csvfile = fopen("results.csv", "w") or die("Unable to open CSV file!");
		$jsonfile = fopen("results.json", "w") or die("Unable to open JSON file!");
		
		$selectQuery="select * from $readingtype where $readingtype.timestamp between \"$startdate 00:00:00\" and \"$enddate 00:00:00\"";
		
		$result = mysqli_query($connection, $selectQuery);
		
		echo("<br>".mysqli_num_rows($result)." rows of data retrieved<br>");
		
		// Process returned data
		if (mysqli_num_rows($result) == 0)
		{
			// No data returned
			echo("<br>No data recorded for dates specified.");
		}
		else
		{
			// *****************
			// CSV FILE WRITEOUT
			// *****************
			if ($readingtype == "DataF")
			{
				//G857 header
				fwrite($csvfile, "Datetime (UTC), Full Field Reading (nT)"."\r\n");
			}
			else
			{
				//SAM header
				fwrite($csvfile, "Datetime (UTC), X axis (nT),Y axis (nT),Z axis (nT),"."\r\n");
			}
			
			
			while ($row = mysqli_fetch_assoc($result))
			{
				if ($readingtype == "DataF")
				{
					//G857 data
					$data = $row['timestamp'].",".$row['dataF']."\r\n";
					fwrite($csvfile, $data);
				}
				else
				{
					//SAM data
					$data = $row['timestamp'].",".$row['dataX'].",".$row['dataY'].",".$row['dataZ']."\r\n";
					fwrite($csvfile, $data);
				}
				

			}

			// CLOSE the CSV file
			fclose($csvfile);
			
			// ******************
			// JSON FILE WRITEOUT
			// ******************
			fwrite($jsonfile,'{"Data":[');
			
			// Reset the row pointer back to the start of my results
			mysqli_data_seek($result, 0);
			
			// How many rows are we getting back?
			$numberOfRows = mysqli_num_rows($result);
								
			$counter = 0;

			// Dont forget to remove the trailing comma from the last key:value pair
			while ($row = mysqli_fetch_array($result)) 
			{	
				$counter++;
				$timeNX =  (string)($row['timestamp']);

				if($counter == $numberOfRows)
				{
					//echo("Last Row");
					if ($readingtype == "DataF")
					{
						//G857
						fwrite($jsonfile,'{"Time UTC":"'.$timeNX.'","Full Field Reading nT":'.$row['dataF'].'}');
					}
					else
					{
						//SAM
						fwrite($jsonfile, '{"Time UTC":"'.$timeNX.'",'.'"X (nT)":'.$row['dataX'].','.'"Y (nT)":'.$row['dataY'].','.'"Z (nT)":'.$row['dataZ'].'}');
					}
					
				}
				else
				{
				
					if ($readingtype == "DataF")
					{
						//G857
						fwrite($jsonfile,'{"Time UTC":"'.$timeNX.'","Full Field Reading nT":'.$row['dataF'].'},');
					}
					else
					{
						//SAM
						fwrite($jsonfile, '{"Time UTC":"'.$timeNX.'",'.'"X (nT)":'.$row['dataX'].','.'"Y (nT)":'.$row['dataY'].','.'"Z (nT)":'.$row['dataZ'].'},');
					}
				}
			}
			// CLOSE the JSON file
			fwrite($jsonfile,"]}");
						
			// CReate download buttons.
			echo("<p>Download your data as <a class=\"btn btn-success\" href=\"results.csv\" role=\"button\">CSV</a> or <a class=\"btn btn-success\" href=\"results.json\" role=\"button\">JSON</a>");
		}

		fclose($jsonfile);					
	}
	
?>