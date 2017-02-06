<?php 
$reparray = json_decode(file_get_contents('rep.json'));
require('config.php');
//print_r($reparray);

$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
} 

foreach($reparray as $rep){
	$rep = (array)$rep;
	//print_r($rep);
	//echo $rep['r'];
	if($rep['r'] == "-1"){
		$rep_amount = "-";
	}elseif($rep['r'] == "1"){
		$rep_amount = "+";
	}
	$sql = "SELECT * FROM `rep` WHERE Username='".$rep['reppie']."' AND Giver='".$rep['repper']."'";
	echo $sql . "<br />";
	$sql_output = $conn->query($sql);
	if ($sql_output->num_rows > 0){
		$sql = "UPDATE `rep` SET Rep='".mysqli_real_escape_string($conn,$rep_amount)."', Reason='".mysqli_real_escape_string($conn,$rep['c'])."' WHERE Username='".mysqli_real_escape_string($conn,$rep['reppie'])."' AND Giver='".mysqli_real_escape_string($conn,$rep['repper'])."'";
		echo $sql . "<br />";
		if ($conn->query($sql) === TRUE) {
			echo "Success!"  . "<br />";
		}else{
			echo "Fail!" . "<br />";
		}
	}else{
		$sql = "INSERT INTO `rep` (`ID`, `Username`, `Rep`, `Reason`, `Giver`) VALUES (NULL, '".mysqli_real_escape_string($conn,$rep['reppie'])."', '".mysqli_real_escape_string($conn,$rep_amount)."', '".mysqli_real_escape_string($conn,$rep['c'])."', '".mysqli_real_escape_string($conn,$rep['repper'])."');";
		echo $sql . "<br />";
		if ($conn->query($sql) === TRUE) {
			echo "Success!"  . "<br />";
		}else{
			echo "Fail!" . "<br />";
		}
	}	
	echo "<br />";
}

?>