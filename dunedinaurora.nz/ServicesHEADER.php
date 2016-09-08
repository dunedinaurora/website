<?PHP
// This is a standard header to be included in ALL web services files. The following must be echoed out. 
$testString = "Here is some text";

switch ($fileType)
{
	case "JSON":
		header("Content-Type: text/plain");
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
		header("Content-Type: text/plain");
		echo("# CSV formatted file of the latest ".$queryIntervalMinutes." minutes of data from full field magnetometer.\r\n");
		// Copyright to be sorted with Museum. At least attribution for fair use
		echo("# COPYRIGHT: http://dunedinaurora.nz/licence.html\r\n");
		echo("# Station: Otago Museum - Portobello\r\n");
		echo("# Magnetometer: Geometrics G857 Precessing Proton\r\n");
		echo("# latitude: -45.8398\r\n");
		echo("# longtitude: 170.6509\r\n");
		echo("# www: DunedinAurora.NZ\r\n");
		break;
		
	default:
}
?>