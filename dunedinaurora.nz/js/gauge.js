var magValue;
var GeoMagDisturbance = 0;


/**
 * Request data from the server, add it to the graph and set a timeout 
 * to request again
 */
function RequestData() {
    $.ajax({
		type: "POST",
        url: 'inc/gaugeData.php',
		datatype: "json",
        success: function(data) {

			
		// Define the charts
		var chartG = $('#MagField').highcharts();
		var chartA = $('#ActivityChart').highcharts();
		var timeInterval = 16; // The length of time for the differences calculation. Shorter intervals are more fine grained, but noisier.
		
		// For the gauge - low and moderate reading thresholds - these will need to be optimised in usage.
		var fieldLow = 60; // under this, field is quiet
		var fieldMod = 100; // up to this, moderate activity
		var fieldHigh = 400; // up to this, Heightened activity - aurora may be present
		var fieldStatus;
			
		// Take the passed in data
		var magdata = $.parseJSON(data);
		
		// Ok - We want to create an array of the average readings binned in minute intervals. 
		var MinutelyAverages = [];
		var tempavg = 0;
		var counter = 0;
		
		// Bin the readings into minute intervals
		for (var i = 0; i < magdata.length; i++)
		{
			tempavg = tempavg + magdata[i];
			counter++;
			// We will average the sum of four readings and write this to MinutelyAverages
			if (counter == 6)
			{
				tempavg = tempavg / counter;
				MinutelyAverages.push(tempavg);
				counter = 0;
				tempavg = 0;
			}
		}
		
		// OK - we now have our array of minutely averages the length of which is defined by gaugeData.php. What we want now is another array
		// of the DIFFERENCES between Reading(x) and Reading(x-timeInterval). This difference will be squared, to exaggerate high activity
		// and suppress noiser lower level activity. This new array will be approx half the size of MinutelyAverages.
		var graphData = [];
		
		// We will start from the most current reading, and go backwards. We will need to reverse the graphData array.
		for (var i = MinutelyAverages.length - 1; i > timeInterval; i--)
		{
			var j =  Math.pow((MinutelyAverages[i] - MinutelyAverages[i - timeInterval]), 2); //Square the answer
			graphData.push(j); //append to graphData Array
		}
		// The array is currently newest -> oldest, we want to reverse this so the gauge displays correctly
		graphData.reverse(); 
		
		// Value to be applied to Gauge
		GeoMagDisturbance = graphData[graphData.length - 1];
		
		
		// We will dynamically generate the categories for this activity graph.
		var categoryText = [];
		
		for (var i = 0; i <= graphData.length -1; i++)
		{
			if (i == graphData.length -1)
			{
				categoryText.push("Now");
			}
			else
			{
				categoryText.push(((graphData.length - 1)- i) + ' mins ago');
			}
		}
		
		
		//Ok, now we set the status of the dial text, depending on the value of GeoMagDisturbance
		if (GeoMagDisturbance <= fieldLow)
		{
			fieldStatus = "<span style=\"color:green;\">Field is quiet</span>";
		}
		else if (GeoMagDisturbance >= fieldLow && GeoMagDisturbance < fieldMod)
		{
			fieldStatus = "<span style=\"color:#ff9900;\">Moderate Activity</span>";
		}
		else if (GeoMagDisturbance >= fieldMod && GeoMagDisturbance < fieldHigh)
		{
			fieldStatus = "<span style=\"color:yellow;\">Heightened Activity<br>Likely aurora conditions</span>";
		}
		else
		{
			fieldStatus = "<span style=\"color:red;\">High Activity<br>Aurora possible</span>";
		}
	
		// Chart A Update
		var chartAData = [];
		for (var i = 0; i < graphData.length; i++)
		{
			// Data is an array of arrays of two elements
			var dataSeries = [];
			dataSeries.push(0); // add the 1 
			dataSeries.push(parseFloat(graphData[i].toFixed(2))); // add the data value
			chartAData.push(dataSeries);
		}
		chartA.series[0].setData(chartAData);
		chartA.xAxis[0].setCategories(categoryText);
		
		//Chart G Update
		chartG.series[0].setData([GeoMagDisturbance]);
		chartG.series[0].update({
			dataLabels: {
				format: '<div style="text-align:center"><span style="font-size:20px;color:' +
				((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">' + Math.sqrt(GeoMagDisturbance).toFixed(2) + " nT" +'</span><br/>' +
				'<span style="font-size:12px;color:silver">'+ fieldStatus +'</span></div>'
				}
		})
		
		// Redraw all the charts.
		chartG.redraw();
		chartA.redraw();
							
		// call it again after one second
		setTimeout(RequestData, 30000); 			
        },
        cache: false
    });
}

//DRAW THE GRAPH
	$(document).ready(function() {
	// chart goes here.
	
	
	var gaugeOptions = {

        chart: {
            type: 'solidgauge',
            backgroundColor: 'transparent',
			events:{
				load:RequestData()
			}
        },

        title: null,

        pane: {
            center: ['50%', '85%'],
            size: '140%',
            startAngle: -90,
            endAngle: 90,
            background: {
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#868686',
                innerRadius: '60%',
                outerRadius: '100%',
                shape: 'arc'
            }
        },

        tooltip: {
            enabled: false
        },

        // the value axis
        yAxis: {
            stops: [
	                [0, '#707070'],
					[0.2,'#f0f0ff'],
					[0.25, '#ffffff'],
					[0.5, '#00ff00'],
	                [0.55, '#ffff00'],
	                [1, '#ff5500']
            ],
            lineWidth: 0,
            minorTickInterval: null,
            tickPixelInterval: 400,
            tickWidth: 0,
            title: {
                y: -90
            },
            labels: {
                y: 16
            }
        },

        plotOptions: {
            solidgauge: {
				area:{
					animation: true,
				},
                dataLabels: {
                    y: 5,
                    borderWidth: 0,
                    useHTML: true
                },
				series: {
					animation: {
						duration: 2000
					}
				}
            }
        }
    };
	
    $('#MagField').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
            min: 0,
            max: 500,
            title: {
                style: {
                    "color": "black"
                },
                text: 'Geomagnetic Activity<br><em>This gauge is experimental, use with caution.</em>'
            }
        },

        credits: {
            enabled: false
        },

        series: [{
            name: 'Field Change',
            data: [GeoMagDisturbance],
            dataLabels: {
            },
            tooltip: {
                valueSuffix: ''
            }
        }]

    }));
	
			$('#ActivityChart').highcharts({

			chart: {
				type: 'heatmap',
				backgroundColor: 'transparent',
				height: 170,
				margin: [10, 10, 130, 10]
			},
			
			credits: {
				enabled: false
			},
			
			title: {
				text: null
			},

			xAxis: {
				categories: [],
				labels:{
					rotation: 45
					},
				},
			tooltip: {
						valueSuffix: ' nT^2'
					},
			yAxis: {
				categories: [''],
				title: "Time",
				},

			colorAxis: {
				min: 0,
				max: 500,
				stops: [
	                [0, '#707070'],
					[0.2,'#f0f0ff'],
					[0.25, '#ffffff'],
					[0.5, '#00ff00'],
	                [0.55, '#ffff00'],
	                [1, '#ff5500']
            	]
			},
			legend:{
				title: {
					text: "Activity (nT^2)",
				}, 
				y:20
			},
			series: [{
				name: 'Rate of change',
				borderWidth: 2,
				borderColor: '#bfbfbf',
				data: [0,0,0,0,0,0,0,0,0], //Otherwise the graph does not appear when the page is first loaded
				dataLabels: {
					enabled: false,
					color: '#000000'
				}
			}]

		});
	
	})
 	