<?php
	include("db_config.php");
	session_save_path('../session_tmp');
	session_start();
	
	$account = $_POST['account'];
	$psw = $_POST['password'];
	
	if($account!=null && $psw!=null){
		$chkAccount = checkAccount($db, $account);
		if($chkAccount[0]==1){
			$chkPassword = checkPassword($db, $chkAccount[1], $account, $psw);
			if($chkPassword[0]==1){
				$_SESSION['account'] = $account;
				$_SESSION['kind'] = $chkPassword[1];
				$_SESSION['id'] = $chkPassword[2];
				echo "<meta http-equiv=REFRESH CONTENT=0;url=home.php>";

			}else if($chkPassword[0]==0){
				echo "<script>alert('Wrong Password!');</script>";
				echo "<meta http-equiv=REFRESH CONTENT=0;url=index.php>";
			}
		}else{
			echo "<script>alert('No such account! Please register!');</script>";
			echo "<meta http-equiv=REFRESH CONTENT=0;url=index.php>";
		}
	}else{
		echo "<script>alert('Please enter both username and password');</script>";
		echo "<meta http-equiv=REFRESH CONTENT=0;url=index.php>";
	}

	function checkAccount($db, $account){
		$sql = "SELECT id FROM employer WHERE account = ?";
		$pdo = $db->prepare($sql);
		$pdo->execute(array($account));
		$result = $pdo->fetchObject();
		if($result==null){
			$sql = "SELECT id FROM user WHERE account = ?";
			$pdo = $db->prepare($sql);
			$pdo->execute(array($account));
			$result = $pdo->fetchObject();

			if($result==null)	return array(0, 'null');
			return array(1, 'user');
		}
		return array(1, 'employer');
	}
	
	function checkPassword($db, $table, $account, $psw){
		$sql = "SELECT id, password FROM ".$table." WHERE account = ?";
		$pdo = $db->prepare($sql);
		$pdo->execute(array($account));
		while($result = $pdo->fetchObject()){
			$id = $result->id;
			$psw_chk = $result->password;
		}
		$check = strcmp($psw_chk, md5($psw));
		$kind = ($table=='employer')?0:1;

		if($check!=0)	return array(0, -1, -1);
		return array(1, $kind, $id);
	}

?>