<?php
require('config.php');

// Create connection
$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT Username FROM rep GROUP BY Username;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$sql = "TRUNCATE rep_count;";
	$void = $conn->query($sql);
	while($row = $result->fetch_assoc()){
		// Get the positive Rep
		$sql_positive = "SELECT Username, Count(*) FROM rep WHERE Username='".$row['Username']."' AND rep='+';";
		$positive = $conn->query($sql_positive);
		$positive = $positive->fetch_assoc();
		$positive = $positive['Count(*)'];
		
		$sql_negative = "SELECT Username, Count(*) FROM rep WHERE Username='".$row['Username']."' AND rep='-';";
		$negative = $conn->query($sql_negative);
		$negative = $negative->fetch_assoc();
		$negative = $negative['Count(*)'];
		
		$sql_adduser = "INSERT INTO `rep_count` (`ID`, `Username`, `Count`) VALUES (NULL, '".$row['Username']."', '".($positive - $negative)."');";
		$void = $conn->query($sql_adduser);
	}
}else{
	echo "No Results";
}

$sql = "SELECT date(Date), COUNT(*) FROM logs WHERE date(Date) LIKE '".date("Y-m-d")."%' GROUP BY date(Date) ORDER BY date(Date) DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()){
		$sql = "SELECT * FROM `daily_usages` WHERE Date='".$row['date(Date)']."';";
		$result2 = $conn->query($sql);
		if ($result2->num_rows > 0) {
			$sql = "UPDATE `daily_usages` SET `Uses` = '".$row['COUNT(*)']."' WHERE Date='".$row['date(Date)']."';";
			$void = $conn->query($sql);
		}else{
			$sql = "INSERT INTO `daily_usages` (`ID`, `Date`, `Uses`) VALUES (NULL, '".$row['date(Date)']."', '".$row['COUNT(*)']."');";
			$void = $conn->query($sql);
		}
	}
}
?>