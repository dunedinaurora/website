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
		
		// For the gauge - low and moderate reading thresholds - these will need to be optimised in usage.
		var fieldLow = 1;
		var fieldMod = 5;
		var fieldStatus;
			
		// Take the passed in data
		var magdata = $.parseJSON(data);
		
		// Create the array of absolute differences between readings. Follows the formula: sqrt((Reading2 - Reading1)^2)
		var diffs = [];
		
		for (var  i = 1; i < magdata.length; i++)
		{
			diffs[i-1] = Math.sqrt(Math.pow((magdata[i] - magdata[i-1]),2));
			
		}
		
		// Ok - We want to create an array of the average readings binned in minute intervals. 
		var MinutelyAverages = [];
		var tempavg = 0;
		var counter = 0;
		
		// Bin the readings into minute intervals
		for (var i = 0; i < diffs.length; i++)
		{
			tempavg = tempavg + diffs[i];
			counter++;
			// We will average the sum of four readings and write this to MinutelyAverages
			if (counter == 4)
			{
				tempavg = tempavg / counter;
				MinutelyAverages.push(tempavg);
				counter = 0;
				tempavg = 0;
			}
		}
		GeoMagDisturbance = MinutelyAverages[MinutelyAverages.length - 1];
		
		
		//Ok, now we set the status of the dial text, depending on the value of GeoMagDisturbance
		if (GeoMagDisturbance <= fieldLow)
		{
			fieldStatus = "<span style=\"color:green;\">Field is quiet</span>";
		}
		else if (GeoMagDisturbance >= fieldLow && GeoMagDisturbance < fieldMod)
		{
			fieldStatus = "<span style=\"color:#ff9900;\">Moderate Activity</span>";
		}
		else
		{
			fieldStatus = "<span style=\"color:red;\">HIGH ACTIVITY!!</span>";
		}
	
		// Chart A Update
		var chartAData = [];
		for (var i = 0; i < MinutelyAverages.length; i++)
		{
			// Data is an array of arrays of two elements
			var dataSeries = [];
			dataSeries.push(0); // add the 1 
			dataSeries.push(parseFloat(MinutelyAverages[i].toFixed(2))); // add the data value
			chartAData.push(dataSeries);
		}
		chartA.series[0].setData(chartAData);
		
		//Chart G Update
		chartG.series[0].setData([GeoMagDisturbance]);
		chartG.series[0].update({
			dataLabels: {
				format: '<div style="text-align:center"><span style="font-size:20px;color:' +
				((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">' + GeoMagDisturbance.toFixed(2) + " nT" +'</span><br/>' +
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
	                [0, '#ffffff'],
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
            max: 5,
            title: {
                style: {
                    "color": "black"
                },
                text: 'Geomagnetic Activity' + '<br>' + 'Five minute average'
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
                valueSuffix: ' km/h'
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
				categories: ["8 mins ago","7 mins ago","6 mins ago","5 mins ago","4 mins ago","3 mins ago","2 mins ago","1 min ago", "Now"],
				labels:{
					rotation: 45
					},
				},
			tooltip: {
						valueSuffix: ' nT'
					},
			yAxis: {
				categories: [''],
				title: "Time",
				},

			colorAxis: {
				min: 0,
				max: 2,
				stops: [
	                [0, '#ffffff'],
					[0.25, '#ffffff'],
					[0.5, '#00ff00'],
	                [0.55, '#ffff00'],
	                [1, '#ff5500']
            	]
			},
			legend:{
				title: {
					text: "Activity (nT)",
				}, 
				y:20
			},
			series: [{
				name: 'Rate of change',
				borderWidth: 2,
				borderColor: '#bfbfbf',
				data: [],
				dataLabels: {
					enabled: false,
					color: '#000000'
				}
			}]

		});
	
	})
 	