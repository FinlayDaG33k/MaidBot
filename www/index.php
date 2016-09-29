<head>
<link rel="stylesheet" href="//bootswatch.com/united/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/9c6f3ac0ed.js"></script>
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<style>
.morris-hover{position:absolute;z-index:1000}.morris-hover.morris-default-style{border-radius:10px;padding:6px;color:#666;background:rgba(255,255,255,0.8);border:solid 2px rgba(230,230,230,0.8);font-family:sans-serif;font-size:12px;text-align:center}.morris-hover.morris-default-style .morris-hover-row-label{font-weight:bold;margin:0.25em 0}
.morris-hover.morris-default-style .morris-hover-point{white-space:nowrap;margin:0.1em 0}
</style>
</head>

<?php
$banlist = json_decode(file_get_contents('banlist.json'));
require('config.php');

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>

<div class="col-lg-2">
    <div class="bs-component">
	
	<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Commands last week</h3>
  </div>
  <div class="panel-body">
    <?php
$sql = "SELECT * FROM daily_usages WHERE Date LIKE '".date("Y-m")."%' ORDER BY Date DESC LIMIT 7";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	?>
	<table class="table table-striped table-hover">
		<tr>
			<th>Date</th>
			<th>Uses</th>
		</tr>
	<?php
    while($row = $result->fetch_assoc()) {
		 
		?>
		<tr>
			<td><?= $row["Date"]; ?></td>
			<td><?= $row["Uses"]; ?></td>
		</tr>
		<?php
    }
	?>
	</table>
	<?php
} else {
    echo "No statistics available..";
}
?>
  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">10 Most using Users</h3>
  </div>
  <div class="panel-body">
    <?php


$sql = "SELECT Username, COUNT(*) FROM logs GROUP BY Username ORDER BY COUNT(*) DESC LIMIT 10;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	?>
	<table class="table table-striped table-hover">
		<tr>
			<th>Username</th>
			<th>Uses</th>
		</tr>
	<?php
    while($row = $result->fetch_assoc()) {
		?>
		<tr>
			<td><?= $row["Username"]; ?></td>
			<td><?= $row["COUNT(*)"]; ?></td>
		</tr>
		<?php
    }
	?>
	</table>
	<?php
} else {
    echo "No statistics available..";
}
?>
  </div>
</div>



	</div>
</div>

<div class="col-lg-2">
    <div class="bs-component">

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title"> 10 Most Reputable Users</h3>
  </div>
  <div class="panel-body">
    <?php
$sql = "SELECT * FROM rep_count ORDER BY Count DESC LIMIT 10;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
	?>
	<table class="table table-striped table-hover">
		<tr>
			<th>Username</th>
			<th>Reputation</th>
		</tr>
	<?php
    while($row = $result->fetch_assoc()) {
		?>
		<tr>
			<td><?= $row["Username"]; ?></td>
			<td><?= $row["Count"]; ?></td>
		</tr>
		<?php
    }
	?>
	</table>
	<?php
} else {
    echo "No statistics available..";
}
?>
  </div>
</div>

	<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">10 Most used commands</h3>
  </div>
  <div class="panel-body">
    <?php
$sql = "SELECT command, COUNT(*) FROM logs GROUP BY command ORDER BY COUNT(*) DESC LIMIT 10;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	?>
	<table class="table table-striped table-hover">
		<tr>
			<th>Command</th>
			<th>Uses</th>
		</tr>
	<?php
    while($row = $result->fetch_assoc()) {
		?>
		<tr>
			<td><?= $row["command"]; ?></td>
			<td><?= $row["COUNT(*)"]; ?></td>
		</tr>
		<?php
    }
	?>
	</table>
	<?php
} else {
    echo "No statistics available..";
}
?>
  </div>
</div>

	</div>
</div>

<div class="col-lg-2">
    <div class="bs-component">

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">10 Least Reputable Users</h3>
  </div>
  <div class="panel-body">
    <?php
$sql = "SELECT * FROM rep_count ORDER BY Count ASC LIMIT 10;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
	?>
	<table class="table table-striped table-hover">
		<tr>
			<th>Username</th>
			<th>Reputation</th>
		</tr>
	<?php
    while($row = $result->fetch_assoc()) {
		?>
		<tr>
			<td><?= $row["Username"]; ?></td>
			<td><?= $row["Count"]; ?></td>
		</tr>
		<?php
    }
	?>
	</table>
	<?php
} else {
    echo "No statistics available..";
}
?>
  </div>
