<?php
	include('db_config.php');
	include('getRelation.php');
	session_save_path('../session_tmp');
	session_start();

	function loadResumeList($db){
		global $LOCATION;
		global $OCCUPATION;
		global $EDUCATION;
		global $SPECIALTY;

		$sql = "SELECT * FROM user";
		$pdo = $db->prepare($sql);
		$pdo->execute();

		$pageContent = 
			'<div class="row">
				<div class="table-background">
					<table class="table table-hover">
					<caption>Appliant Resumes</caption>
					<thead>
						<tr>
							<th>#</th>
							<th><i class="fa fa-user"></i>&nbsp;Account</th>
							<th><i class="fa fa-graduation-cap"></i>&nbsp;Highest Education</th>
							<th><i class="fa fa-phone"></i>&nbsp;Phone Number</th>
							<th><i class="fa fa-envelope-o"></i>&nbsp;Email</th>
							<th><i class="fa fa-usd"></i>&nbsp;Expected Salary</th>
							<th><i class="fa fa-transgender"></i>&nbsp;Gender</th>
							<th><i class="fa fa-birthday-cake"></i>&nbsp;Age</th>
							<th><i class="fa fa-star"></i>&nbsp;Specialties</th>
						</tr>
					</thead>
					<tbody>';

		while($result = $pdo->fetchObject()){
			$id = $result->id;
			$account = $result->account;
			$education = $EDUCATION[$result->education-1][1];
			$salary = $result->expected_salary;
			$phone = $result->phone;
			$gender = ($result->gender==0)?'<i class="fa fa-male"></i>':'<i class="fa fa-female"></i>';
			$age = $result->age;
			$email = $result->email;

			$sql = "SELECT specialty_id FROM user_specialty WHERE user_id = ?";
			$pdo2 = $db->prepare($sql);
			$pdo2->execute(array($id));
			$specArr = array();
			while($spec = $pdo2->fetchObject())
				array_push($specArr, $SPECIALTY[$spec->specialty_id-1][1]);

			$specialty = "";
			for($i=0;$i<count($specArr);$i++){
				$specialty .= $specArr[$i];
				if($i<count($specArr)-1) $specialty .= ", ";
			}

			$pageContent .=	
				'<tr>
					<th scope="row">'.$result->id.'</th>
					<td>'.$account.'</td>
					<td>'.$education.'</td>
					<td>'.$phone.'</td>
					<td>'.$email.'</td>
					<td>'.$salary.'</td>
					<td>'.$gender.'</td>
					<td>'.$age.'</td>
					<td>'.$specialty.'</td>
				</tr>';
		}
		
		$response = $pageContent;
		return json_encode($response);
	}

	function loadRecruitList($account, $id, $db, $sort, $res){
		global $LOCATION;
		global $OCCUPATION;
		global $EDUCATION;
		global $SPECIALTY;
		global $EXPERIENCE;
		global $WORKING_TIME;
		
		$occ=$_SESSION['occ'];
		$loc=$_SESSION['loc'];
		$work=$_SESSION['work'];
		$edu=$_SESSION['edu'];
		$salary=$_SESSION['salary'];
		$exp=$_SESSION['exp'];
		
		if($sort==0){
			$sql = "SELECT * FROM recruit";
			$pdo = $db->prepare($sql);
			$pdo->execute();
			$order_value=1;
		}else if($sort==1){
			$sql = "SELECT * FROM recruit ORDER BY salary ASC";
			$pdo = $db->prepare($sql);
			$pdo->execute();
			$order_value=1;
		}else if($sort==2){
			$sql = "SELECT * FROM recruit ORDER BY salary DESC";
			$pdo = $db->prepare($sql);
			$pdo->execute();
			$order_value=1;
		}else if($sort==3){
			return json_encode($res);
		}else if($sort==4 || $sort==5){
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
			
			if($sort==4){
				if($s){
					$sql='SELECT * FROM recruit WHERE '.$tmp.' ORDER BY salary ASC';
					$order_value=3;
				}else{
					$sql='SELECT * FROM recruit ORDER BY salary ASC';
					$order_value=1;
				}
			}else if($sort==5){
				if($s){
					$sql='SELECT * FROM recruit WHERE '.$tmp.' ORDER BY salary DESC';
					$order_value=3;
				}else{
					$sql='SELECT * FROM recruit ORDER BY salary DESC';
					$order_value=1;
				}
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
		}

		$location = get_selection($LOCATION, -1);
		$occupation = get_selection($OCCUPATION, -1);
		$education = get_selection($EDUCATION, -1);
		$working_time = get_selection($WORKING_TIME, -2);
		$experience = get_selection($EXPERIENCE, -2);

		$pageContent = 
			'<div class="table-background">
				<table class="table table-hover">
				<caption>My Recruit List</caption>
				<thead>
					<tr>
						<th>#</th>
						<th>Occupation</th>
						<th><i class="fa fa-map-marker"></i>&nbsp;Location</th>
						<th><i class="fa fa-clock-o"></i>&nbsp;Working Time</th>
						<th><i class="fa fa-graduation-cap"></i>&nbsp;Required Education</th>
						<th>Required Experience</th>
						<th><i class="fa fa-usd"></i>&nbsp;Salary&nbsp
							<i class="fa fa-caret-up btn-asc" id="asc-btn" value="'.$order_value.'"></i>&nbsp;
							<i class="fa fa-caret-down btn-desc" id="desc-btn" value="'.$order_value.'"></i>
						</th>
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
						<th><i class="fa fa-search btn-search" id="search" value="1">&nbsp;Search</th>
					</tr>
				</thead>
				<tbody>';

		while($result = $pdo->fetchObject()){
			$employer_id=$result->employer_id;
			$occupation_id = $OCCUPATION[$result->occupation_id-1][0];
			$occupation = $OCCUPATION[$result->occupation_id-1][1];
			$location = $LOCATION[$result->location_id-1][1];
			$working_time = $WORKING_TIME[$result->working_time];
			$education = $EDUCATION[$result->education-1][1];
			$experience = $EXPERIENCE[$result->experience];
			$salary = $result->salary;
			
			if($employer_id==$id){
				$pageContent .=	
				'<tr>
					<th scope="row">'.$result->id.'</th>
					<td>'.$occupation.'</td>
					<td>'.$location.'</td>
					<td>'.$working_time.'</td>
					<td>'.$education.'</td>
					<td>'.$experience.'</td>
					<td>'.$salary.'</td>
					<td>
						<div name="edit-rec" class="edit-recruit-btn btn-recruit btn-inline-block" data-toggle="modal" data-target="#myModal" value="'.$result->id.'">Edit</div>
						<span style="color:#AAA;">|</span>
						<div name="delete-rec" class="recruit-delete-btn btn-recruit btn-inline-block" value="'.$result->id.'">Delete</div>
					</td>
				</tr>';
			}else{
				$pageContent .=	
				'<tr>
					<th scope="row">'.$result->id.'</th>
					<td>'.$occupation.'</td>
					<td>'.$location.'</td>
					<td>'.$working_time.'</td>
					<td>'.$education.'</td>
					<td>'.$experience.'</td>
					<td>'.$salary.'</td>
				</tr>';
			}
		}

		$pageContent .= 
			'</tbody>
			</table>';

		$addRecruit = 
			'<div class="recruit">
				<button id="recruit-btn" class="btn btn-default" data-toggle="modal" data-target="#myModal">Add a Job</button>
			</div>
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content" id="add-recruit-modal">
					</div>
				</div>
			</div>';

		$response = $pageContent.$addRecruit;
		return json_encode($response);
	}

	function loadAppliantList($id, $db){
		global $LOCATION;
		global $OCCUPATION;
		global $EDUCATION;
		global $SPECIALTY;
		global $EXPERIENCE;
		global $WORKING_TIME;

		$sql = "SELECT * FROM recruit WHERE id IN (SELECT recruit_id FROM application) AND employer_id = ? ORDER BY id";
		$pdo = $db->prepare($sql);
		$pdo->execute(array($id));

		$pageContent = 
			'<div class="row">
				<div class="table-background">';

		$i = 1;
		while($result = $pdo->fetchObject()){
			$occupation = $OCCUPATION[$result->occupation_id-1][1];
			$location = $LOCATION[$result->location_id-1][1];
			$working_time = $WORKING_TIME[$result->working_time];
			$education = $EDUCATION[$result->education-1][1];
			$experience = $EXPERIENCE[$result->experience];
			$salary = $result->salary;

			if($i==1)	$caption = '<caption>Applications</caption>';
			else 		$caption = '';
			$i = 0;

			$pageContent .=
				'<table class="table table-hover">
					'.$caption.'
					<thead>
						<tr>
							<th>'.$occupation.'</th>
							<th><i class="fa fa-map-marker"></i>&nbsp;'.$location.'</th>
							<th><i class="fa fa-clock-o"></i>&nbsp;'.$working_time.'</th>
							<th><i class="fa fa-graduation-cap"></i>&nbsp;'.$education.'</th>
							<th>'.$experience.'</th>
							<th><i class="fa fa-usd"></i>&nbsp;'.$salary.'</th>
							<th></th>
							<th></th>
							<th></th>							
						</tr>
					</thead>
					<tbody>';

			$sql = "SELECT * FROM user WHERE id IN (SELECT user_id FROM application WHERE recruit_id = ?) ORDER BY id";
			$pdo2 = $db->prepare($sql);
			$pdo2->execute(array($result->id));
			while($res = $pdo2->fetchObject()){
				$acc = $res->account;
				$gen = ($res->gender==0)?'<i class="fa fa-male"></i>':'<i class="fa fa-female"></i>';
				$age = $res->age;
				$edu = $EDUCATION[$res->education-1][1];
				$sal = $res->expected_salary;
				$pho = $res->phone;
				$ema = $res->email;

				$sql = "SELECT specialty_id FROM user_specialty WHERE user_id = ?";
				$pdo3 = $db->prepare($sql);
				$pdo3->execute(array($res->id));
				$specArr = array();
				while($spec = $pdo3->fetchObject())
					array_push($specArr, $SPECIALTY[$spec->specialty_id-1][1]);

				$spe = '';
				for($i=0;$i<count($specArr);$i++){
					$spe.= $specArr[$i];
					if($i<count($specArr)-1) $spe .= ', ';
				}

				$pageContent .=	
					'<tr>
						<td>'.$acc.'</td>
						<td>'.$gen.'</td>
						<td>'.$age.'</td>
						<td>'.$edu.'</td>
						<td>'.$sal.'</td>
						<td>'.$pho.'</td>
						<td>'.$ema.'</td>
						<td>'.$spe.'</td>
						<td class="btn-hire btn-recruit" value="'.$result->id.'">Hire</td>
					</tr>';
			}
			$pageContent .= '</tbody></table>';
		}
		$pageContent .= '</div></div>';
		$response = $pageContent;
		return json_encode($response);
	}
	
?>