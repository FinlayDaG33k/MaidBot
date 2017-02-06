<style>

.fa {
	font-size: 25px;
    vertical-align: middle;
    text-shadow: 0px 0px 1px rgba(0, 0, 0, 0.65);
}

.fa-exclamation-triangle {
	color: #e95420;
}

.fa-trophy {
	color: #38b44a;
}

.fa-question {
    font-size: 25px;
}


</style>

<script type="text/javascript">
    jQuery(function ($) {
        $('.panel-heading span.clickable').on("click", function (e) {
            if ($(this).hasClass('panel-collapsed')) {
                // expand the panel
                $(this).parents('.panel').find('.panel-body').slideDown();
                $(this).removeClass('panel-collapsed');
                $(this).find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
            }
            else {
                // collapse the panel
                $(this).parents('.panel').find('.panel-body').slideUp();
                $(this).addClass('panel-collapsed');
                $(this).find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
            }
        });
    });
</script>


<script>
 window.onload = function() {
        setTimeout(function() {
          var ad = document.querySelector("ins.adsbygoogle");
          if (ad && ad.innerHTML.replace(/\s/g, "").length == 0) {
            content.innerHTML = "You seem to blocking Google AdSense ads in your browser. Please disable it and refresh the page :)<br />The ad is non-invasive, so you probably won't even notice it's there, but it will help me a great deal anyways :)";
          }
        }); 
      }; 