</div>

	</div>
</div>

<div class="col-lg-4">
    <div class="bs-component">

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Last 10 Commands</h3>
  </div>
  <div class="panel-body">
    <?php
$sql = "SELECT * FROM logs ORDER BY Date DESC LIMIT 10;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	?>
	<table class="table table-striped table-hover">
		<tr>
			<th>User</th>
			<th>Command</th>
			<th>Date</th>
		</tr>
	<?php
    while($row = $result->fetch_assoc()) {
		 
		?>
		<tr>
			<td><?= $row["Username"]; ?></td>
			<td><?php echo str_replace($banlist, $replace,$row["Command"]);?></td>
			<td><?= $row["Date"]; ?></td>
		</tr>
		<?php
    }
	?>
	</table>
	<?php
} else {
    echo "No statistics available..";
}
?>
  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Daily Usage Graph (Dev)</h3>
  </div>
  <div class="panel-body">
  <?php
	$sql = "SELECT * FROM daily_usages WHERE Date LIKE '".date("Y-m")."%' ORDER BY Date DESC";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		?>
		<script>
			$.getScript('https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',function(){
				$.getScript('https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.0/morris.min.js',function(){
  
					Morris.Line({
						element: 'line-example',
							data: <?php $prefix = '';
							echo "[";
							$counter = 0;
							while($row = $result->fetch_assoc()){
								$counter++;
								echo $prefix . " {";
								echo '  "Date": "' . $row['Date'] . '",' . "";
								echo '  "Uses": "' . $row['Uses'] . '",' . "";
								echo ' 	"Average": "' . number_format($row['Uses'] / $counter, 2) . '"' . "";
								echo " }";
								$prefix = ",";
							}
							echo "]";?>,
							xkey: 'Date',
							ykeys: ['Uses', 'Average'],
							labels: ['Uses', 'Average']
					});  
				});
			});
		</script>
	<div id="line-example" style="height: 300px;"></div>
		<?php
	} else {
		echo "No statistics available..";
	}
	?>
  </div>
  </div>

  
  <div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Advertisement</h3>
  </div>
  <div class="panel-body">
  <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- MaidBot_responsive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-1484445584339390"
     data-ad-slot="3912264864"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
  </div>
  </div>
  
</div>
</div>

<div class="col-lg-2">
    <div class="bs-component">
	<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Donations</h3>
  </div>
  <div class="panel-body">
  <table class="table table-striped table-hover">
		<tr>
			<td>Total Donations in BTC: </td>
			<td><i class="fa fa-btc" aria-hidden="true"></i> <?php
  $wallet = json_decode(file_get_contents('https://blockchain.info/address/1BRoDCbnJ7kTS5dvVhjLdQnyqSWWjWC6SS?format=json'));
  //print_r($wallet);
  echo number_format($wallet->total_received / 100000000,5);
  ?></td>
		</tr>
		<tr>
			<td>Total Donations in Bits: </td>
			<td><?php echo $wallet->total_received / 100; ?> Bits</td>
		</tr>
		<tr>
			<td>Total Donations in EUR: </td>
			<td><i class="fa fa-eur" aria-hidden="true"></i> <?php
				$currencies = json_decode(file_get_contents('https://blockchain.info/ticker'));
				echo number_format(($wallet->total_received / 100000000) * $currencies->EUR->sell,2);
  ?></td>
		</tr>
		<tr>
			<td>Total Donations in USD: </td>
			<td><i class="fa fa-usd" aria-hidden="true"></i> <?php
				echo number_format(($wallet->total_received / 100000000) * $currencies->USD->sell,2) ;
  ?></td>
		</tr>

  </table>
  </div>
  </div>
  
  <div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">Last Raffle Winners</h3>
  </div>
  <div class="panel-body">
  <?php
$sql = "SELECT * FROM raffle_winners ORDER BY Date DESC LIMIT 10;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
	?>
	<table class="table table-striped table-hover">
		<tr>
			<th>Username</th>
			<th>Pot</th>
			<th>Date</th>
		</tr>
	<?php
    while($row = $result->fetch_assoc()) {
		 
		?>
		<tr>
			<td><?= $row["Username"]; ?></td>
			<td><?= $row["Pot"] ;?></td>
			<td><?= $row["Date"]; ?></td>
		</tr>
		<?php
    }
	?>
	</table>
	<?php
} else {
    echo "No statistics available..";
}
?>
  </div>
  </div>
	</div>
</div>
<?php
$conn->close();
?>
