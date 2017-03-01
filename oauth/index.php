<?php
	include 'constants.php';

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
		height: 50px;
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
		font-weight: bold;
		padding-top: 8px;
	}

	#middle-content {
		width: 30%;
		padding: 8px;
	}

	#right-content {
		width: 50%;
		padding: 8px;
		border-left: 1px solid gray;
	}

	.checkin {
		margin-top: 8px;
		padding: 8px;
		width: 300px;
		background-color: #f5f5f5;
		border-radius: 3px;
		box-shadow: 3px, 3px, 3px, gray;
	}

	.name {
		font-weight: bold;
	}

	.checkins-count {
		font-size: 15px;
	}

	.button {
		background-color: #3160df;
		padding: 8px;
		display: inline-block;
		border-radius: 12px;
		cursor: pointer;
		color: white;
		margin-top: 16px;
	}

	#message-box {
		padding: 8px;
		background-color: #f5f5f5;
		display: flex;
	}

	#message-sender {
		margin-right: 8px;
	}

	#message {
		width: 500px;
	}

	form {
		margin-bottom: 0px;
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
				$user = json_decode(file_get_contents($fileinfo->getPathName()), true);
				$div = "<div id='{$user['id']}' class='user' onclick='displayUserCheckin(\"{$user['id']}\")'>{$user['firstName']}";
				if (array_key_exists('lastName', $user)) {
					$div .= " {$user['lastName']}";
				}
				$div .= "</div>";
				echo $div;
			}
		}

		?>
	</div>
	<div id="middle-content">
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
	var clientID = <?php echo "'$clientID'"?>;
	var baseURL = <?php echo "'$baseURL'"?>;
	var user = <?php echo ($_SESSION['user'] ? json_encode($_SESSION['user']) : null); ?>;
	console.log(user);

	$('#foursquare-login').click(function() {
		window.location.href = `https://foursquare.com/oauth2/authenticate?client_id=${clientID}&response_type=code&redirect_uri=${baseURL}/oauth/redirect/code.php`;
	});

	$('#foursquare-logout').click(function() {
		window.location.href = '/oauth/logout.php';
	});

<?php
	if (array_key_exists('accessToken', $_SESSION)) {
		echo "var accessToken = '{$_SESSION['accessToken']}'; \n";
?>
		displayCurrentUsersCheckins();
		addChatBox();
<?php
	}
?>

	function displayCurrentUsersCheckins() {
		if (accessToken) {
			$('#account').html('Loading...');
			$.ajax({
				url: 'https://api.foursquare.com/v2/users/self/checkins?oauth_token=' + accessToken + '&v=20170213',
				success: function(data) {
					$('#account').html('<span class="header">My Checkins</span>');

					data.response.checkins.items.forEach(function(checkin) {
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

	function addChatBox() {
		$('#content').append(`
		<div id="right-content">
			<span class="header">Chat</span>
			<div id="chat-box">
				
			</div>
			<div id="message-box">
				<div id="message-sender">${user.firstName}${(user.lastName ? ' ' + user.lastName : '')}</div>
				<form>
					<input type="text" name="message" id="message" />
					<button type="submit">Send</button>
				</form>
			</div>
		</div>`
		);
	}
</script>
</html>