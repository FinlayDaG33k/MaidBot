<?php
header("Access-Control-Allow-Origin: *");

// Banlist of inappropriate words
$banlist = json_decode(file_get_contents('banlist.json'));

require('config.php');

// Validate the data recieved from the client
if(isset($_GET['clienttoken'])){
	$clienttoken = $_GET['clienttoken'];
}else{
	$clienttoken = "empty";
}
if(isset($_GET['method'])){
	$method = $_GET['method'];
}else{
	$method = "empty";
}

if(isset($_GET['username'])){
	$username = strtolower($_GET['username']);
}else{
	$username = "empty";
}

if(isset($_GET['rep'])){
	$rep = $_GET['rep'];
}else{
	$rep = "empty";
}

if(isset($_GET['message'])){
	$message = $_GET['message'];
}else{
	$message = "";
}

if(isset($_GET['issuer'])){
	$issuer = strtolower($_GET['issuer']);
}else{
	$issuer = "empty";
}

if(isset($_GET['bet'])){
	$bet = $_GET['bet'];
}else{
	$bet = 0;
}

if(isset($_GET['pot'])){
	$pot = $_GET['pot'];
}else{
	$pot = 0;
}

if(isset($_GET['command'])){
	$bet = $_GET['command'];
}else{
	$bet = "";
}

