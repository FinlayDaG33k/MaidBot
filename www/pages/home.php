<div class="container-fluid">
<?php
if(!empty($_GET['username'])){
?>

<h1>Viewing Data for <?= htmlentities($_GET['username']); ?></h1>
<hr>

<div class="row">
	<div class="col-md-2">
		<div class="bs-component">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Statistics for <?= htmlentities($_GET['username']); ?></h3>
				</div>
				<div class="panel-body">
					<?php
						$array = [];
						$sql = "SELECT Rep,COUNT(*) as count FROM rep WHERE Username='".mysqli_real_escape_string($conn,$_GET['username'])."' GROUP BY Rep ORDER BY count DESC;";
						$sql_output = $conn->query($sql);
						if ($sql_output->num_rows > 0) { 
							while($row = $sql_output->fetch_row()){
								$array["rep_received_" . $row[0]] = $row[1];
							}
							
						}else{
							$array["rep_received_+"] = "User Not Seen!";
							$array["rep_received_-"] = "User Not Seen!";
						}
						
						$sql = "SELECT Command,COUNT(*) as count FROM logs WHERE Username='".mysqli_real_escape_string($conn,$_GET['username'])."' GROUP BY Command ORDER BY count DESC LIMIT 1;";
						$sql_output = $conn->query($sql);
						if ($sql_output->num_rows > 0) { 
							while($row = $sql_output->fetch_row()){
								$array["fav_command"] = $row[0];
								$array["fav_command_uses"] = $row[1];
							}
							
						}else{
							$array["fav_command"] = "User Not Seen!";
							$array["fav_command_uses"] = "User Not Seen!";
						}
						
						$sql = "SELECT Rep,COUNT(*) as count FROM rep WHERE Giver='".mysqli_real_escape_string($conn,$_GET['username'])."' GROUP BY Rep ORDER BY count DESC;";
						$sql_output = $conn->query($sql);
						if ($sql_output->num_rows > 0) { 
							while($row = $sql_output->fetch_row()){
								$array["rep_given_" . $row[0]] = $row[1];
							}
							
						}else{
							$array["rep_given_+"] = "User Not Seen!";
							$array["rep_given_-"] = "User Not Seen!";
						}
						
						?>
						<table class="table table-striped table-hover">
							<tr>
								<td>Positive Reputation Received:</td>
								<td><?= $array['rep_received_+']; ?></td>
							</tr>
							<tr>
								<td>Negative Reputation Received:</td>
								<td><?= $array['rep_received_-']; ?></td>
							</tr>
							<tr>
								<td>Positive Reputation Given:</td>
								<td><?= $array['rep_given_+']; ?></td>
							</tr>
							<tr>
								<td>Negative Reputation Given:</td>
								<td><?= $array['rep_given_-']; ?></td>
							</tr>
							<tr>
								<td>Favorite Command:</td>
								<td><?= $array['fav_command']; ?></td>
							</tr>
							<tr>
								<td>Favorite Command Uses:</td>
								<td><?= $array['fav_command_uses']; ?></td>
							</tr>
						</table>
						<?php
						//print_r($array);
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="bs-component">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Reputations Received</h3>
				</div>
				<div class="panel-body">
					<?php
						$sql = "SELECT * FROM `rep` WHERE `Username`='".mysqli_real_escape_string($conn,$_GET['username'])."' ORDER BY RAND() LIMIT 20;";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							// output data of each row
					?>
							<table class="table table-striped table-hover">
								<tr>
									<th>Giver</th>
									<th>Reputation</th>
									<th>Message</th>
								</tr>
					<?php
							while($row = $result->fetch_assoc()) {
					?>
								<tr>
									<td><?= $row["Giver"]; ?></td>
									<td><?= $row["Rep"]; ?></td>
									<td><?= $row["Reason"]; ?></td>
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
	<div class="col-md-6">
		<div class="bs-component">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Reputations Given</h3>
				</div>
				<div class="panel-body">
					<?php
						$sql = "SELECT * FROM `rep` WHERE `Giver`='".mysqli_real_escape_string($conn,$_GET['username'])."' ORDER BY RAND() LIMIT 20;";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							// output data of each row
					?>
							<table class="table table-striped table-hover">
								<tr>
									<th>Receiver</th>
									<th>Reputation</th>
									<th>Message</th>
								</tr>
					<?php
							while($row = $result->fetch_assoc()) {
					?>
								<tr>
									<td><?= $row["Username"]; ?></td>
									<td><?= $row["Rep"]; ?></td>
									<td><?= $row["Reason"]; ?></td>
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
</div>


<?php
}else{
?>
	<div class="row">
		<div class="col-md-2">
			<!-- Begin Commands last week -->
			<div class="bs-component">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Commands last week <span class="badge">5</span></h3>
					</div>
					<div class="panel-body">
						<?php
							$sql = "SELECT * FROM daily_usages WHERE `Date` BETWEEN '".date("Y-m-d", strtotime("-7 days")) ."' AND '".date("Y-m-d")."' ORDER by `Date` DESC LIMIT 7;";
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
			</div>
			<!-- End Commands last week -->
		</div>
		<div class="col-md-2">
			<!-- Begin 10 Most using Users -->
			<div class="bs-component">
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
			<!-- End 10 Most using Users -->
		</div>
		<div class="col-md-2">
			<!-- Begin 10 Most reputable Users -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"> 10 Most Reputable Users <span class="badge">5</span></h3>
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
			<!-- End 10 Most reputable Users -->
		</div>
		<div class="col-md-2">
			<!-- Begin 10 Least Reputable Users -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">10 Least Reputable Users <span class="badge">5</span></h3>
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
			<!-- End 10 Least Reputable Users -->
		</div>
		<div class="col-md-4">
			<!-- Begin 10 most used commands -->
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
						}else{
							echo "No statistics available..";
						}
					?>
				</div>
			</div>
			<!-- End 10 most used commands -->
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<!-- Begin 10 last commands -->
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
			<!-- End 10 last commands -->
		</div>
		<div class="col-md-4">
			<!-- Begin Monthly Usage Graph -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Monthly Usage Graph <span class="badge">5</span></h3>
				</div>
				<div class="panel-body">
					<?php
						$sql = "SELECT * FROM daily_usages WHERE `Date` BETWEEN '".date("Y-m-d", strtotime("-30 days")) ."' AND '".date("Y-m-d", strtotime("+1 days"))."' ORDER by `Date` ASC LIMIT 31;";
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
											$total_uses = 0;
											while($row = $result->fetch_assoc()){
												$counter++;
												$total_uses = $total_uses + $row['Uses'];
												echo $prefix . " {";
												echo '  "Date": "' . $row['Date'] . '",' . "";
												echo '  "Uses": "' . $row['Uses'] . '",' . "";
												echo ' 	"Average": "' . number_format($total_uses / $counter, 2) . '"' . "";
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
						}else{
							echo "No statistics available..";
						}
					?>
				</div>
			</div>
			<!-- End Monthly Usage Graph -->
		</div>
		<div class="col-md-2">
			<!-- Begin Advertisement -->
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
						data-ad-format="auto">
					</ins>
					<script>
						(adsbygoogle = window.adsbygoogle || []).push({});
					</script>
				</div>
			</div>
			<!-- End Advertisement -->
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<!-- Begin Made Possible By -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Made Possible By</h3>
				</div>
				<div class="panel-body">
					<a href="https://www.cloudflare.com" target="_new"><img style="max-width:125px;" src="https://www.cloudflare.com/img/logo-cloudflare-dark.svg"></a>
					<a href="https://www.finlaydag33k.nl" target="_new"><img style="max-width:125px;" src="https://www.finlaydag33k.nl/wp-content/uploads/2016/08/cropped-logo-FDG-300-01.png"></a>
					<a href="https://jquery.com" target="_new"><img style="max-width:125px;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/JQuery_logo_text.svg/420px-JQuery_logo_text.svg.png"></a>
					<a href="https://php.net" target="_new"><img style="max-width:125px;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/PHP-logo.svg/711px-PHP-logo.svg.png"></a>
					<a href="https://nodejs.org/" target="_new"><img style="max-width:125px;" src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d9/Node.js_logo.svg/553px-Node.js_logo.svg.png"></a>
					<a href="https://getbootstrap.com/" target="_new"><img style="max-width:125px;" src="https://avatars3.githubusercontent.com/u/2918581?v=3&s=200"></a>
					<a href="https://www.github.com" target="_new"><img style="max-width:125px;" src="https://major.io/wp-content/uploads/2014/08/github.png"></a>
				</div>
			</div>
			<!-- End Made Possible By -->
		</div>
	</div>
	<?php } ?>
</div>