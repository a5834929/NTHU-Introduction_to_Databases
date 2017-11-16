<?php
	include('db_config.php');
	include('getRelation.php');
	session_save_path('../session_tmp');
	session_start();
	    
	$kind=$_SESSION['kind'];
	$id=$_SESSION['id'];
	
	$occ=$_POST['occupation'];
	$loc=$_POST['location'];
	$work=$_POST['work'];
	$edu=$_POST['education'];
	$salary=$_POST['salary'];
	$exp=$_POST['experience'];

	$_SESSION['occ']=$occ;
	$_SESSION['loc']=$loc;
	$_SESSION['work']=$work;
	$_SESSION['edu']=$edu;
	$_SESSION['salary']=$salary;
	$_SESSION['exp']=$exp;

	$tmp='';
	$s=0;
	if($occ!=-1){
		$tmp=$tmp.'occupation_id=:occupation_id';
		$s=1;		
	}
	if($loc!=-1){
		if($s){
			$tmp=$tmp.' AND location_id=:location_id';
		}else{
			$tmp=$tmp.'location_id=:location_id';
			$s=1;
		}
	}
	if($work!=-1){
		if($s){
			$tmp=$tmp.' AND working_time=:working_time';
		}else{
			$tmp=$tmp.'working_time=:working_time';
			$s=1;
		}
	}
	if($edu!=-1){
		if($s){
			$tmp=$tmp.' AND education=:education';
		}else{
			$tmp=$tmp.'education=:education';
			$s=1;
		}
	}
	if($exp!=-1){
		if($s){
			$tmp=$tmp.' AND experience=:experience';
		}else{
			$tmp=$tmp.'experience=:experience';
			$s=1;
		}
	}

	if($salary!=-1){
		if($salary==10000){
			$bound=0;
		}else if($salary==30000){
			$bound=10000;
		}else if($salary==50000){
			$bound=30000;
		}else if($salary==100000){
			$bound=100000;
			$salary=99999999999;
		}
		if($s){
			$tmp=$tmp.' AND salary<:salary AND salary>=:bound';
		}else{
			
			$tmp=$tmp.'salary<:salary AND salary>=:bound';
			$s=1;
		}
	}
	
	if($s){
		$sql='SELECT * FROM recruit WHERE '.$tmp.'';
	}else{
		$sql='SELECT * FROM recruit';
	}
	
	//echo "<script>alert($sql);</script>";
	$pdo = $db->prepare($sql);
	
	if($occ!=-1)
		$pdo->bindParam(':occupation_id', $occ, PDO::PARAM_INT);
	if($loc!=-1)	
		$pdo->bindParam(':location_id', $loc, PDO::PARAM_INT);
	if($work!=-1)	
		$pdo->bindParam(':working_time', $work, PDO::PARAM_INT);
	if($edu!=-1)	
		$pdo->bindParam(':education', $edu, PDO::PARAM_INT);
	if($exp!=-1)	
		$pdo->bindParam(':experience', $exp, PDO::PARAM_INT);
	if($salary!=-1){
		$pdo->bindParam(':salary', $salary, PDO::PARAM_INT);	
		$pdo->bindParam(':bound', $bound, PDO::PARAM_INT);
	}	
	$pdo->execute();

	$location = get_selection($LOCATION, -1);
	$occupation = get_selection($OCCUPATION, -1);
	$education = get_selection($EDUCATION, -1);
	$working_time = get_selection($WORKING_TIME, -2);
	$experience = get_selection($EXPERIENCE, -2);

	if($kind==0){//employer
		$kind_value=1;
		$title='My Recruit List';
		$sort_salary=3;
	}else if($kind==1 || $kind==-1){
		$kind_values=0;
		$title='Job Vacancies';
		$sort_salary=2;
	}
	
	$pageContent = 
		'<div class="row">
			<div class="table-background">
				<table class="table table-hover">
				<caption>'.$title.'</caption>
				<thead>
					<tr>
						<th>#</th>
						<th>Occupation</th>
						<th><i class="fa fa-map-marker"></i>&nbsp;Location</th>
						<th><i class="fa fa-clock-o"></i>&nbsp;Working Time</th>
						<th><i class="fa fa-graduation-cap"></i>&nbsp;Highest Education</th>
						<th><i class="fa"></i>&nbsp;Experience</th>
						<th><i class="fa fa-usd"></i>&nbsp;Salary&nbsp;
							<i class="fa fa-caret-up btn-asc" id="asc-btn" value="'.$sort_salary.'"></i>&nbsp;
							<i class="fa fa-caret-down btn-desc" id="desc-btn" value="'.$sort_salary.'"></i>
						</th>
						<th></th>
						<th></th>
					</tr>
				</thead>
				<thead class="search-bar">
					<tr>
						<th></th>
						<th>
							<select name="occupation" id="occupation">
								<option value="-1" select="selected">occupation</option>
								'.$occupation.'
							</select>
						</th>
						<th>
							<select name="location" id="location">
								<option value="-1" select="selected">location</option>
								'.$location.'
							</select>
						</th>
						<th>
							<select name="wokring_time" id="working_time">
								<option value="-1" select="selected">working time</option>
								'.$working_time.'
							</select>
						</th>
						<th>
							<select name="education" id="education">
								<option value="-1" select="selected">education</option>
								'.$education.'
							</select>
						</th>
						<th>
							<select name="experience" id="experience">
								<option value="-1" select="selected">experience</option>
								'.$experience.'
							</select>
						</th>
						<th>
							<select name="search_salary" id="search_salary">
								<option value="-1" select="selected">Salary</option>
								<option value="10000"><10000</option>
								<option value="30000">10000-30000</option>
								<option value="50000">30000-50000</option>
								<option value="100000">>100000</option>
							</select>
						</th>
						<th><i class="fa fa-search btn-search" id="search" value="'.$kind_value.'" >&nbsp;Search</th>
						<th></th>
						
					</tr>
				</thead>
				<tbody>';
				
	while($result = $pdo->fetchObject()){
		$occupation = $OCCUPATION[$result->occupation_id-1][1];
		$location = $LOCATION[$result->location_id-1][1];
		$working_time = $WORKING_TIME[$result->working_time];
		$education = $EDUCATION[$result->education-1][1];
		$experience = $EXPERIENCE[$result->experience];
		$salary = $result->salary;
		if($kind==1){
			$sql = "SELECT * FROM application WHERE user_id=? AND recruit_id=?";
			$pdo2 = $db->prepare($sql);
			$pdo2->execute(array($id, $result->id));
			$res = $pdo2->fetchObject();
			if($res!=null)	$apply = '<td><div class="btn-apply del-apply" value="'.$result->id.'">Pending</div></td>';
			else 			$apply = '<td><div class="btn-apply add-apply" value="'.$result->id.'">Apply</div></td>';

			$sql = "SELECT * FROM favorite WHERE user_id=? AND recruit_id=?";
			$pdo2 = $db->prepare($sql);
			$pdo2->execute(array($id, $result->id));
			$res = $pdo2->fetchObject();
				if($res!=null)	$favor= '<td><div class="btn-static add-favor" value="'.$result->id.'">Added to Favorite</div></td>';
				else 			$favor = '<td><div class="btn-favor add-favor" value="'.$result->id.'">Favorite</div></td>';
		}else{
			if($result->employer_id==$id){
				$apply='<td>
						<div name="edit-rec" class="edit-recruit-btn btn-recruit btn-inline-block" data-toggle="modal" data-target="#myModal" value="'.$result->id.'">Edit</div>
						<span style="color:#AAA;">|</span>';
				$favor='<div name="delete-rec" class="recruit-delete-btn btn-recruit btn-inline-block" value="'.$result->id.'">Delete</div>
					</td>';
			}else{
				$apply='';
				$favor='';
			}
		}
		
		$pageContent .=	
			'<tr>
				<th scope="row">'.$result->id.'</th>
				<td>'.$occupation.'</td>
				<td>'.$location.'</td>
				<td>'.$working_time.'</td>
				<td>'.$education.'</td>
				<td>'.$experience.'</td>
				<td>'.$salary.'</td>
				'.$apply.$favor.'
			</tr>';
	
	}
	$pageContent .= 
		'</tbody>
		</table>';
	if($kind==0){
		$addRecruit = 
			'<div class="recruit">
				<button id="recruit-btn" class="btn btn-default" data-toggle="modal" data-target="#myModal">Add a Job</button>
			</div>
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" id="add-recruit-modal">
					</div>
				</div>
			</div></div><div>';

		$response = $pageContent.$addRecruit;
	}else{
		$response = $pageContent.='</div></div>';
	}
	echo json_encode($response);

?>