if (password_verify($servertoken . $clienttoken, $tokenhash)) {
	if($method == "rep"){
		if($rep == "+"){
			if(strtolower($issuer) !== $username){
				$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
				// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				} 
			
				$sql = "SELECT * FROM rep WHERE Giver='".mysqli_real_escape_string($conn,$issuer)."' AND Username='".mysqli_real_escape_string($conn,$username)."';";
				$sql_output = $conn->query($sql);
				if ($sql_output->num_rows > 0) { 
					// If the issuer already gave rep to the reciever, update it instead.
					$sql = "UPDATE rep SET Username='" . mysqli_real_escape_string($conn,$username) . "',Rep='+', Reason='".mysqli_real_escape_string($conn,$message)."', Giver='".mysqli_real_escape_string($conn,$issuer)."' WHERE Username='" . mysqli_real_escape_string($conn,$username) . "' AND Giver='".mysqli_real_escape_string($conn,$issuer)."'";
					if ($conn->query($sql) === TRUE) {
						echo "I am happy to inform you that I managed to update " . $username . "'s record Master.";
					}
				}else{
					// If the issuer did not gave rep to the reciever already
					$sql = "INSERT INTO rep (Username, Rep, Reason, Giver)VALUES ('" . mysqli_real_escape_string($conn,$username) . "','+', '".mysqli_real_escape_string($conn,$message)."','".mysqli_real_escape_string($conn,$issuer)."')";
					if ($conn->query($sql) === TRUE) {
						echo "I am happy to inform you that I managed to add reputation to " . $username . "'s record Master.";
					}
				}
			}else{
				echo "I'm sorry master, but you can't choose your own reputation.";
			}
		}elseif($rep == "-"){
			if(strtolower($issuer) !== $username){
				$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
				// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				} 
			
				$sql = "SELECT * FROM rep WHERE Giver='".mysqli_real_escape_string($conn,$issuer)."' AND Username='".mysqli_real_escape_string($conn,$username)."';";
				$sql_output = $conn->query($sql);
				if ($sql_output->num_rows > 0) { 
					// If the issuer already gave rep to the reciever, update it instead.
					$sql = "UPDATE rep SET Username='" . mysqli_real_escape_string($conn,$username) . "',Rep='-', Reason='".mysqli_real_escape_string($conn,$message)."', Giver='".mysqli_real_escape_string($conn,$issuer)."'WHERE Username='" . mysqli_real_escape_string($conn,$username) . "' AND Giver='".mysqli_real_escape_string($conn,$issuer)."'";
					if ($conn->query($sql) === TRUE) {
						echo "I am happy to inform you that I managed to update " . $username . "'s record Master.";
					}
				}else{
					// If the issuer did not gave rep to the reciever already
					$sql = "INSERT INTO rep (Username, Rep, Reason, Giver)VALUES ('" . mysqli_real_escape_string($conn,$username) . "','-', '".mysqli_real_escape_string($conn,$message)."','".mysqli_real_escape_string($conn,$issuer)."')";
					if ($conn->query($sql) === TRUE) {
						echo "I am happy to inform you that I managed to add reputation to " . $username . "'s record Master.";
					}
				}
			}else{
				echo "I'm sorry master, but you can't choose your own reputation.";
			}
		}elseif($rep == "ls"){
			$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			} 
			
			$sql = "SELECT * FROM rep WHERE Username='".mysqli_real_escape_string($conn,$username)."' ORDER BY RAND() LIMIT 5;";
			$sql_output = $conn->query($sql);
			if ($sql_output->num_rows > 0) { 
				// List a random rep from the user!
				while($row = $sql_output->fetch_row()){
					if($row[3] == ""){
						echo $row[4] . "(". $row[2] . ") ";
					}else{
						echo $row[4] . "(". $row[2] . "): " . str_replace($banlist, $replace,$row[3]) . " ";
					}
				}
				$sql_output->close();
			}else{
					echo "I'm sorry, but " . $username . " has no reputation yet.";
			}
		}elseif($rep == "rm"){
			if(strtolower($issuer) !== $username){
				$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
				// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				} 
			
				$sql = "SELECT * FROM rep WHERE Giver='".mysqli_real_escape_string($conn,$issuer)."' AND Username='".mysqli_real_escape_string($conn,$username)."';";
				$sql_output = $conn->query($sql);
				if ($sql_output->num_rows > 0) { 
					// Only do this is the issuer actually already gave rep to the reciever.
					$sql = "DELETE FROM `rep` WHERE Giver='".mysqli_real_escape_string($conn,$issuer)."' AND Username='".mysqli_real_escape_string($conn,$username)."';";
					if ($conn->query($sql) === TRUE) {
						echo "I'm glad to inform you that I successfully removed the reputation you gave to ".mysqli_real_escape_string($conn,$username);
					}
				}else{
					// If the issuer didn't give rep to the reciever already
					echo "I'm sorry, but there is no reputation to be removed.";
				}
			}else{
				echo "I'm sorry master, but you can't choose your own reputation.";
			}
		}else{
			$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			} 
			$sql = "SELECT Rep,COUNT(*) as count FROM rep WHERE Username='".mysqli_real_escape_string($conn,$username)."' GROUP BY Rep ORDER BY count DESC;";
			$sql_output = $conn->query($sql);
			if ($sql_output->num_rows > 0) { 
				echo $username . " has this reputation: ";
				while($row = $sql_output->fetch_row()){
					echo $row[0] . "(". $row[1] . ") ";
				}
			}else{
				echo "I'm sorry, but this user has no reputation yet.";
			}
			$sql_output->close();
		}
				
	}else if($method == "wagered"){
		$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT Wagered FROM users WHERE Username='".mysqli_real_escape_string($conn,$username)."';";
		$sql_output = $conn->query($sql);
		if ($sql_output->num_rows > 0) { 
			$row = $sql_output->fetch_row();
			echo $username . " seems to have wagered " . number_format ($row[0],0,".",",") . "Bits since my initiation";
		}else{
			echo "I'm sorry, but this user has not made a bet since my initiation yet.";
		}
		$sql_output->close();
	}else if($method == "addbet"){
		$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT * FROM users WHERE Username='".mysqli_real_escape_string($conn,$username)."';";
		$sql_output = $conn->query($sql);
		if ($sql_output->num_rows > 0) { 
			$sql = "UPDATE users SET Wagered=Wagered + ".mysqli_real_escape_string($conn,$bet)." WHERE Username='".mysqli_real_escape_string($conn,$username)."';";
			$sql_output = $conn->query($sql);
		}else{
			$sql = "INSERT INTO `users` (`Username`, `Wagered`) VALUES ('".mysqli_real_escape_string($conn,$username)."', '".mysqli_real_escape_string($conn,$bet)."');";
			$sql_output = $conn->query($sql);
		}
	}else if($method == "log"){	
		$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		
		$sql = "INSERT INTO `logs` (`Username`, `Command`,`Date`) VALUES ('".mysqli_real_escape_string($conn,$username)."', '".mysqli_real_escape_string($conn,$message)."','".date("Y-m-d H:i:s")."');";
		$sql_output = $conn->query($sql);
		echo $sql;
	}else if($method == "raffle"){
		$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		
		$sql = "INSERT INTO `raffle_winners` (`Username`, `Pot`,`Date`) VALUES ('".mysqli_real_escape_string($conn,$username)."', '".mysqli_real_escape_string($conn,$pot)."','".date("Y-m-d")."');";
		$sql_output = $conn->query($sql);
	}else{
		echo "I'm sorry Master, but something went wrong while trying to do this. The error I got was: \"Invalid Method\"";
	}
} else {
    echo "I'm sorry Master, but something went wrong while trying to do this. The error I got was: \"Invalid Client token\"";
}
?>