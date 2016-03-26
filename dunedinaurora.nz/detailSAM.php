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
		<!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
		<script src="http://code.highcharts.com/modules/exporting.js"></script>
		
		<!-- Bootstrap Core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		
		<!-- Custom CSS -->
		<link href="css/portfolio-item.css" rel="stylesheet">
		
<title>Dunedin Aurora - SAM Fluxgate Magnetometer</title>
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

		$selectQuery = "SELECT * FROM DataXYZ WHERE DataXYZ.timestamp > DATE_SUB(NOW(), INTERVAL 24 HOUR);";
		$result = mysqli_query($connection, $selectQuery);
		
		// // Grab the first row, normalise the rest of the graph to this
		// $row = mysqli_fetch_array($result);
		// $normalX = ($row['dataX']) * -1;
		// $normalY = ($row['dataY']) * -1;
		// $normalZ = ($row['dataZ']) * -1;
		
		while ($row = mysqli_fetch_array($result)) 
		{	
			$xTitles[] = '"'.$row['timestamp'].'"';
			// $dataX[] = floatval($row['dataX']) + $normalX;
			// $dataY[] = floatval($row['dataY']) + $normalY;
			// $dataZ[] = floatval($row['dataZ']) + $normalZ;
			
			$dataX[] = floatval($row['dataX']);
			$dataY[] = floatval($row['dataY']);
			$dataZ[] = floatval($row['dataZ']);
		}
		?>
		
		<script>	
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

		
		$(function () {
			$('#magContainer').highcharts({
				  chart: {
					//height: $(document).height(),
					type: 'line',
					renderTo: 'magContainer'
				  },
			    tooltip: {
					crosshairs: {
						width: 3
					}
				},  
				  title: {
					text: '24 Hour Plot, Fluxgate Magnetometer, Portobello, Dunedin'
					},
				  xAxis:{
					categories: [<?php echo join($xTitles, ',')?>]
				  },
			yAxis: [{ // Primary yAxis
				title: {
					text: 'X axis',
					rotation: 0,
					style: {
						color: Highcharts.getOptions().colors[0]
						}
					},
				labels: {
					style:{
						color: Highcharts.getOptions().colors[0]
					}
				}
			}, 
			{ // Secondary yAxis
				title: {
					text: 'Y Axis',
					rotation: 0,
					style: {
						color: Highcharts.getOptions().colors[1],
					}
				},
				labels: {
					style:{
						color: Highcharts.getOptions().colors[1]
					}
				},
				opposite: true
			},
			{ // Tertiary yAxis
				title: {
					text: 'Z Axis',
					rotation: 0,
					style: {
						color: Highcharts.getOptions().colors[2]
						}
					},
				labels: {
					style:{
						color: Highcharts.getOptions().colors[2]
					}
				},
				opposite: true
			}],
			
			series: [
				{
				name: 'X axis',
				yAxis: 0,
				data: [<?php echo join($dataX, ',')?>],
				
				},
			
				{
				name: 'Y axis',
				yAxis: 1,
				data: [<?php echo join($dataY, ',')?>],
				},
			
				{
				name: 'Z axis',
				yAxis: 2,
				data: [<?php echo join($dataZ, ',')?>],
				}
			]
			})
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
		<div class="row-fluid">
			<div id="magContainer"></div>
		</div
		

		<!-- Bootstrap Core JavaScript -->
		<script src="js/bootstrap.min.js"></script>
		
		<!-- Google Analytics Script -->
		<script type="text/javascript" src="js/googleanalyticsscript.js"></script>

		<footer>

		</footer>

	</body>
</html> 
