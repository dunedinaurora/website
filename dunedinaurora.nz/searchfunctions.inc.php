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
	// UDF to to display search results
	// ********************************
	
	function displayResults($connection, $startdate, $enddate, $readingtype, $outputfiles)
	{
		// Open the text file for writing
		$csvf = $outputfiles."/results.csv";
		$jsonf = $outputfiles."/results.json";

		$csvfile = fopen($csvf, "w") or die("Unable to open CSV file!");
		$jsonfile = fopen($jsonf, "w") or die("Unable to open JSON file!");
		
		// Create the SQL query
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
			
			// Write the file headers
			if ($readingtype == "DataF")
			{
				//G857
				fwrite($csvfile, "Datetime (UTC), Full Field Reading (nT)"."\r\n");
			}
			else
			{
				//SAM
				fwrite($csvfile, "Datetime (UTC), X axis (nT),Y axis (nT),Z axis (nT),"."\r\n");
			}
			
			// now write the file contents
			while ($row = mysqli_fetch_assoc($result))
			{
				if ($readingtype == "DataF")
				{
					//G857
					$data = $row['timestamp'].",".$row['dataF']."\r\n";
					fwrite($csvfile, $data);
				}
				else
				{
					//SAM
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
			echo("<br><p>Download your data as <a class=\"btn btn-success\" href=\"$outputfiles/results.csv\" role=\"button\">CSV</a> or <a class=\"btn btn-success\" href=\"$outputfiles/results.json\" role=\"button\">JSON</a>");
			
			// create help on data formats
			echo("
					<!-- Trigger the modal with a button -->
					<button type=\"button\" class=\"btn btn-info\" data-toggle=\"modal\" data-target=\"#myModal1\"><span class=\"glyphicon glyphicon-question-sign\" aria-hidden=\"true\"></span></button>


					<!-- Modal -->
					<div id=\"myModal1\" class=\"modal fade\" role=\"dialog\">
					  <div class=\"modal-dialog\">
  						<!-- Modal content-->
  						<div class=\"modal-content\">
  						  <div class=\"modal-header\">
    							<button type=\"button\" class=\"close\" data-dismiss=\"modal\">&times;</button>
    							<h4 class=\"modal-title\">CSV and JSON data</h4>
  						  </div>
  						  <div class=\"modal-body\">
    							<p>Data from the website is provided in two formats: Comma Separated Values (CSV) and JavaScript Object Notation (JSON - pronounced \"Jason\")</p>
								<p>Both of these formats are plain text files, with the data laid out in a particular way.</p>
								<p>CSV files are the simplest to use and can be imported into Excel. Our files have the date/time as the first item on the line, then a comma followed by the reading from the magnetometer. The very first line in the file describes what each item is.
								<br><samp>
								<br>Datetime (UTC), Full Field Reading (nT)
								<br>2016-04-05 00:00:05,58824.40
								<br>2016-04-05 00:00:15,58823.00
								<br>2016-04-05 00:00:25,58815.00
								<br>etc...
								</samp>
								<p>JSON files use a different format and are usually used by people writing their own software.</p>
  						  </div>
  						  <div class=\"modal-footer\">
  							  <button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>
  						  </div>
  						</div>
					  </div>
					</div>
				</div>
			");
		}

		fclose($jsonfile);	

		// ******************************************************
		// CREATE DISPLAY FILE FOR GRAPH
		// Because of the extreme overhead on highcharts when charting
		// enourmous datasets, we will create a reduced file of 
		// approx 1000 datapoints for display purposes
		// ******************************************************
		$maxdisplay = 1000; // we can tune this for performance on Highcharts
		$i = 0; // to test if we've gotten to every ith row
		//$j = 0; // to report how many rows we ended up with

		// Reset the row pointer back to the start of my results
		mysqli_data_seek($result, 0);

		// if our query has returned less than 1000 rows (SO should be quick to display)
		if (mysqli_num_rows($result) < $maxdisplay)
		{	
			drawgraph('outputfiles/results.csv');
		}
		else //average down the query to however many rows and write out to reduced display CSV
		{
			//OPen new CSV for reduced files
			$reducedfile = fopen('outputfiles/reduced.csv', "w") or die("Unable to open CSV file!");
			
			//We're going to skip over our array until we get to the end. We use the array size div by out threshold
			$parseinterval = floor(mysqli_num_rows($result) / $maxdisplay);
			// echo("<p>Plotting every $parseinterval<sup>th</sup> datapoint...");

			// set up CSV file header
			if ($readingtype == "DataF")
			{
				//G857
				fwrite($reducedfile, "Datetime (UTC), Full Field Reading (nT)"."\r\n");
			}
			else
			{
				//SAM
				fwrite($reducedfile, "Datetime (UTC), X axis (nT),Y axis (nT),Z axis (nT),"."\r\n");
			}

			// Create the CSV file body
			while ($row = mysqli_fetch_array($result)) 
			{
				$i++;
				if ($i%$parseinterval == 0) //MOD div, are we at ith row yet?
				{
					if ($readingtype == "DataF")
					{
						//G857
						$data = $row['timestamp'].",".$row['dataF']."\r\n";
						fwrite($reducedfile, $data);
						//$j++;
					}
					else
					{
						//SAM
						$data = $row['timestamp'].",".$row['dataX'].",".$row['dataY'].",".$row['dataZ']."\r\n";
						fwrite($reducedfile, $data);
						//$j++;
					}
				}
			}

			// CLOSE the CSV file
			fclose($reducedfile);

			// echo("<p>Reduced file is $j records");

			// call the graphing function on the reduced file	
			drawgraph('outputfiles/reduced.csv');
		}
	}

	
function drawgraph($resultfile)
{
	echo("
		<!-- 1. Add these JavaScript inclusions in the head of your page -->
				<script type=\"text/javascript\" src=\"http://code.highcharts.com/highcharts.js\"></script>
				<script type=\"text/javascript\" src=\"http://code.highcharts.com/modules/data.js\"></script>
						
		<!-- 2. Add the JavaScript to initialize the chart on document ready -->
				<script type=\"text/javascript\">
				
				//**********************************************************************************************************
				/**
				 * Dark theme for Highcharts JS
				 * @author Torstein Honsi
				 */

				// Load the fonts
				Highcharts.createElement('link', {
				   href: '//fonts.googleapis.com/css?family=Unica+One',
				   rel: 'stylesheet',
				   type: 'text/css'
				}, null, document.getElementsByTagName('head')[0]);

				Highcharts.theme = {
				   colors: [\"#2b908f\", \"#90ee7e\", \"#f45b5b\", \"#7798BF\", \"#aaeeee\", \"#ff0066\", \"#eeaaee\", \"#55BF3B\", \"#DF5353\", \"#7798BF\", \"#aaeeee\"],
				   chart: {
					  backgroundColor: {
						 linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
						 stops: [
							[0, '#2a2a2b'],
							[1, '#3e3e40']
						 ]
					  },
					  style: {
						 fontFamily: \"'Unica One', sans-serif\"
					  },
					  plotBorderColor: '#606063'
				   },
				   title: {
					  style: {
						 color: '#E0E0E3',
						 textTransform: 'uppercase',
						 fontSize: '20px'
					  }
				   },
				   subtitle: {
					  style: {
						 color: '#E0E0E3',
						 textTransform: 'uppercase'
					  }
				   },
				   xAxis: {
					  gridLineColor: '#707073',
					  labels: {
						 style: {
							color: '#E0E0E3'
						 }
					  },
					  lineColor: '#707073',
					  minorGridLineColor: '#505053',
					  tickColor: '#707073',
					  title: {
						 style: {
							color: '#A0A0A3'

						 }
					  }
				   },
				   
				   yAxis: {
					  gridLineColor: '#707073',
					  labels: {
						 style: {
							color: '#E0E0E3'
						 }
					  },
					  lineColor: '#707073',
					  minorGridLineColor: '#505053',
					  tickColor: '#707073',
					  tickWidth: 1,
					  title: {
						 style: {
							color: '#A0A0A3'
						 }
					  },
				   },
				   tooltip: {
					  backgroundColor: 'rgba(0, 0, 0, 0.85)',
					  style: {
						 color: '#F0F0F0'
					  }
				   },
				   plotOptions: {
					  series: {
						 dataLabels: {
							color: '#B0B0B3'
						 },
						 marker: {
							lineColor: '#333'
						 }
					  },
					  boxplot: {
						 fillColor: '#505053'
					  },
					  candlestick: {
						 lineColor: 'white'
					  },
					  errorbar: {
						 color: 'white'
					  }
				   },
				   legend: {
					  itemStyle: {
						 color: '#E0E0E3'
					  },
					  itemHoverStyle: {
						 color: '#FFF'
					  },
					  itemHiddenStyle: {
						 color: '#606063'
					  }
				   },
				   credits: {
					  style: {
						 color: '#666'
					  }
				   },
				   labels: {
					  style: {
						 color: '#707073'
					  }
				   },

				   drilldown: {
					  activeAxisLabelStyle: {
						 color: '#F0F0F3'
					  },
					  activeDataLabelStyle: {
						 color: '#F0F0F3'
					  }
				   },

				   navigation: {
					  buttonOptions: {
						 symbolStroke: '#DDDDDD',
						 theme: {
							fill: '#505053'
						 }
					  }
				   },

				   // scroll charts
				   rangeSelector: {
					  buttonTheme: {
						 fill: '#505053',
						 stroke: '#000000',
						 style: {
							color: '#CCC'
						 },
						 states: {
							hover: {
							   fill: '#707073',
							   stroke: '#000000',
							   style: {
								  color: 'white'
							   }
							},
							select: {
							   fill: '#000003',
							   stroke: '#000000',
							   style: {
								  color: 'white'
							   }
							}
						 }
					  },
					  inputBoxBorderColor: '#505053',
					  inputStyle: {
						 backgroundColor: '#333',
						 color: 'silver'
					  },
					  labelStyle: {
						 color: 'silver'
					  }
				   },

				   navigator: {
					  handles: {
						 backgroundColor: '#666',
						 borderColor: '#AAA'
					  },
					  outlineColor: '#CCC',
					  maskFill: 'rgba(255,255,255,0.1)',
					  series: {
						 color: '#7798BF',
						 lineColor: '#A6C7ED'
					  },
					  xAxis: {
						 gridLineColor: '#505053'
					  }
				   },

				   scrollbar: {
					  barBackgroundColor: '#808083',
					  barBorderColor: '#808083',
					  buttonArrowColor: '#CCC',
					  buttonBackgroundColor: '#606063',
					  buttonBorderColor: '#606063',
					  rifleColor: '#FFF',
					  trackBackgroundColor: '#404043',
					  trackBorderColor: '#404043'
				   },

				   // special colors for some of the
				   legendBackgroundColor: 'rgba(0, 0, 0, 0.5)',
				   background2: '#505053',
				   dataLabelsColor: '#B0B0B3',
				   textColor: '#C0C0C0',
				   contrastTextColor: '#F0F0F3',
				   maskColor: 'rgba(255,255,255,0.3)'
				};

				// Apply the theme
				Highcharts.setOptions(Highcharts.theme);
				//**********************************************************************************************************
				
				
				$(document).ready(function() {

				Highcharts.setOptions({})
				
					$.get('$resultfile', function(csv) {
						$('#container').highcharts({
							chart: {
								//type: 'spline',
								zoomType:'x',
								//height: 640,
								spacingLeft: 10,
								spacingRight: 10
							},
							plotOptions: {
							},
							data: 
							{
								csv: csv,
								startColumn: 0,
								endColumn: 3,
							},
							tooltip: {
								enabled: true
							},
							title: 
							{
								text: 'Data Preview'
							},
							yAxis: [

								{ // Primary yAxis
										labels: {
											enabled: false
										},
										title: {
											text: null
												}
								}, 
								{ // Secondary yAxis
										labels: {
											enabled: false
											},
										title: {
											text: null
												}
								},
								{ // 3rdry yAxis
										labels: {
											enabled: false
											},
										title: {
											text: null
												}
								},
							],
							series:[
								{yAxis: 0},
								{yAxis: 1},
								{yAxis: 2}
							],
							xAxis: 
							{
								tickInterval: 60,
								gridLineWidth: 1,
								gridLineColor: '#D8D8D8',
								title: 
								{
									text: 'Time, UTC'
								}
							}
						});
					});
				});
				</script>

		"); // end of echo
}


?>