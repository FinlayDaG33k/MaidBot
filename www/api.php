<?php 
include('config.php');
$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if(!empty($_GET['username'])){
	$rep_received_array = [];
	$sql = "SELECT Rep,COUNT(*) as count FROM rep WHERE Username='".mysqli_real_escape_string($conn,$_GET['username'])."' GROUP BY Rep ORDER BY count DESC;";
	$sql_output = $conn->query($sql);
	if ($sql_output->num_rows > 0) { 
		$rep_received_array["total"] = 0;
		while($row = $sql_output->fetch_row()){
			$rep_received_array[$row[0]] = $row[1];
		}
		
		$rep_received_array["total"] = $rep_received_array["+"] - $rep_received_array["-"];
		
	}else{
		$rep_received_array["+"] = "0";
		$rep_received_array["-"] = "0";
	}
							
	$sql = "SELECT Command,COUNT(*) as count FROM logs WHERE Username='".mysqli_real_escape_string($conn,$_GET['username'])."' GROUP BY Command ORDER BY count DESC LIMIT 1;";
	$sql_output = $conn->query($sql);
	if ($sql_output->num_rows > 0) { 
		while($row = $sql_output->fetch_row()){
			$rep_array["fav_command"] = $row[0];
			$rep_array["fav_command_uses"] = $row[1];
		}
								
	}else{
		$rep_array["fav_command"] = "";
		$rep_array["fav_command_uses"] = "";
	}
	
	$rep_given_array = [];	
	$sql = "SELECT Rep,COUNT(*) as count FROM rep WHERE Giver='".mysqli_real_escape_string($conn,$_GET['username'])."' GROUP BY Rep ORDER BY count DESC;";
	$sql_output = $conn->query($sql);
	if ($sql_output->num_rows > 0) { 
		$rep_given_array["total"] = 0;
		while($row = $sql_output->fetch_row()){
			$rep_given_array[$row[0]] = $row[1];
		}
								
	}else{
		$rep_given_array["+"] = "0";
		$rep_given_array["-"] = "0";
	}
	
	$rep_given_array["total"] = $rep_given_array["+"] - $rep_given_array["-"];
	
		$sql = "SELECT * FROM `rep` WHERE `Username`='".mysqli_real_escape_string($conn,$_GET['username'])."' ORDER BY RAND() LIMIT 20;";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$repmsg_received_array = [];
			while($row = $result->fetch_assoc()) {
				$repmsg_received_array[$row["Giver"]] = array("rep" => $row["Rep"], "reason" => $row["Reason"]);
			}
		}
		
		$sql = "SELECT * FROM `rep` WHERE `Giver`='".mysqli_real_escape_string($conn,$_GET['username'])."' ORDER BY RAND() LIMIT 20;";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$repmsg_given_array = [];
			while($row = $result->fetch_assoc()) {
				$repmsg_given_array[$row["Username"]] = array("rep" => $row["Rep"], "reason" => $row["Reason"]);
			}
		}
	
	$cointrust = json_decode(file_get_contents('https://cointrust.xyz/wp-json/wp/v2/profile?slug=' . $_GET['username']));
	$cointrust_array = (array)$cointrust[0];
	
	$api_array = array(
					"username" => $_GET['username'],
					"maidbot-profile" => "https://maidbot.finlaydag33k.nl/index.php?username=" . $_GET['username'],
					"rep" => array(
								"rep_count" => array(
													"received" => $rep_received_array,
													"given" => $rep_given_array
												),
								"rep_list" => array(
													"received" => $repmsg_received_array,
													"given" => $repmsg_given_array
								)
							),
					"cointrust" => $cointrust_array
	);
	
	echo json_encode($api_array);
}
?>