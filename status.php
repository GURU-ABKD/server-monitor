<?php

	$server_check_version = '1.0.4';
	$start_time = microtime(TRUE);

	$operating_system = PHP_OS_FAMILY;

		// Linux CPU
		$load = sys_getloadavg();
		$cpuload = $load[0];
		// Linux MEM
		$free = shell_exec('free');
		$free = (string)trim($free);
		$free_arr = explode("\n", $free);
		$mem = explode(" ", $free_arr[1]);
		$mem = array_filter($mem, function($value) { return ($value !== null && $value !== false && $value !== ''); }); // removes nulls from array
		$mem = array_merge($mem); // puts arrays back to [0],[1],[2] after 
		$memtotal = round($mem[1] / 1000000,2);
		$memused = round($mem[2] / 1000000,2);
		$memfree = round($mem[3] / 1000000,2);
		$memshared = round($mem[4] / 1000000,2);
		$memcached = round($mem[5] / 1000000,2);
		$memavailable = round($mem[6] / 1000000,2);
		// Linux Connections
		$connections = `netstat -ntu | grep :80 | grep ESTABLISHED | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
		$totalconnections = `netstat -ntu | grep :80 | grep -v LISTEN | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -rn | grep -v 127.0.0.1 | wc -l`; 
	

	$memusage = round(($memavailable/$memtotal)*100);



	$phpload = round(memory_get_usage() / 1000000,2);

	$diskfree = round(disk_free_space(".") / 1000000000);
	$disktotal = round(disk_total_space(".") / 1000000000);
	$diskused = round($disktotal - $diskfree);

	$diskusage = round($diskused/$disktotal*100);

	if ($memusage > 85 || $cpuload > 85 || $diskusage > 85) {
		$trafficlight = 'red';
	} elseif ($memusage > 50 || $cpuload > 50 || $diskusage > 50) {
		$trafficlight = 'orange';
	} else {
		$trafficlight = '#2F2';
	}

	$end_time = microtime(TRUE);
	$time_taken = $end_time - $start_time;
	$total_time = round($time_taken,4);


function getMySQLVersion() { 
  $output = shell_exec('mysql -V'); 
  preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version); 
  return $version[0]; 
}
	$os = php_uname("s");

	if (isset($_GET['type']) && $_GET['type']=='json'){
		echo '{"ram":'.$memusage.',"cpu":'.$cpuload.',"disk":'.$diskusage.'}';
		exit;
	}
	$server_name = $_SERVER['SERVER_NAME'];
	if (isset($_GET['name'])) $server_name = $_GET['name']

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Server Status</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta1/css/all.css"></link>
	<style>
	*{
		margin: 0;
		padding: 0;
	}
	html {
		background: #FFF;
		overflow: hidden;
	}
	body {
		background: #FFF;
		font-family: Arial,sans-serif;
		margin: 0;
		padding: 0;
		color: #333;
		/*font-size: 15px;*/
    font-size: clamp(6px, 4vw, 15px);
    min-width: 100vw;
    min-height: 100vh;
	}
	p, hr{
		margin: 7px 0;
	}
	#container {
		width: 500px;
		margin: 10px auto;
		padding: 10px 20px;
		background: #efefef;
		border-radius: 5px;
		box-shadow: 0 0 5px #aaa;
		-webkit-box-shadow: 0 0 5px #aaa;
		-moz-box-shadow: 0 0 5px #aaa;
		box-sizing: border-box;
		-moz-box-sizing: border-box;
		-webkit-box-sizing: border-box;
/*    top: 40%;
    left: 50%;
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    position: absolute;*/
    max-width: 98%;
	}
	.description {
		font-weight: bold;
	}
	#trafficlight {
		float: right;
		margin-top: 8px;
		width: 16vw;
		height: 16vw;
		max-width: 60px;
		max-height: 60px;
		border-radius: 60px;
		background: <?php echo $trafficlight; ?>;
		border: 3px solid #333;
	}
	#details {
		/*font-size: 0.8em;*/
	}
	hr {
		border: 0;
		height: 1px;
		background-image: linear-gradient(to right, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0));
	}
	.big {
		font-size: 1.2em;
	}
	.fa{
		min-width: 1vw;
	}
	h1{
		font-size: 1.8em;
	}
	.footer {
		font-size: 0.5em;
		color: #888;
		text-align: center;
	}
	.footer a {
		color: #888;
	}
	.footer a:visited {
		color: #888;
	}
	.dark {
		background: #000;
		filter: invert(1) hue-rotate(180deg);
	}
	</style>
</head>
<body>
	<div id="container">
		<h1><i class="fa fa-server" aria-hidden="true"></i> <?php echo $server_name ?></h1><hr>

		<div id="trafficlight" class="nodark"></div>

		<p><span class="description big"><i class="fa fa-microchip" aria-hidden="true"></i> RAM Usage:</span> <span class="result big"><?php echo $memusage; ?>%</span></p>
		<p><span class="description big"><i class="fa fa-desktop" aria-hidden="true"></i> CPU Usage: </span> <span class="result big"><?php echo $cpuload; ?>%</span></p>
		<p><span class="description big"><i class="fa fa-solid fa-hard-drive"></i> HDD Usage: </span> <span class="result big"><?php echo $diskusage; ?>%</span></p>
		<?php
		if (isset($_GET['type']) && $_GET['type']=='full'){
			?>
		<hr>
		<p><span class="description"><i class="fa fa-microchip" aria-hidden="true"></i> RAM Total:</span> <span class="result"><?php echo $memtotal; ?> GB</span></p>
		<p><span class="description"><i class="fa fa-microchip" aria-hidden="true"></i> RAM Used:</span> <span class="result"><?php echo $memused; ?> GB</span></p>
		<p><span class="description"><i class="fa fa-microchip" aria-hidden="true"></i> RAM Available:</span> <span class="result"><?php echo $memavailable; ?> GB</span></p>
		<hr>
		<p><span class="description"><i class="fa fa-solid fa-hard-drive"></i> Hard Disk Free:</span> <span class="result"><?php echo $diskfree; ?> GB</span></p>
		<p><span class="description"><i class="fa fa-solid fa-hard-drive"></i> Hard Disk Used:</span> <span class="result"><?php echo $diskused; ?> GB</span></p>
		<p><span class="description"><i class="fa fa-solid fa-hard-drive"></i> Hard Disk Total:</span> <span class="result"><?php echo $disktotal; ?> GB</span></p>
		<hr>
		<div id="details">
			<p><span class="description"><i class="fa fa-server" aria-hidden="true"></i> Server Name: </span> <span class="result"><?php echo $_SERVER['SERVER_NAME']; ?></span></p>
			<p><span class="description"><i class="fa fa-globe" aria-hidden="true"></i> Server Addr: </span> <span class="result"><?php echo $_SERVER['REMOTE_ADDR']; ?></span></p>
			<p><span class="description"><i class="fa fa-desktop" aria-hidden="true"></i> OS: </span> <span class="result"><?php echo $os; ?></span></p>
			<p><span class="description"><i class="fa fa-brands fa-php"></i> PHP Version: </span> <span class="result"><?php echo phpversion(); ?></span></p>
			<p><span class="description"><i class="fa fa-solid fa-database"></i> MySQL Version: </span> <span class="result"><?php echo getMySQLVersion(); ?></span></p>			
			<p><span class="description"><i class="fa fa-solid fa-stopwatch"></i> Load Time: </span> <span class="result"><?php echo $total_time; ?> sec</span></p>
		</div>
		<?php
			}
			?>
<?php
if (!isset($_GET['controls'])){
?>
			<hr>
	<footer>
		<div class="footer">
			<?php
			if (isset($_GET['name'])){
				echo '<a href="?name='.$_GET['name'].'">Compact</a> | <a href="?type=full&name='.$_GET['name'].'">Full Details</a> | <a href="?type=json">JSON</a> | <a href="javascript:void(0)" onclick="toggleDarkMode();">Dark Mode</a>';
			} else {
				echo '<a href="?">Compact</a> | <a href="?type=full">Full Details</a> | <a href="?type=json">JSON</a> | <a href="javascript:void(0)" onclick="toggleDarkMode();">Dark Mode</a>';
			}
			?>
		</div>
	</footer>
<?php
}
?>
	</div>
<script>
	const toggleDarkMode = () => {
		if (localStorage.getItem('darkMode') && localStorage.getItem('darkMode') === 'true') {
			localStorage.setItem('darkMode',false);
		} else {
			localStorage.setItem('darkMode',true);
		}
		setDarkMode();
	}
	const setDarkMode = () => {
		if (localStorage.getItem('darkMode') && localStorage.getItem('darkMode') === 'true') {
			document.documentElement.classList.add('dark');
		} else {
			document.documentElement.classList.remove('dark');
		}
	}
	setDarkMode();
</script>
</body>
</html>