<?php
	include('getRelation.php');

	function loadProfile($account, $kind, $db){
		global $EDUCATION;

		$profileInfo = 
			'<div class="profile-background">
			<div class="col-md-6">
				<img src="img/profile.png" class="img-responsive">
			</div>
			<div class="col-md-6">
				<div class="table-background">
					<table class="table table-hover">
					<caption>My Profile</caption>';

		if($kind==0){ // profile of employer
			$sql = "SELECT * FROM employer WHERE account = ?";
			$pdo = $db->prepare($sql);
			$pdo->execute(array($account));
			while($result = $pdo->fetchObject()){
				$phone = $result->phone;
				$email = $result->email;
			}
			
			$pageContent = 
				'<thead>
					<tr>
						<th><i class="fa fa-user"></i>&nbsp;'.$account.'</th>
						<th class="btn-profile btn-align-right" id="edit"><i class="fa fa-pencil"></i>&nbsp;Edit</th>
						<th class="btn-align-right hidden" id="save-cancel">
							<span id="save" class="btn-profile">Save</span>&nbsp;
							<span style="color:#222;">|</span>&nbsp;
							<span id="cancel" class="btn-profile">Cancel</span>
						</th>
					</tr>
				</thead>
				<tbody>

					<tr>
						<th scope="row"><i class="fa fa-phone"></i>&nbsp;Phone</th>
						<td id="profile-phone">'.$phone.'</td>
					</tr>
					<tr>
						<th scope="row"><i class="fa fa-envelope-o"></i>&nbsp;Email</th>
						<td id="profile-email">'.$email.'</td>
					</tr>';

		}else{	// profile of job seeker
			$sql = "SELECT * FROM user WHERE account = ?";
			$pdo = $db->prepare($sql);
			$pdo->execute(array($account));
			while($result = $pdo->fetchObject()){
				$education_id = $result->education;
				$education = $EDUCATION[$result->education-1][1];
				$salary = $result->expected_salary;
				$phone = $result->phone;
				$gender = $result->gender;
				$age = $result->age;
				$email = $result->email;
			}

			$pageContent = 
				'<thead>
					<tr>
						<th><i class="fa fa-user"></i>&nbsp;'.$account.'</th>
						<th class="btn-profile btn-align-right" id="edit"><i class="fa fa-pencil"></i>&nbsp;Edit</th>
						<th class="btn-align-right hidden" id="save-cancel">
							<span id="save" class="btn-profile">Save</span>&nbsp;
							<span style="color:#222;">|</span>&nbsp;
							<span id="cancel" class="btn-profile">Cancel</span>
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row"><i class="fa fa-phone"></i>&nbsp;Phone</th>
						<td id="profile-phone">'.$phone.'</td>
					</tr>
					<tr>
						<th scope="row"><i class="fa fa-envelope-o"></i>&nbsp;Email</th>
						<td id="profile-email">'.$email.'</td>
					</tr>
					<tr>
						<th scope="row"><i class="fa fa-graduation-cap"></i>&nbsp;Education</th>
						<td id="profile-education" value="'.$education_id.'">'.$education.'</td>
					</tr>
					<tr>
						<th scope="row"><i class="fa fa-birthday-cake"></i>&nbsp;Age</th>
						<td id="profile-age">'.$age.'</td>
					</tr>
					<tr>
						<th scope="row"><i class="fa fa-usd"></i>&nbsp;Expected Salary</th>
						<td id="profile-salary">'.$salary.'</td>
					</tr>';

		}

		$pageContent = $profileInfo.$pageContent;
		$pageContent .=
				'</tbody>
				</table>
			 </div>
			 </div>
			 </div>';

		$response = $pageContent;
		return json_encode($response);
	}
	
?>