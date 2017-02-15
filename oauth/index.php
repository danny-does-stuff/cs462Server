<?php
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
?>
<html>
<head>
	<title>Foursquare Logins</title>
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
					echo "<div id='{$user['id']}' onclick='displayUserCheckin({$user['id']})'>{$user['firstName']} {$user['lastName']}</div>"
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
		if (!array_key_exists('foursquareID', $_SESSION)) {
			// var_dump($_SESSION);
		?>
		<div id="foursquare-login">Foursquare Login</div>
		<?php
		}/*
			

			// 
			// var_dump($user);
			// echo "<br><br>";
		}*/
		?>
	</div>
</div>
</body>

<script type="text/javascript">
	var clientID = 'LTNHKGWBGBQ1AOE2KZ1N1JM32H2I0C5H0XG4AYJHH5MISCA1';

	$('#foursquare-login').click(function() {
		window.location.href = `https://foursquare.com/oauth2/authenticate?client_id=${clientID}&response_type=code&redirect_uri=http://localhost:8888/oauth/redirect/code.php`;
	});

<?php
	if (array_key_exists('foursquareID', $_SESSION)) {
		echo "var accessToken = '{$_SESSION['accessToken']}'; \n";
?>

		displayCurrentUsersCheckins();
		
<?php
	}
?>

	function displayCurrentUsersCheckins() {
		if (accessToken) {
			$('#account').html('Loading...');
			$.ajax({
				url: 'https://api.foursquare.com/v2/users/self/venuehistory?oauth_token=' + accessToken + '&v=20170213',
				success: function(data) {
					console.log(data.response.venues.items);
					var checkins = data.response.venues.items;
					var $account = $('#account');

					$account.empty();
					checkins.forEach(function(checkin) {
						var $checkin = $('<div class="checkin">').appendTo($account);

						$('<div class="name">').html(checkin.venue.name).appendTo($checkin);

						$('<div class="checkins-count">').html('# of checkins: ' + checkin.venue.stats.checkinsCount).appendTo($checkin);
					});
				}
			});
		} else {
			$('#account').html('FAILED');
		}
	}

	function displayUserCheckin(id) {
		$.ajax({
			url: '/oauth/redirect/user.php?id=' + id,
			success: function(data) {
				console.log(data);
			}
		});
	}
</script>
</html>