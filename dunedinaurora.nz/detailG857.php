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
		
		<!-- Bootstrap Core JavaScript -->
		<script src="js/bootstrap.min.js"></script>
		
		
		<title>Dunedin Aurora - Precessing Proton Magnetometer</title>
		
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
		
<script>		
	// The PHP file returns a multidimensional array. Each element is [datetime, reading]
	
	var chartG857;		// defined globally
	
	function RequestData() {
		$.ajax({
			type: "POST",
			url: 'inc/detailG857Data.php',
			datatype: "json",
			success: function(data) {
			// Define the charts
			chartG857 = $('#magContainer').highcharts();

			// Take the passed in data format as JSON 
			var magdata = $.parseJSON(data);
			
			// Create the array that will hold the average readings
			var displayAverage = [];
			var categoryText= [];
			var counter = 0;
			var avgValue = 0;
			
			// ######### AVERAGING #########
			// A running average of the data. Not quicker to display, but will smooth out the jaggedies
			var AVG_LENGTH = 24; // 2 minutes @ 6x a minute
			
			for (var i = AVG_LENGTH; i < magdata.length; i++)
			{
				for (var j = 0; j < AVG_LENGTH; j++)
				{
					avgValue = avgValue + parseFloat(magdata[i-j][1]);
				}
					avgValue = avgValue / AVG_LENGTH;
					displayAverage.push(parseFloat(avgValue.toFixed(2)));
					categoryText.push(magdata[i][0]);
					avgValue = 0;
			}
			
			//alert(displayAverage)
			// Currently binning in intervals and plotting the average of each bin, to smooth out data and to
			// speed up graph display 
			// for (var i = 0; i < magdata.length; i++)
			// {
				// avgValue = avgValue + magdata[i][1];
				// counter++;
				// if (counter == AVG_LENGTH)
				// {
					// avgValue = avgValue / AVG_LENGTH;
					// displayAverage.push(parseFloat(avgValue.toFixed(2)));
					// categoryText.push(magdata[i][0]);
					// avgValue = 0;
					// counter = 0;
				// }
			// }
			// ######### END OF AVERAGING #########
			
			
			//document.write(displayAverage);
			chartG857.xAxis[0].setCategories(categoryText);
			chartG857.series[0].setData(displayAverage);

			
			// Redraw the chart!
			chartG857.redraw();
			
			// call it again after one second
			setTimeout(RequestData, 120000); 			
			},

			cache: false
		});
	}
	
			
	$(document).ready(function () {
		$('#magContainer').highcharts({
			chart: {
				//height: $(document).height(),
				type: 'line',
				renderTo: 'magContainer',
				events:{
						load:RequestData()
					}
			},
			tooltip: {
				crosshairs: {
					width: 3
				}
			},
			  title: {
				text: '24 Hour Plot, Precessing Proton Magnetometer, Portobello, Dunedin'
				},
			xAxis:{
			categories: ["Loading Data..."]
			},
			yAxis: { 
				title: {
					text: 'Total Field - nanoTesla',
					style: {
						color: Highcharts.getOptions().colors[3]
					}
				}
			},
			series: [{
				name: 'Total Field (Averaged)',
				data: [0],
				color: Highcharts.getOptions().colors[3]
				},
			]})
	});
	
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


</script>
	</head>
  
	<body>

	<!-- Page Header -->
		<header>
				    <!-- Navigation -->
			<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="index.php">Dunedin Aurora</a>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li>
								<a href="index.php">Home</a>
							</li>
							<li>
								<a href="beginner.html">For beginners...</a>
							</li>
							<li>
								<a href="graph.php">Graphs</a>
							</li>
							<li>
								<a href="about.html">About Project Helios</a>
							</li>
						</ul>
					</div>
					<!-- /.navbar-collapse -->
				</div>
				<!-- /.container -->
			</nav>

		
		</header>
		
		<div class="row-fluid">
			<div id="magContainer" ></div>
		</div>

		<footer>
		</footer>

	</body>
</html> 
