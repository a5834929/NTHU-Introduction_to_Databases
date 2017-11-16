<?php
	include('getRelation.php');
	
	$response1 = 
		'<div class="modal-header">
			<h4 class="modal-title" id="employer-modal">Fill Out Your Information</h4>
		</div>
		<form action="register.php" method="post">
			<div class="modal-body">
				<div class="register-form">
					<div>
						Account
						<input type="text" placeholder="account" name="account"><br>
						Password
						<input type="password" placeholder="password" name="password"><br>
						Email
						<input type="text" placeholder="email" name="email"><br>
						Phone
						<input type="text" placeholder="phone" name="phone"><br>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" name="kind" value="0" class="btn btn-default">Register</button>
			</div>
		</form>';

	$education = get_selection($EDUCATION, 0);
	$specialty = get_checkbox($SPECIALTY);

	$response2 = 
		'<div class="modal-header">
			<h4 class="modal-title" id="jobseeker-modal">Fill Out Your Resume</h4>
		</div>
		<form action="register.php" method="post">
			<div class="modal-body">
				<div class="register-form">
					<div>
						Account
						<input type="text" placeholder="account" name="account"><br>
						Password
						<input type="password" placeholder="password" name="password"><br>
						Email
						<input type="text" placeholder="email" name="email"><br>
						Phone
						<input type="text" placeholder="phone" name="phone"><br>
						Age
						<input type="text" placeholder="age" name="age"><br>
						Gender
						<select name="gender">
							<option value="0" select="selected">Male</option>
							<option value="1">Female</option>
						</select><br>
						Expected Salary
						<input type="text" placeholder="expected salary" name="salary"><br>
						Major Education
						<select name="education">
						'.$education.'
						</select><br>
						What are your specialties?<br>
						'.$specialty.'
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" name="kind" value="1" class="btn btn-default">Register</button>
			</div>
		</form>';
	
	$response = array();
	array_push($response, $response1, $response2);
	echo json_encode($response);

?>