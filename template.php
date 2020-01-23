<!DOCTYPE html PUBLIC>
<html xml:lang="en" lang="en">

<head>
    <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  <meta name="description" content="">
	  <meta name="author" content="">

	  <title>ANALISA FP-GROWTH</title>

	  <!-- Custom fonts for this template-->
	  <link href="<?php echo $urlaplikasi;?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	  <!-- Custom styles for this template-->
	  <link href="<?php echo $urlaplikasi;?>assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top" style="background-color: #36b9cc;">
	<?php 
		if(!isset($_SESSION['LOGIN_ID'])){
		eval($content);
	}else{
	?>
	<div id="wrapper">
		<?php include 'sidebar.php';?>
			    <div id="content-wrapper" class="d-flex flex-column">
			    	<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
					    <!-- Sidebar Toggle (Topbar) -->
					    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
					        <i class="fa fa-bars"></i></button>
			        </nav>
      			<!-- Main Content -->
      			<div id="content" style="padding-left: 20px; padding-right: 20px;">
					<?php eval($content);?>
				</div>
				</div>
	<?php
	}
	?>

	<div class="footer">

      <script src="<?php echo $urlaplikasi;?>assets/vendor/jquery/jquery.min.js"></script>
		<script src="<?php echo $urlaplikasi;?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

		  <!-- Core plugin JavaScript-->
		<script src="<?php echo $urlaplikasi;?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

		  <!-- Custom scripts for all pages-->
		<script src="<?php echo $urlaplikasi;?>assets/js/sb-admin-2.min.js"></script>

		  <!-- Page level plugins -->
		<script src="<?php echo $urlaplikasi;?>assets/vendor/chart.js/Chart.min.js"></script>

		  <!-- Page level custom scripts -->
		<script src="<?php echo $urlaplikasi;?>assets/js/demo/chart-area-demo.js"></script>
		<script src="<?php echo $urlaplikasi;?>assets/js/demo/chart-pie-demo.js"></script>
    </div>
    </div>
  </body>

</html>
