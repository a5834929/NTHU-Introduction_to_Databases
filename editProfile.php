<?php
	include('db_config.php');
	include('getRelation.php');
	session_save_path('../session_tmp');
	session_start();

	$id = $_SESSION['id'];
	$kind = $_SESSION['kind'];
	$editType = $_POST['proEdit'];

	if($editType==0){  // load edit
		$education = $_POST['education'];
		$education_select = get_selection($EDUCATION, $education);
		$response = '<select>'.$education_select.'</select>';
		echo json_encode($response);
		
	}else if($editType==1){ // update profile

		$phone = $_POST['phone'];
		$email = $_POST['email'];
		if($kind==0){
			$sql="UPDATE employer SET phone=:phone,
								  	  email=:email
								      WHERE id=:id";

			$pdo = $db->prepare($sql);
			$pdo->bindParam(':phone',$phone,PDO::PARAM_STR);
			$pdo->bindParam(':email',$email,PDO::PARAM_STR);
			$pdo->bindParam(':id',$id,PDO::PARAM_INT);

		}else{
			$education = $_POST['education'];
			$age = $_POST['age'];
			$salary = $_POST['salary'];

			$sql="UPDATE user SET education=:education,
								  expected_salary=:salary,
								  phone=:phone,
								  age=:age,
								  email=:email
								  WHERE id=:id";

			$pdo = $db->prepare($sql);
			$pdo->bindParam(':education',$education, PDO::PARAM_INT);
			$pdo->bindParam(':salary',$salary,PDO::PARAM_INT);
			$pdo->bindParam(':phone',$phone,PDO::PARAM_STR);
			$pdo->bindParam(':age',$age,PDO::PARAM_INT);
			$pdo->bindParam(':email',$email,PDO::PARAM_STR);
			$pdo->bindParam(':id',$id,PDO::PARAM_INT);
		}
		$pdo->execute();
		echo 1;
	}
?>