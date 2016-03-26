<!doctype html>
<!--
Author: Vaughn Malkin.
Date: 	2015-08-26
Topic: PHP file to retrieve data from database.

-->
<html>

	<head>
	
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Dunedin Aurora - Graphs</title>
		
		<!-- Bootstrap Core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom CSS -->
		<link href="css/portfolio-item.css" rel="stylesheet">
		
		
		<!-- Bootstrap Core JavaScript -->
		<script src="js/bootstrap.min.js"></script>
		
		<!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>		
		<!-- Google Analytics Script -->
		

		<script src="http://code.highcharts.com/stock/highstock.js"></script>
		<!-- <script src="http://code.highcharts.com/highcharts.js"></script> -->
		<script src="http://code.highcharts.com/stock/modules/exporting.js"></script>
			
		<style>
			#magContainer
			{
				position: absolute;
				width: 100%;
				min-width: 768px;
				height: 90%;
				min-height: 600px;
				padding: 10px 10px 10px 10px;
			}
		</style>

		<?php
			// Include connection information and user defined functions
			include 'connect.inc.php';
			
			// We need this else the time will be automatically screwed up. Time is UTC.
			date_default_timezone_set('UTC'); 
			
			$selectQuery = "SELECT * FROM DataXYZ WHERE DataXYZ.timestamp > DATE_SUB(NOW(), INTERVAL 24 HOUR) AND idStationXYZ = 1;";
			$resultSAM = mysqli_query($connection, $selectQuery);
			
			//$selectQuery = "SELECT * FROM DataF WHERE DataF.timestamp > DATE_SUB(NOW(), INTERVAL 24 HOUR) AND idStationF = 2;";
			//$resultG857 = mysqli_query($connection, $selectQuery);
			
			$gQuery = "SELECT timestamp, dataF FROM DataF AS F WHERE F.timestamp > DATE_SUB(NOW(), INTERVAL 24 HOUR) AND idStationF = 2;";
			$resultG857 = mysqli_query($connection, $gQuery);

			while ($row = mysqli_fetch_array($resultSAM)) 
			{	
				//echo("<br>I Connected to the database");
				//$xTitles[] = $row['timestamp'];
				$timeNX =  (string)strtotime($row['timestamp']).'000'; // This is needed for Highcharts to display time correctly
				$dataX[] = '['.$timeNX.','.$row['dataX'].']';
				$dataY[] = '['.$timeNX.','.$row['dataY'].']';
				$dataZ[] = '['.$timeNX.','.$row['dataZ'].']';
			}
			
			while ($row = mysqli_fetch_array($resultG857)) 
			{	
				//echo("<br>I Connected to the database");
				//$xTitles[] = $row['timestamp'];
				$timeNX =  (string)strtotime($row['timestamp']).'000'; // This is needed for Highcharts to display time correctly
				$dataF[] = '['.$timeNX.','.$row['dataF'].']';
			}
			
		?>
		
		<script>
			var graphHeight = '45%';

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
			   colors: ["#2b908f", "#90ee7e", "#f45b5b", "#7798BF", "#aaeeee", "#ff0066", "#eeaaee",
				  "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
			   chart: {
				  backgroundColor: {
					 linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
					 stops: [
						[0, '#2a2a2b'],
						[1, '#3e3e40']
					 ]
				  },
				  style: {
					 fontFamily: "'Unica One', sans-serif"
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
				  }
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

		$(function () {
				Highcharts.setOptions({
					//colors: ['#b90000','#d0d000','#00b900','#0000b9'],
					global:{
					timezoneOffset: 0,
					useUTC: true
					}
				})
				
				// create the chart
				$('#magContainer').highcharts('StockChart', {

					title: {
						text: 'Magnetic Field Readings'
					},
					subtitle: {
						text: 'Portobello, Dunedin, New Zealand.'
					},

					rangeSelector: {
						enabled: true,			
						selected: 2,
						buttonSpacing: 5,
						buttons: [{
							type: 'minute',
							count: 60,
							text: '1 Hr'
						}, 
						{
							type: 'minute',
							count: 240,
							text: '4 Hr'
						}, 
						{
							type: 'all',
							text: 'All'
						}],
						
						buttonTheme: { // styles for the buttons
						fill: 'none',
						stroke: 'none',
						'stroke-width': 0,
						r: 2,
						style: {
							color: '#555',
							fontWeight: 'bold'
						},
						states: {
							hover: {
							},
							select: {
								fill: '#888',
								style: {
									color: 'white'
								}
							}
						}
					},
					},
						
					chart:{
					type: "line",
					//height: $(document).height(),
					},
					
					xAxis:{
					type: 'datetime',
						labels:{
						rotation: -45,
						},
					units: [
						['hour',[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]]
					],
					title: {
						text: 'Date/Time - UTC'
						},
					//gridLineColor: "#D8D8D8",
					gridLineDashStyle: "Solid",
					gridLineWidth: 2,
					},
					
					yAxis: [{
						labels: {
						style: {color: ["#2b908f"],},
							// align: 'right',
							// x: -3
						},
						title: {
							style:{
								color: '#2b908f'
							},
							text: 'X-Axis'
						},
						height: graphHeight,
						
						// lineWidth: 2
					}, 
					{
						labels: {
							style: {color: ["#90ee7e"]},
							// align: 'right',
							// x: -3
						},
						title: {
							style: {
								color: '#90ee7e'
							},
							text: 'Y-Axis'
						},
						// top: '25%',
						height: graphHeight,

						// offset: 0,
						// lineWidth: 2
					},
					{
						labels: {
						style: {color: ["#f45b5b"],}
							// align: 'right',
							// x: -3
						},
						title: {
							style:{
								color: '#f45b5b'
							},
							text: 'Z-Axis'
						},
						// top: '50%',
						height: graphHeight,
						// offset: 1,
						// lineWidth: 2
					},
					{
						// labels: {
						// align: 'right',
						// x: -3
						// },
						title: {
							style:{
								color: '#7798BF'
							},
							text: 'Total Field'
						},
						top: '50%',
						height: graphHeight,
						offset: 2,
						lineWidth: 2
					}],

					series: [{
						type: 'line',	
						name: 'X-Axis',
						data: [<?php echo join($dataX, ',')?>],
						yAxis: 0
					},
					{
						type: 'line',
						name: 'Y-Axis',
						data: [<?php echo join($dataY, ',')?>],
						yAxis: 1
					},
					{
						type: 'line',
						name: 'Z-Axis',
						data: [<?php echo join($dataZ, ',')?>],
						yAxis: 2
					},
					{
						type: 'line',
						name: 'Total Field',
						data: [<?php echo join($dataF, ',')?>],
						yAxis: 3
					}]
				});
			});
				
		</script>
	
	</head>
  
	<body>

	<!-- Page Header -->
		<header>

		<?php
		include 'menu.php'
		?>
		
		</header>
		<!-- /.header -->
		
		<!-- The container for the chart -->
		<div class="row-fluid">
				<div class="span12">	
					<div id="magContainer"></div>
				</div>
		</div>



    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	
	<!-- Google Analytics Script -->
	<script type="text/javascript" src="js/googleanalyticsscript.js"></script>

	</body>
</html> 
