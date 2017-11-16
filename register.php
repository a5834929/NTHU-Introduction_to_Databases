<?php
	include('db_config.php');
	session_save_path('../session_tmp');
	session_start();
		
	$account = $_POST['account'];
	$password = $_POST['password'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$kind = $_POST['kind'];

	if($account!='' && $password!='' && $phone!='' && $email!=''){
		switch($kind){
			case "0"://employer
				//check if the account used
				$sql = "SELECT account FROM employer";
				$pdo = $db->prepare($sql);
				$pdo->execute();
				while($result = $pdo->fetchObject()){
					if($result->account==$account){
						echo "<script>alert('The account has been registered.')</script>";
						echo "<meta http-equiv='Refresh' content='0;url=".$DNS."/index.php'>";
						exit;
					}
				}
				
				$newpa = md5($password);
				$sql = "INSERT INTO employer (account,password,phone,email) VALUES (?,?,?,?)";
				$pdo = $db->prepare($sql);
				$pdo->execute(array($account,$newpa,$phone,$email));

				$sql = "SELECT id FROM employer WHERE account=?";
				$pdo = $db->prepare($sql);
				$pdo->execute(array($account));
				$result = $pdo->fetchObject();
				$id = $result->id;
				
				$_SESSION['id'] = $id;
				$_SESSION['account'] = $account;
				$_SESSION['kind'] = $kind;
				echo "<meta http-equiv='Refresh' content='0;url=".$DNS."/home.php'>";
				break;
				
			case "1"://job seeker
				$sql = "SELECT account FROM user";
				$pdo = $db->prepare($sql);
				$pdo->execute();
				while($result = $pdo->fetchObject()){
					if($result->account==$account){
						echo "<script>alert('The account has been registered.')</script>";
						echo "<meta http-equiv='Refresh' content='0;url=".$DNS."/index.php'>";
						exit;
					}
				}
				
				$newpa = md5($password);
				$edu = $_POST['education'];
				$salary = $_POST['salary'];
				if(!is_numeric($salary)) $salary = 10000;
				$gen = $_POST['gender'];
				$age = $_POST['age'];
				$specialty = $_POST['specialty'];
				
				$sql = "INSERT INTO user (account,password,education,expected_salary,phone,gender,age,email) VALUES (?,?,?,?,?,?,?,?)";
				$pdo = $db->prepare($sql);
				$pdo->execute(array($account,$newpa,$edu,$salary,$phone,$gen,$age,$email)); 

				$sql = "SELECT id FROM user WHERE account=?";
				$pdo = $db->prepare($sql);
				$pdo->execute(array($account));
				$result = $pdo->fetchObject();
				$id = $result->id;

				$sql = "INSERT INTO user_specialty (user_id, specialty_id) VALUES (?,?)";
				$pdo = $db->prepare($sql);
				for($i=0;$i<count($specialty);$i++)
					$pdo->execute(array($id, $specialty[$i])); 

				$_SESSION['id'] = $id;
				$_SESSION['account'] = $account;
				$_SESSION['kind'] = $kind;
				echo "<meta http-equiv='Refresh' content='0;url=".$DNS."/home.php'>";
				break;
		}
	}else{
		echo "<script>alert('Please Enter account, password, phone, email');</script>";
		echo "<meta http-equiv='Refresh' content='0;url=".$DNS."/index.php'>";		
	}

?>