<?php

header('Content-Type: application/json');

if ($_SERVER['HTTP_ACCEPT'] == 'application/vnd.byu.cs462.v1+json') {
	echo json_encode(array('version'=> 'v1'));
} else if ($_SERVER['HTTP_ACCEPT'] == 'application/vnd.byu.cs462.v2+json') {
        echo json_encode(array('version'=> 'v2'));
} else {
	echo json_encode(array('error'=> 'invalid version'));
}

?>
