<?php
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
?>
<html>
<head>
	<title>The Ultimate Search Page</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<style type="text/css">
	body {
		margin: 0px;
		font-family: 'helvetica';
	}

	#header {
		height: 30px;
		background-color: #555;
	}

	#content {
		display: flex;
	}

	#left-content {
		width: 20%;
		height: 100%;
		padding: 8px;
		background-color: #a5a5a5;
	}

	#right-content {
		width: 80%;
		padding: 8px;
	}

	#foursquare-login {
		background-color: #3160df;
		padding: 8px;
		display: inline-block;
		border-radius: 12px;
		cursor: pointer;
		color: white;
	}

</style>
<body>
<div id="header">
	
</div>

<div id="content">
	<div id="left-content">
		<span style="font-size: 20px; font-weight: bold; margin-bottom: 8px; display: block;">Users</span>
		<?php

		$dir = new DirectoryIterator(__DIR__ . '/users');
		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDot() && substr($fileinfo->getFilename(), -5) === '.user') {
				?>
				<div id="user">
					<?php
					$user = json_decode(file_get_contents($fileinfo->getPathName()), true);
					echo "<div id='{$user['id']}'><a href='/oauth/user?id={$user['id']}'>{$user['firstName']} {$user['lastName']}</a></div>"
					?>
				</div>
				<?php
			}
		}

		?>
	</div>
	<div id="right-content">
		<div id="account"></div>
		<?php
		if (!$_SESSION['foursquareID']) {
			var_dump($_SESSION);
		?>
		<div id="foursquare-login">Foursquare Login</div>
		<?php
		}
		?>
	</div>
</div>
</body>

<script type="text/javascript">
	var clientID = 'LTNHKGWBGBQ1AOE2KZ1N1JM32H2I0C5H0XG4AYJHH5MISCA1';

	$('#foursquare-login').click(function() {
		window.location.href = `https://foursquare.com/oauth2/authenticate?client_id=${clientID}&response_type=code&redirect_uri=https://462.danny-harding.com/oauth/redirect/code.php`;
	});
</script>
</html>