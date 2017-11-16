<?php
	include('db_config.php');
	session_save_path('../session_tmp');
	session_start();
?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>DBMS Project</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/index.css">
</head>

<body>

	<div class="header">
		<div>Hunt, or <span>Hunted?</span></div>
	</div>
	<br/>
	<div class="login">
		<form action="login.php" method="post">
			<input type="text" placeholder="account" name="account" id="usrBar"><br><br>
			<input type="password" placeholder="password" name="password" id="pswBar"><br>
			<button type="submit" id="loginBtn" class="btn btn-default">Log In</button><br>
		</form>
	</div>
	<div class="register">
		<button id="employer-btn" class="btn btn-default" data-toggle="modal" data-target="#employer-register-modal">
		  Sign Up For Employer
		</button>
		<button id="jobseeker-btn" class="btn btn-default" data-toggle="modal" data-target="#jobseeker-register-modal">
		  Sign Up For Job Seeker
		</button>
	</div>
	<div class="no-account">
		<form action="anonymous.php" method="post">
			<button id="no-account-btn">
				See Job Vacancies
			</button>
		</form>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="employer-register-modal" tabindex="-1" role="dialog" aria-labelledby="employer-modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" id="employer-register">
			</div>
		</div>
	</div>

	<div class="modal fade" id="jobseeker-register-modal" tabindex="-1" role="dialog" aria-labelledby="jobseeker-modal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" id="jobseeker-register">
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script src="js/index.js"></script>
</body>
</html>