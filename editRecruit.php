<?php
	include('db_config.php');
	include('employerList.php');
	session_save_path('../session_tmp');
	session_start();
		
	$id = $_POST['primary_key'];
	$occ = $_POST['occupation'];
	$loc = $_POST['location'];
	$work = $_POST['work'];
	$edu = $_POST['education'];
	$exp = $_POST['experience'];
	$sal = $_POST['salary'];

	switch($_POST['edit']){
		case "0":	
			if($sal==''){
				echo "<script>alert('Please enter the salary.')</script>";
				break;
			}else{
				$sql = "UPDATE recruit SET occupation_id=:occupation_id,
										   location_id=:location_id,
										   working_time=:working_time,
										   education=:education,
										   experience=:experience,
										   salary=:salary 
									 WHERE id=:id";
				$sth = $db->prepare($sql);
				$sth->bindParam(':occupation_id',$occ, PDO::PARAM_INT);
				$sth->bindParam(':location_id',$loc, PDO::PARAM_INT);
				$sth->bindParam(':working_time',$work,PDO::PARAM_INT);
				$sth->bindParam(':education',$edu,PDO::PARAM_INT);
				$sth->bindParam(':experience',$exp,PDO::PARAM_INT);
				$sth->bindParam(':salary',$sal,PDO::PARAM_INT);
				$sth->bindParam(':id',$id,PDO::PARAM_INT);
				
				$sth->execute();
				break;
			}
		case "1":
			$sql = "DELETE FROM recruit WHERE id=:id";
			$sth = $db->prepare($sql);
			$sth->bindParam(':id',$id,PDO::PARAM_INT);
			$sth->execute();
	}
	echo 1;
?>