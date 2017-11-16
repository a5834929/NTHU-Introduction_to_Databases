<?php
	include('db_config.php');
	include('getRelation.php');
	session_save_path('../session_tmp');
	session_start();
	
	$recType = $_POST['recType'];
	$recruit_id = $_POST['edit'];
	
	if($recType==0){

		$location = get_selection($LOCATION, 1);
		$occupation = get_selection($OCCUPATION, 1);
		$education = get_selection($EDUCATION, 1);
		$exp = get_selection($EXPERIENCE, -2);
		$wor = get_selection($WORKING_TIME, -2);

		$response =
			'
			<div class="modal-header">
				<h4 class="modal-title" id="add-new-job">Add A New Job</h4>
			</div>
			<div class="modal-body">
				<div class="recruit-form">
					Occupation
					<select name="occupation" id="occ">
					'.$occupation.'
					</select></br>
					Location
					<select name="location" id="loc">
					'.$location.'
					</select>
					</br>
					Working Time
					<select name="working_time" id="wrt">
					'.$wor.'
					</select></br>
					Highest Education Required
					<select name="education" id="edu">
					'.$education.'
					</select><br>
					Minimum of Working Experience					
					<select name="experience" id="exp">
					'.$exp.'
					</select></br>
					Salary
					<input type="text" placeholder="salary" name="salary" id="sal">
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" name="kind" value="0" id="add-job" class="btn btn-default" data-dismiss="modal">Submit</button>
			</div>';
			echo json_encode($response);
	}else if($recType==1){

		$ori_recruit = get_recruit($recruit_id,$db);
		$e_location = get_selection($LOCATION,$ori_recruit[2]);
		$e_occupation = get_selection($OCCUPATION,$ori_recruit[1]);
		$e_education = get_selection($EDUCATION,$ori_recruit[4]);
		$exp = get_selection(array(-1),$ori_recruit[5]);
		$wor = get_selection(array(-2),$ori_recruit[3]);

		$response=
		'<div class="modal-header">
			<h4 class="modal-title" id="myModalLabel">Edit My Job</h4>
		</div>
		<div class="modal-body">
			<div class="editRecruit-form">
				#
				<input type="text" name="primary_key" value='.$recruit_id.' id="id" readonly></br>
				Occupation
				<select name="occupation" id="occ">
				'.$e_occupation.'
				</select></br>
				Location
				<select name="location" id="loc">
				'.$e_location.'
				</select>
				</br>
				Working Time
				<select name="working_time" id="work">
				'.$wor.'
				</select>
				</br>
				Highest Education Required
				<select name="education" id="edu">
				'.$e_education.'
				</select></br>
				Minimum of Working Experience					
				<select name="experience" id="exp">
				'.$exp.'
				</select></br>
				Salary
				<input type="text" placeholder="salary" name="salary" id="sal" value="'.$ori_recruit[6].'">
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-default" data-dismiss="modal">Close</button>
			<button id="save-edit" name="edit" value="0" class="btn btn-default" data-dismiss="modal">Save Changes</button>
		</div>';
		echo json_encode($response);

	}else if($recType==2){
		$sql="DELETE FROM recruit WHERE id=:id";
		$sth=$db->prepare($sql);
		$sth->bindParam(':id',$recruit_id,PDO::PARAM_INT);
		$sth->execute();
		echo 1;
	}
	
?>