<?php
	include('db_config.php');
	session_save_path('../session_tmp');
	session_start();

	$addType = $_POST['addType'];
	$recruit_id = $_POST['recruit_id'];
	$id = $_SESSION['id'];

	if($addType==0) 
		echo addApply($id, $recruit_id, $db);
	else if($addType==1) 
		echo addFavorite($id, $recruit_id, $db);
	else if($addType==2) 
		echo delApply($id, $recruit_id, $db);
	else if($addType==3) 
		echo delFavorite($id, $recruit_id, $db);

	function addApply($id, $recruit_id, $db){
		$sql = "INSERT INTO application (user_id, recruit_id) VALUES (?,?)";
		$pdo = $db->prepare($sql);
		$pdo->execute(array($id, $recruit_id));
		$text = "Pending";
		$rmClass = 'add-apply';
		$adClass = 'del-apply';
		return json_encode(array($text, $rmClass, $adClass));
	}

	function addFavorite($id, $recruit_id, $db){
		$sql = "INSERT INTO favorite (user_id, recruit_id) VALUES (?,?)";
		$pdo = $db->prepare($sql);
		$pdo->execute(array($id, $recruit_id));
		return json_encode("Added to Favorite");
	}

	function delApply($id, $recruit_id, $db){
		$sql = "DELETE FROM application WHERE user_id=? AND recruit_id=?";
		$pdo = $db->prepare($sql);
		$pdo->execute(array($id, $recruit_id));
		$text = "Apply";
		$rmClass = 'del-apply';
		$adClass = 'add-apply';
		return json_encode(array($text, $rmClass, $adClass));
	}

	function delFavorite($id, $recruit_id, $db){
		$sql = "DELETE FROM favorite WHERE user_id=? AND recruit_id=?";
		$pdo = $db->prepare($sql);
		$pdo->execute(array($id, $recruit_id));
		return json_encode("Favorite");
	}


?>