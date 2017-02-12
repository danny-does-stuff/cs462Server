<?php
echo "<html><head></head><body>";
echo "<h1>HEADERS</h1>";
foreach(getallheaders() as $name => $value) {
	echo "$name: $value<br>";
}
echo "<br>";
echo "<h1>QUERY STRING</h1>";
foreach($_GET as $name => $value) {
	echo "$name: $value<br>";
}
echo "<br>";
echo "<h1>POST</h1>";
foreach($_POST as $name => $value) {
        echo "$name: $value<br>";
}
echo "</body></html>";
?>
