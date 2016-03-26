<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dunedin Aurora - Home</title>

	<!-- Bootstrap Core JavaScript -->
	<script src="js/bootstrap.min.js"></script>

	<!-- 1. Add these JavaScript inclusions in the head of your page -->
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

		<?php
		// Include connection information and user defined functions
		include 'connect.inc.php';
		?>
		<!-- 
		<script src="http://code.highcharts.com/highcharts.js"></script>
		<script src="http://code.highcharts.com/highcharts-more.js"></script>
		<script src="http://code.highcharts.com/modules/solid-gauge.js"></script>
		<script src="http://code.highcharts.com/modules/heatmap.js"></script>
		-->
		
		<script src="js/highcharts.js"></script>
		<script src="js/highcharts-more.js"></script>
		<script src="js/solid-gauge.js"></script>
		<script src="js/heatmap.js"></script>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/portfolio-item.css" rel="stylesheet">

</head>

<body>
    <!-- Navigation -->
		<?php
		  include 'menu.php'
		?>


    <!-- Page Content -->
    <div class="container">

        <!-- Portfolio Item Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Welcome To Dunedin Aurora
                    <small>Bringing The Aurora To Life</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

    <!-- Portfolio Item Row -->
    <div class="row">

			<div class="col-md-8">
				<div class="thumbnail with-caption">
					<img class="img-responsive" src="images/landing-aurora-750x500.jpg" alt="">
					<p>St Patrick's Day Aurora - 2015<small>Copyright 2015 &copy; Ian Griffin </small></p>
				</div>
      </div>

      <div class="col-md-4">
				<!-- DIAL GAUGE -->
				<script src="js/gauge.js"></script>

							<div id="MagField" style="width: 300px; height: 210px; float: left; margin: 0 auto;"></div>
							<div id="ActivityChart" style="height: 300px; min-width: 300px; max-width: 300px; margin: 0 auto; float: left;"></div>

					<a class="btn btn-success" href="graph.php" role="button">Magnetometer Graphs</a>
					<!-- Trigger the modal with a button -->
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal1"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></button>


					<!-- Modal -->
					<div id="myModal1" class="modal fade" role="dialog">
					  <div class="modal-dialog">
  						<!-- Modal content-->
  						<div class="modal-content">
  						  <div class="modal-header">
    							<button type="button" class="close" data-dismiss="modal">&times;</button>
    							<h4 class="modal-title">Magnetometer Graphs</h4>
  						  </div>
  						  <div class="modal-body">
    							<p>A magnetometer is like a super-sensitive compass. It can show tiny changes in the strength and direction of the Earth's magnetic field.</p>
    							<p>These changes are usually caused by charged particles from the sun hitting the Earth's magnetic field. If a large amount of these particles get funnelled towards the magnetic poles, we get an aurora.</p>
  						  </div>
  						  <div class="modal-footer">
  							  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  						  </div>
  						</div>
					  </div>
					</div>
				</div> <!-- /.col-md4 -->

      </div> <!-- /.row -->

        <br>
        <div class="row">
          <h3>Project Description</h3>
            <p>Dunedin Aurora aims to answer the question: <strong>"When can I see an aurora?"</strong></p>
            <p>This website is a work in progress and is likely to change. For example, we hope to implement a simple dashboard system to replace the magnetometer charts, to make it easy for people.</p>
            <p>You can find more info about this project over <a href="about.php">here</a>.</p>

          <h3>Can I see an Aurora?</h3>
              <p>To answer that question, we are currently using devices called <em>magnetometers</em>. A magnetometer shows tiny changes in the Earth's magnetic field, which sometimes occur when an aurora is happening. Click the green button above to go straight to the magnetometer graphs, or head over to the '<a href="beginner.php">For Beginners</a>' page to find out more information.</p>
        </div>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Project Helios 2015</p>
                </div>
            </div>
            <!-- /.row -->
        </footer>

    </div> <!-- /.container -->


    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

	<!-- Google Analytics Script -->
	<script type="text/javascript" src="js/googleanalyticsscript.js"></script>

</body>

</html>
