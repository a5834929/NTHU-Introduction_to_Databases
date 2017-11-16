<?php
	include('db_config.php');
	include('profile.php');
	include('employerList.php');
	include('jobSeekerList.php');
	session_save_path('../session_tmp');
	session_start();

	$account = $_SESSION['account'];
	$id = $_SESSION['id'];
	$kind = $_SESSION['kind'];
	$pageType = $_POST['pageType'];
	$sort =$_POST['sort'];
	$res = $_POST['res'];

	switch($pageType){
		case "profile":
			echo loadProfile($account, $kind, $db);
			break;
		case "resume-list":
			echo loadResumeList($db);
			break;
		case "recruit-list":
			echo loadRecruitList($account, $id, $db, $sort, $res);
			break;
		case "appliant-list":
			echo loadAppliantList($id, $db);
			break;
		case "job-list":
			echo loadJobList($id, $db, $sort, $res);
			break;
		case "favorite-list":
			echo loadFavoriteList($id, $db);
			break;
		case "anonymous":
			echo loadJobList(-1, $db, $sort, $res);
			break;
	}

?>