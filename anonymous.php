<?php
	include('db_config.php');
	session_save_path('../session_tmp');
	session_start();

	$_SESSION['kind'] = -1;

	$navList = 
		'<hr class="style-one">
		<li class="">
			<div class="header">
				<div>Hunt, or <span>Hunted?</span></div>
			</div>
		</li>
		<li class="active">
			<a href="" id="anonymous">
				<i class="fa fa-list"></i>
				<span class="navbar-left-category">&nbsp;Job List</span>
			</a>
		</li>
		<li class="">
			<a href="./logout.php" id="logout">
				<i class="fa fa-sign-out"></i>
				<span class="navbar-left-category">&nbsp;Log out</span>
			</a>
		</li>';
		$pageList = '<div class="row anonymous-content"></div>';
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Home</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/home.css">
</head>

<body>

	<div class="container-fluid">

		<div class="row">
			<div class="col-md-3">
				<div class="sidebar-nav">
					<div class="navbar navbar-default" role="navigation">
						<div class="navbar-collapse collapse sidebar-navbar-collapse">
							<ul class="nav navbar-nav">
							<?php echo $navList; ?>
							</ul>
						</div><!--/.nav-collapse -->
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="row loading-content"><img src="img/loading.svg"></div>
				<div class="row profile-content hidden"></div>
				<?php echo $pageList; ?>
			</div>
		</div>

	</div>
		
	<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script src="js/anonymous.js"></script>
</body>
</html>