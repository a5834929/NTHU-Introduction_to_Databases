<?php
	include('db_config.php');
	
	$EXPERIENCE=array('Not required', '1 year', '2 years', '5 years', '10 years');
	$WORKING_TIME=array('All day', 'Morning', 'Afternoon', 'Night');
	
	$sql = "SELECT * FROM location ORDER BY id";
	$pdo = $db->prepare($sql);
	$pdo->execute();
	$LOCATION = array();
	$i=1;
	while($result = $pdo->fetchObject()){
		while($i!=$result->id){
			$tmp = array();
			array_push($tmp, $i, "NONE");
			array_push($LOCATION, $tmp);
			$i++;
		}
		$tmp = array();
		array_push($tmp, $result->id, $result->location);
		array_push($LOCATION, $tmp);
		$i++;
	}

	$sql = "SELECT * FROM occupation ORDER BY id";
	$pdo = $db->prepare($sql);
	$pdo->execute();
	$OCCUPATION = array();
	$i=1;
	while($result = $pdo->fetchObject()){
		while($i!=$result->id){
			$tmp = array();
			array_push($tmp, $i, "NONE");
			array_push($OCCUPATION, $tmp);
			$i++;
		}
		$tmp = array();
		array_push($tmp, $result->id, $result->occupation);
		array_push($OCCUPATION, $tmp);
		$i++;
	}

	$sql = "SELECT * FROM education ORDER BY id";
	$pdo = $db->prepare($sql);
	$pdo->execute();
	$EDUCATION = array();
	$i=1;
	while($result = $pdo->fetchObject()){
		while($i!=$result->id){
			$tmp = array();
			array_push($tmp, $i, "NONE");
			array_push($EDUCATION, $tmp);
			$i++;
		}
		$tmp = array();
		array_push($tmp, $result->id, $result->education);
		array_push($EDUCATION, $tmp);
		$i++;
	}

	$sql = "SELECT * FROM specialty ORDER BY id";
	$pdo = $db->prepare($sql);
	$pdo->execute();
	$SPECIALTY = array();
	$i=1;
	while($result = $pdo->fetchObject()){
		while($i!=$result->id){
			$tmp = array();
			array_push($tmp, $i, "NONE");
			array_push($SPECIALTY, $tmp);
			$i++;
		}
		$tmp = array();
		array_push($tmp, $result->id, $result->specialty);
		array_push($SPECIALTY, $tmp);
		$i++;
	}
	
	function get_selection($arr, $chosen){
		global $EXPERIENCE;
		global $WORKING_TIME;
		if($arr[0]!=-1 && $arr[0]!=-2){
			if($chosen==-1){
				for($i=0;$i<count($arr);$i++){
					if($arr[$i][1]!="NONE"){
						$result .=
							'<option value="'.$arr[$i][0].'">'.$arr[$i][1].'</option>';
					}
				}
			}else if($chosen==-2){
				$result = "";
				for($i=0;$i<count($arr);$i++){
					$result .=
							'<option value="'.$i.'" '.$select.'>'.$arr[$i].'</option>';
				}
			}else{
				$result = "";
				for($i=0;$i<count($arr);$i++){
					$select = ($i==$chosen-1)?'selected':'';
					if($arr[$i][1]!="NONE"){
						$result .=
							'<option value="'.$arr[$i][0].'" '.$select.'>'.$arr[$i][1].'</option>';
					}
				}
			}
		}else if($arr[0]==-1){
			$result = "";
			for($i=0;$i<count($EXPERIENCE);$i++){
				$select = ($i==$chosen)?'selected':'';
				$result .=
					'<option value="'.$i.'" '.$select.'>'.$EXPERIENCE[$i].'</option>';
			}
		}else if($arr[0]==-2){
			$result = "";
			for($i=0;$i<count($WORKING_TIME);$i++){
				$select = ($i==$chosen)?'selected':'';
				$result .=
					'<option value="'.$i.'" '.$select.'>'.$WORKING_TIME[$i].'</option>';
			}
		}
		return $result;
	}

	function get_checkbox($arr){
		$result = "";
		for($i=0;$i<count($arr);$i++){
			if($arr[$i][1]!="NONE"){
				$result .= 
					'<input type="checkbox" name="specialty[]" value="'.$arr[$i][0].'">'.$arr[$i][1];
			}
		}
		return $result;
	}
	
	function get_recruit($id, $db){
		$sql="SELECT * FROM recruit WHERE id=?";
		$pdo=$db->prepare($sql);
		$pdo->execute(array($id));
		$result=array();
		while($sth = $pdo->fetchObject()){
			array_push($result,$sth->id);
			array_push($result,$sth->occupation_id);
			array_push($result,$sth->location_id);
			array_push($result,$sth->working_time);
			array_push($result,$sth->education);
			array_push($result,$sth->experience);
			array_push($result,$sth->salary);
		}		
		return $result;
	}

?>