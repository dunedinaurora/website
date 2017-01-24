<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dunedin Aurora - Sky Camera No 2</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/portfolio-item.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
</head>

<body>

<?php
include 'menu.php'
?>

    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Skycam 2</h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">		
					<div style="border-style: solid; 
								border-radius: 3px;
								border-color: #707070;					
								padding: 3em;
								background-color: #999999;
								margin: auto;">
								<img class="img-responsive" id = "skycam2" src = "http://188.166.235.64/downscaled.png" style="display: block; margin-left: auto; margin-right: auto">
					</div>
				<p>Skycam 2 is located in suburban Dunedin, New Zealand. It faces south and lower edge of the field of view is approx 24 deg above the horizon to miss the glare from neighbours and the city lights. This is experimental, and may break without warning ;-)</p>
				<p>Specifications</p>
				<ul>
					<li>Hardware - ZWO Company, ASI 120 MC-S colour imaging camera fitted with a 4mm CCD camera lens</li>
					<li>Software - <a href="http://www.sharpcap.co.uk/">SharpCap</a> imaging software with customised python scripts to manage the image capture and upload</li>
				</ul>

        </div>
        <!-- /.row -->

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Project Helios 2017</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
	
	<!-- Google Analytics Script -->
	<script type="text/javascript" src="js/googleanalyticsscript.js"></script>

	<script>
	function imgReload() {
		var d = new Date();
		var newImgURL = "http://188.166.235.64/downscaled.png#" + d.getTime();
		document.getElementById('skycam2').innerHTML = '<img class="img-responsive" id = "skycam2" src = "' + newImgURL + '" style="display: block; margin-left: auto; margin-right: auto">';

		// window.alert(document.getElementById('skycam2').innerHTML)
		
		setTimeout("imgReload()", 300000);
		return;
	}
	
	imgReload();
	</script>
	
</body>

</html>
