<html>
<head>
	<title>The Ultimate Search Page</title>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<style type="text/css">
	body {
		margin: 0px;
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
	}

</style>
<body>
<div id="header">
	
</div>
<div id="content">
	<div id="left-content">
		<span style="font-size: 20px; font-weight: bold; margin-bottom: 8px; display: block;">Users</span>
		<?php
		$clientID = 'LTNHKGWBGBQ1AOE2KZ1N1JM32H2I0C5H0XG4AYJHH5MISCA1';
		$clientSecret = 'BM2HK13RXDQWTHCDMMJTYSCJ0UIVBBDNTKLJXSN4PJNJVETT';

		$dir = new DirectoryIterator(__DIR__ . '/users');
		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDot() && substr($fileinfo->getFilename(), -5) === '.user') {
				?>
				<div id="user">
					<?php echo file_get_contents($fileinfo->getPathName()); ?>
				</div>
				<?php
			}
		}

		?>
	</div>
	<div id="right-content">
		<div id="foursquare-login">Foursquare Login</div>
	</div>
</div>
</body>

<script type="text/javascript">
	var clientID = 'LTNHKGWBGBQ1AOE2KZ1N1JM32H2I0C5H0XG4AYJHH5MISCA1';
	var clientSecret = 'BM2HK13RXDQWTHCDMMJTYSCJ0UIVBBDNTKLJXSN4PJNJVETT';

	$('#foursquare-login').click(function() {
		console.log('clicked');
		var url = `https://foursquare.com/oauth2/authenticate?client_id=${clientID}&response_type=code&redirect_uri=https://462.danny-harding.com/redirect.php`;
	});
</script>
</html>