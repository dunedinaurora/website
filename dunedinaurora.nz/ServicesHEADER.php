<?PHP
// This is a standard header to be included in ALL web services files. The following must be echoed out. 
$testString = "Here is some text";

switch ($fileType)
{
	case "JSON":
		echo('"Description": "JSON formatted file of the latest '.$queryIntervalMinutes.' minutes of data from full field magnetometer.",');
		
		// Copyright to be sorted with Museum. At least attribution for fair use
		echo('"COPYRIGHT": "http://dunedinaurora.nz/licence.html",');
		echo('"Station": "Otago Museum - Portobello",');
		echo('"Magnetometer": "Geometrics G857 Precessing Proton",');
		echo('"latitude": -45.8398,');
		echo('"longtitude": 170.6509,');
		echo('"www": "DunedinAurora.NZ",');
		break;
		
	case "CSV":
		echo('# CSV formatted file of the latest '.$queryIntervalMinutes.' minutes of data from full field magnetometer.'."<br>");
		
		// Copyright to be sorted with Museum. At least attribution for fair use
		echo('# COPYRIGHT: http://dunedinaurora.nz/licence.html'."<br>");
		echo('# Station: Otago Museum - Portobello'."<br>");
		echo('# Magnetometer: Geometrics G857 Precessing Proton'."<br>");
		echo('# latitude: -45.8398'."<br>");
		echo('# longtitude: 170.6509'."<br>");
		echo('# www: DunedinAurora.NZ'."<br><br>");	
		break;
		
	default:
}
?>