<?php
	include('db_config.php');
	session_save_path('../session_tmp');
	session_start();
	
	$id=$_SESSION['id'];
	$occ=$_POST['occupation'];
	$loc=$_POST['location'];
	$work=$_POST['working_time'];
	$edu=$_POST['education'];
	$exp=$_POST['experience'];
	$sal=$_POST['salary'];

	$sql="INSERT INTO recruit (employer_id,occupation_id,location_id,working_time,education,experience,salary)
			VALUES(?,?,?,?,?,?,?)";
	$sth=$db->prepare($sql);
	$sth->execute(array($id,$occ,$loc,$work,$edu,$exp,$sal));
	echo 1;
?>