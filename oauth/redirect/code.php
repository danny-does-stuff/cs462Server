<?php
include '../constants.php';

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

$code = $_GET['code'];

$url = "https://foursquare.com/oauth2/access_token";
$url .= "?client_id=$clientID";
$url .= "&client_secret=$clientSecret";
$url .= "&grant_type=authorization_code";
$url .= "&redirect_uri=$baseURL/oauth/redirect/accessToken.php";
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
	$_SESSION['user'] = $user;
	$_SESSION['foursquareID'] = $user['id'];
	$_SESSION['accessToken'] = $accessToken;

	$file = fopen("../users/{$user['id']}.user","w");
	fwrite($file, json_encode($user));
	fclose($file);
}

?>