<head>
<link rel="stylesheet" href="//bootswatch.com/united/bootstrap.min.css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="//use.fontawesome.com/9c6f3ac0ed.js"></script>
<script src="inc/jquery-3.1.1.min.js"></script>
<style>
.morris-hover{position:absolute;z-index:1000}.morris-hover.morris-default-style{border-radius:10px;padding:6px;color:#666;background:rgba(255,255,255,0.8);border:solid 2px rgba(230,230,230,0.8);font-family:sans-serif;font-size:12px;text-align:center}.morris-hover.morris-default-style .morris-hover-row-label{font-weight:bold;margin:0.25em 0}
.morris-hover.morris-default-style .morris-hover-point{white-space:nowrap;margin:0.1em 0}
</style>
</head>

<?php
$banlist = json_decode(file_get_contents('banlist.json'));
require('config.php');

// Create connection
$conn = new mysqli($sql_servername, $sql_username, $sql_password, $sql_dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
 
// don't touch this, it might break the site!
$disallowed_paths = array('header', 'footer'); 
if (!empty($_GET['action'])) {
	$tmp_action = basename($_GET['action']);
	if (!in_array($tmp_action, $disallowed_paths) && file_exists("pages/{$tmp_action}.php")) {
		$action = $tmp_action;
	} else {
		$action = error;
	}
}else{
    $action = 'home';
}

include("inc/navbar.php");
?>
<div class="container-fluid">
	<?php
		include("pages/$action.php");
	?>
</div>
<?php
$conn->close();
?>