</script>
<div id="content">
<?php
if(!empty($_GET['username'])){
?>

<h1>Viewing Data for <?= htmlentities($_GET['username']); ?></h1>
<hr>

<div class="row">
	<div class="col-md-6">
		<div class="bs-component">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Statistics for <?= htmlentities($_GET['username']); ?></h3>
				</div>
				<div class="panel-body">
					<?php
						$array = [];
						$sql = "SELECT Rep,COUNT(*) as count FROM rep WHERE Username='".mysqli_real_escape_string($conn,$_GET['username'])."' AND `invalidate`='0' GROUP BY Rep ORDER BY count DESC;";
						$sql_output = $conn->query($sql);
						if ($sql_output->num_rows > 0) { 
							while($row = $sql_output->fetch_row()){
								$array["rep_received_" . $row[0]] = $row[1];
							}
							
						}else{
							$array["rep_received_+"] = "0";
							$array["rep_received_-"] = "0";
						}
						
						$sql = "SELECT Command,COUNT(*) as count FROM logs WHERE Username='".mysqli_real_escape_string($conn,$_GET['username'])."' AND `invalidate`='0' GROUP BY Command ORDER BY count DESC LIMIT 1;";
						$sql_output = $conn->query($sql);
						if ($sql_output->num_rows > 0) { 
							while($row = $sql_output->fetch_row()){
								$array["fav_command"] = $row[0];
								$array["fav_command_uses"] = $row[1];
							}
							
						}else{
							$array["fav_command"] = "";
							$array["fav_command_uses"] = "";
						}
						
						$sql = "SELECT Rep,COUNT(*) as count FROM rep WHERE Giver='".mysqli_real_escape_string($conn,$_GET['username'])."' AND `invalidate`='0' GROUP BY Rep ORDER BY count DESC;";
						$sql_output = $conn->query($sql);
						if ($sql_output->num_rows > 0) { 
							while($row = $sql_output->fetch_row()){
								$array["rep_given_" . $row[0]] = $row[1];
							}
							
						}else{
							$array["rep_given_+"] = "0";
							$array["rep_given_-"] = "0";
						}
						
						?>
						
						<?php 
							$cointrust = json_decode(file_get_contents('https://cointrust.xyz/wp-json/wp/v2/profile?slug=' . $_GET['username']));
							$cointrust_array = (array)$cointrust[0];
						?>
						<table class="table table-striped table-hover">
							<tr>
								<td>Positive Reputation Received:</td>
								<td><?= htmlentities($array['rep_received_+']); ?></td>
							</tr>
							<tr>
								<td>Negative Reputation Received:</td>
								<td><?= htmlentities($array['rep_received_-']); ?></td>
							</tr>
							<tr>
								<td>Positive Reputation Given:</td>
								<td><?= htmlentities($array['rep_given_+']); ?></td>
							</tr>
							<tr>
								<td>Negative Reputation Given:</td>
								<td><?= htmlentities($array['rep_given_-']); ?></td>
							</tr>
							<tr>
								<td>Favorite Command:</td>
								<td><?= htmlentities($array['fav_command']); ?></td>
							</tr>
							<tr>
								<td>Favorite Command Uses:</td>
								<td><?= htmlentities($array['fav_command_uses']); ?></td>
							</tr>
							<tr>
								<td>Cointrust Status:</td>
								<td><?php if(in_array($cointrust_array['suspicion'][0],array("hacker","scammer","abuser","dwc","spammer"))){ ?><i class="fa fa-exclamation-triangle" aria-hidden="true"></i><?php }elseif($cointrust_array['suspicion'][0] == "trustworthy"){?><i class="fa fa-trophy" aria-hidden="true"></i><?php }elseif($cointrust_array['suspicion'][0] == "none"){ ?><i class="fa fa-question" aria-hidden="true"></i><?php } ?> <?= $cointrust_array['suspicion'][0]; ?></td>
							</tr>
							<tr>
								<td>Cointrust Profile:</td>
								<td><?php if(!empty($cointrust_array['link'])){ ?><a href="<?= $cointrust_array['link']; ?>" target="_new">Visit <?= $cointrust_array['slug']; ?>'s Profile on Cointrust</a><?php }else{ ?><a href="https://www.cointrust.xyz/?s=<?= htmlentities($_GET['username']); ?>" target="_new">I could not find "<?= htmlentities($_GET['username']); ?>" on Cointrust Master</a><?php } ?></td>
							</tr>
							<tr>
								<td>MaidBot API URL:</td>
								<td><a href="<?= "https://maidbot.finlaydag33k.nl/api.php?username=" . htmlentities($_GET['username']); ?>" target="_new">Visit <?= htmlentities($_GET['username']); ?>'s page on the MaidBot API</a></td>
							</tr>
						</table>
						<?php
						//print_r($array);
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-2 col-md-offset-4">
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
	<div class="col-md-6">
		<div class="bs-component">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Reputations Received</h3>
				</div>
				<div class="panel-body">
					<?php
						$sql = "SELECT * FROM `rep` WHERE `Username`='".mysqli_real_escape_string($conn,$_GET['username'])."' AND `invalidate`='0' ORDER BY RAND() LIMIT 20;";
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
									<td><?= htmlentities($row["Giver"]); ?></td>
									<td><?= htmlentities($row["Rep"]); ?></td>
									<td><?= htmlentities($row["Reason"]); ?></td>
								</tr>
					<?php
							}
					?>
							</table>
					<?php
						} else {
							echo "I'm Sorry, but I could not get any statistics at the moment. Please try again soon!";
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
						$sql = "SELECT * FROM `rep` WHERE `Giver`='".mysqli_real_escape_string($conn,$_GET['username'])."' AND `invalidate`='0' ORDER BY RAND() LIMIT 20;";
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
									<td><?= htmlentities($row["Username"]); ?></td>
									<td><?= htmlentities($row["Rep"]); ?></td>
									<td><?= htmlentities($row["Reason"]); ?></td>
								</tr>
					<?php
							}
					?>
							</table>
					<?php
						} else {
							echo "I'm Sorry, but I could not get any statistics at the moment. Please try again soon!";
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
										<td><?= htmlentities($row["Date"]); ?></td>
										<td><?= htmlentities($row["Uses"]); ?></td>
									</tr>
						<?php
								}
						?>
								</table>
						<?php
							} else {
								echo "I'm Sorry, but I could not get any statistics at the moment. Please try again soon!";
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
										<td><a href="https://maidbot.finlaydag33k.nl/?username=<?= htmlentities($row["Username"]); ?>"><?= htmlentities($row["Username"]); ?></a></td>
										<td><?= htmlentities($row["COUNT(*)"]); ?></td>
									</tr>
						<?php
								}
						?>
								</table>
						<?php
							} else {
								echo "I'm Sorry, but I could not get any statistics at the moment. Please try again soon!";
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
					<h3 class="panel-title"><i class="fa fa-heart" aria-hidden="true"></i> 10 Most Reputable Users <span class="badge">5</span></h3>
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
									<td><a href="https://maidbot.finlaydag33k.nl/?username=<?= htmlentities($row["Username"]); ?>"><?= htmlentities($row["Username"]); ?></a></td>
									<td><?= htmlentities($row["Count"]); ?></td>
								</tr>
					<?php
							}
					?>
							</table>
					<?php
						} else {
							echo "I'm Sorry, but I could not get any statistics at the moment. Please try again soon!";
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
								<td><a href="https://maidbot.finlaydag33k.nl/?username=<?= htmlentities($row["Username"]); ?>"><?= htmlentities($row["Username"]); ?></a></td>
								<td><?= htmlentities($row["Count"]); ?></td>
							</tr>
					<?php
							}
					?>
							</table>
					<?php
						} else {
							echo "I'm Sorry, but I could not get any statistics at the moment. Please try again soon!";
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
									<td><?= htmlentities($row["command"]); ?></td>
									<td><?= htmlentities($row["COUNT(*)"]); ?></td>
								</tr>
					<?php
							}
					?>
							</table>
					<?php
						}else{
							echo "I'm Sorry, but I could not get any statistics at the moment. Please try again soon!";
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
								<td><a href="https://maidbot.finlaydag33k.nl/?username=<?= htmlentities($row["Username"]); ?>"><?= htmlentities($row["Username"]); ?></a></td>
								<td><?php echo htmlentities(str_replace($banlist, $replace,$row["Command"]));?></td>
								<td><?= htmlentities($row["Date"]); ?></td>
							</tr>
					<?php
							}
					?>
							</table>
					<?php
						} else {
							echo "I'm Sorry, but I could not get any statistics at the moment. Please try again soon!";
						}
					?>
				</div>
			</div>
			<!-- End 10 last commands -->
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
								<td><?= htmlentities($row["Username"]); ?></td>
								<td><?= htmlentities($row["Pot"]);?></td>
								<td><?= htmlentities($row["Date"]); ?></td>
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
		<div class="col-md-4">
			<!-- Begin Monthly Usage Graph -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-line-chart" aria-hidden="true"></i> Monthly Usage Graph <span class="badge">5</span></h3>
				</div>
				<div class="panel-body">
					<?php
						$sql = "SELECT * FROM daily_usages WHERE `Date` BETWEEN '".date("Y-m-d", strtotime("-30 day")) ."' AND '".date("Y-m-d")."' ORDER by `Date` ASC;";
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
							echo "I'm Sorry, but I could not get any statistics at the moment. Please try again soon!";
						}
					?>
				</div>
			</div>
			<!-- End Monthly Usage Graph -->
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-money" aria-hidden="true"></i> Donations</h3>
				</div>
				<div class="panel-body">
					<table class="table table-striped table-hover">
						<tr>
							<td>Total Donations in BTC: </td>
							<td>
								<i class="fa fa-btc" aria-hidden="true"></i> 
								<?php
									$wallet = json_decode(file_get_contents('https://blockchain.info/address/1BRoDCbnJ7kTS5dvVhjLdQnyqSWWjWC6SS?format=json'));
									echo number_format($wallet->total_received / 100000000,5);
								?>
							</td>
						</tr>
						<tr>
							<td>Total Donations in Bits: </td>
							<td><?php echo $wallet->total_received / 100; ?> Bits</td>
						</tr>
						<tr>
							<td>Total Donations in EUR: </td>
							<td>
								<i class="fa fa-eur" aria-hidden="true"></i> 
								<?php
									$currencies = json_decode(file_get_contents('https://blockchain.info/ticker'));
									echo number_format(($wallet->total_received / 100000000) * $currencies->EUR->sell,2);
								?>
							</td>
						</tr>
						<tr>
							<td>Total Donations in USD: </td>
							<td>
								<i class="fa fa-usd" aria-hidden="true"></i> 
								<?php
									echo number_format(($wallet->total_received / 100000000) * $currencies->USD->sell,2) ;
								?>
							</td>
						</tr>
					</table>
				</div>
			</div>
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