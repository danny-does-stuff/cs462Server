<?php
	$foursquareID = $_GET['id']
	header('Content-Type: application/json');
	echo file_get_contents("../users/{$foursquareID}.user");
?>