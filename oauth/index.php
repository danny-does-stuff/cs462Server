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
		background-color: #3160df;
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

	.header {
		font-size: 20px;
		font-weight: bold;
		margin-bottom: 8px;
		display: block;
	}

	.user {
		cursor: pointer;
	}

	#right-content {
		width: 80%;
		padding: 8px;
	}

	.button {
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
		<span class="header">Users</span>
		<?php

		$dir = new DirectoryIterator(__DIR__ . '/users');
		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDot() && substr($fileinfo->getFilename(), -5) === '.user') {
				?>
				<div id="user">
					<?php
					$user = json_decode(file_get_contents($fileinfo->getPathName()), true);
					$div = "<div id='{$user['id']}' class='user' onclick='displayUserCheckin(\"{$user['id']}\")'>{$user['firstName']}";
					if (array_key_exists('lastName', $user)) {
						$div .= " {$user['lastName']}";
					}
					$div .= "</div>";
					echo $div;
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
		?>
		<div id="foursquare-login" class="button">Foursquare Login</div>
		<?php
		} else {
		?>
		<div id="foursquare-logout" class="button">Logout</div>
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

	$('#foursquare-logout').click(function() {
		// $.ajax({
		// 	url: '/oauth/logout.php',
		// 	success: function(data) {
				window.location.href = '/oauth/logout.php';
		// 	}
		// })
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
					$('#account').html('<span class="header">My Checkins</span>');

					data.response.venues.items.forEach(function(checkin) {
						appendCheckin(checkin.venue.name, checkin.venue.stats.checkinsCount);
					});
				}
			});
		} else {
			$('#account').html('FAILED');
		}
	}

	function displayUserCheckin(id) {
		<?php
			if (array_key_exists('foursquareID', $_SESSION)) {
		?>
		if (id == <?php echo "'{$_SESSION['foursquareID']}'" ?>) {
			displayCurrentUsersCheckins();
		} else {
		<?php
		}
		?>
			$.ajax({
				url: '/oauth/redirect/user.php?id=' + id,
				success: function(data) {
					$('#account').html('<span class="header">' + data.firstName + (data.lastName ? ` ${data.lastName}` : '') + '\'s Last Checkin</span>');
					if (data.checkins.items[0]) {
						appendCheckin(data.checkins.items[0].venue.name, data.checkins.items[0].venue.stats.checkinsCount)
					} else {
						appendCheckin('No Checkins!', '');
					}
				}
			});
		<?php
		if (array_key_exists('foursquareID', $_SESSION)) {
		?>
		}
		<?php
		}
		?>

	}

	function appendCheckin(name, count) {
		var $account = $('#account');

		var $checkin = $('<div class="checkin">').appendTo($account);

		$('<div class="name">').html(name).appendTo($checkin);

		$('<div class="checkins-count">').html('# of checkins: ' + count).appendTo($checkin);
	}
</script>
</html>