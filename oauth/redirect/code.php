<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}


$clientID = 'LTNHKGWBGBQ1AOE2KZ1N1JM32H2I0C5H0XG4AYJHH5MISCA1';
$clientSecret = 'BM2HK13RXDQWTHCDMMJTYSCJ0UIVBBDNTKLJXSN4PJNJVETT';
$code = $_GET['code'];

$url = "https://foursquare.com/oauth2/access_token";
$url .= "?client_id=$clientID";
$url .= "&client_secret=$clientSecret";
$url .= "&grant_type=authorization_code";
$url .= "&redirect_uri=https://localhost:8888/oauth/redirect/accessToken.php";
$url .= "&code=$code";

$result = json_decode(file_get_contents($url), true);

$accessToken = $result['access_token'];

saveUserDetails($accessToken);

header("Location: /oauth");
die();


function saveUserDetails($accessToken) {
	$userURL = "https://api.foursquare.com/v2/users/self?oauth_token=$accessToken&v=20170213";
	$response = file_get_contents($userURL);

	$user = json_decode($response, true)['response']['user'];
	$user['accessToken'] = $accessToken;
	$_SESSION['foursquareID'] = $user['id'];
	$_SESSION['accessToken'] = $accessToken;

	$file = fopen("../users/{$user['id']}.user","w");
	fwrite($file, json_encode($user));
	fclose($file);
}

?>