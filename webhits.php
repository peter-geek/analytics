<?php
/*
	Database = ?
	table	 = webhits
*/
$now = date('D-d-F-Y');
if(isset($_COOKIE['present'])){
	if($_COOKIE['present'] != ''){
		// /* record this hit */
		$value = $_COOKIE['present'];
		$query = mysqli_query($db,"select * from webhits where date = '$now'") or die(mysqli_error($db));
		if(mysqli_num_rows($query) < 1){
			// /* echo 'wer'; */
			$insertNew = mysqli_query($db,"insert into webhits (date,hits,count,user) values ('$now','1','1','$value')") or die(mysqli_error($db));
		}
		else{
			// /* echo 'wer'; */
			$asc = $query->fetch_assoc();
			$hits = $asc['hits']+1;

			$user = explode(',',$asc['user']);
			if(in_array($value,$user)){
				$count = $asc['count']+1;
				$hits = $asc['hits'];
				$user = $asc['user'];
			}
			else{
				$count = $asc['count']+1;
				$hits = $asc['hits'];
				if(strlen($asc['user']) < 1){
					$user = $hits;
				}
				else{
					$user = $asc['user'].','.$hits;
				}

			}
			$ins = mysqli_query($db,"update webhits set hits='$hits',count='$count',user='$user' where date='$now'") or die(mysqli_error($db));
		}
	}
	// setcookie('present','', time() - 3600*34);
}
else if(!isset($_COOKIE['present'])){
	//asign a unique value
	//get the number of counts and make this a value of my cookie
	$query = mysqli_query($db,"select * from webhits where date = '$now'") or die(mysqli_error($db));
	if(mysqli_num_rows($query) > 0){

		$a = $query->fetch_assoc();
		$count = $a['count']+1;
		$hits = $a['hits']+1;
		$cookieValue = $a['hits']+1;
		$user = explode(',',$a['user']);
		if(strlen($a['user']) < 1){

			$user = $cookieValue;
		}
		else{
			if($user < 1){

				$user = $count;
			}
			else{
				$user = $a['user'].','.$hits;
			}

		}

		$update = mysqli_query($db,"update webhits set hits='$cookieValue',count='$count',user='$user' where date='$now'") or die(mysqli_error($db));
		setcookie('present',$cookieValue, time() + 3600*24);
	}
	else{
		//there's no hit in the database. Make me number 1 and record this in the database
		$insert = mysqli_query($db,"insert into webhits (date,hits,count,user) values('$now','1','1','1')") or die(mysqli_error($db));
		$cookieValue = 1;
		setcookie('present',$cookieValue, time() + 3600*24);
	}

}
?>
