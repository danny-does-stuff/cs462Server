<?php
$redirects = array(
        'foo'=> 'http://www.google.com',
        'bar'=> 'http://www.facebook.com',
        'baz'=> 'http://www.iwastesomuchtime.com'
);
foreach($redirects as $key => $value) {
	if (array_key_exists($key, $_GET)) {
		header("Location: $value");
		die();
	}
}

echo "No Valid Redirects Found";
?>
