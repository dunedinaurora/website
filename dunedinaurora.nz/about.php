<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dunedin Aurora - About</title>

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
                <h1 class="page-header">About Project Helios
                    <small>Collaboration In Science</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

			<div class="col-md-7">
				<div class="thumbnail with-caption"> 
					<img class="img-responsive" src="images/compost-bins_643x482.jpg" alt="photo of the compost bins housing the magnetometers">
					<p>The magnetometers live inside here, we promise.<small>Copyright 2015 &copy; Project Helios</small></p> 
				</div>
            </div>
		
            <div class="col-md-5">
                <h3>Project Helios is...</h3>
                <p>Project Helios grew from a meeting of minds and ideas between the Dr Ian Griffin, Director of the Otago Museum and Otago Polytechnic students Vaughn Malkin and Chris Campbell.</p>
				<p>We (Project Helios) are Chris Campbell and Vaughn Malkin. We're completing the capstone project for our final year of the Bachelor in Information Technology (BIT) at Otago Polytechnic. Alongside Otago Museum, we are developing a system to provide the people of Dunedin (and the world) real-time, local geomagnetic data and an early warning for when the Aurora Australis is visible.</p>
				
				<h3>The hardware and software setup</h3>
				<p>The two magnetometers at the core of our project are a 3-axis fluxgate device from the <a href="http://www.sam-magnetometer.net/">SAM Magnetometer Project</a> and a G-857 precessing proton magnetometer from <a href="http://www.geometrics.com/geometrics-products/geometrics-magnetometers/g-857-magnetometer/">Geometrics</a>. Both of these devices talk to on-site hardware where we use custom software to collect the data, process and database it. Our website then displays this information in real-time</p>

                <h3>Wishlist and To-Do's</h3>
				<p>There are a few things we'd like to add to our site. They include:</p>
				<ul>
					<li>Simple web service to provide the most current 5 minutes of data (JSON and CSV format).</li>
					<li>Extra graphs showing Horizontal component, Declination and Inclination.</li>
					<li>Downloading of data with user-defined dates.</li>
					<li>Fully integrate an All-Sky camera to provide live feed of the sky</li>
					<li></li>
				</ul>
            </div>
			

        </div>
        <!-- /.row -->

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Project Helios 2015</p>
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

</body>

</html>
