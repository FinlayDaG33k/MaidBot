<?php 
include('config.php');
$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "INSERT INTO `API_logs` (`ID`, `function`,`IP`,`API_key`,`Date`) VALUES (NULL,'".mysqli_real_escape_string($conn,$_GET['function'])."','".mysqli_real_escape_string($conn,$_SERVER["HTTP_CF_CONNECTING_IP"])."','".mysqli_real_escape_string($conn,$_GET['authtoken'])."','".date("Y-m-d H:i:s")."');";
$sql_output = $conn->query($sql);

if(!empty($_GET['authtoken'])){
	$sql = "SELECT * FROM `API_keys` WHERE `API_Key`='".mysqli_real_escape_string($conn,$_GET['authtoken'])."' AND `Suspended`='0';";
	$sql_output = $conn->query($sql);
	if ($sql_output->num_rows > 0) {
		$authorized = true;
		if($_GET['function'] == 'user'){
			include('api_functions/user.php');
		}else{
			echo "Invalid function call!";
		}
	}else{
		echo "Invalid authtoken!";
	}
}else{
	echo "Empty authtoken!";
}
?>