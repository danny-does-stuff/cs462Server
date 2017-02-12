<?php
$clientID = 'LTNHKGWBGBQ1AOE2KZ1N1JM32H2I0C5H0XG4AYJHH5MISCA1';
$clientSecret = 'BM2HK13RXDQWTHCDMMJTYSCJ0UIVBBDNTKLJXSN4PJNJVETT';

$dir = new DirectoryIterator(__DIR__ . '/users');
foreach ($dir as $fileinfo) {
	if (!$fileinfo->isDot()) {
		if (substr($fileinfo->getFilename(), -5) === '.user') {
			var_dump($fileinfo->getFilename());
		}
	}
}

?>
