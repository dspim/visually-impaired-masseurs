<?php
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
	header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
	header("Content-Type: text/plain");

	if(isset($_GET['arg'])) {
		$arg = $_GET["arg"];
	} else {
		$arg = "-h";
	}

	system("python ../../python/analyze.py $arg");
?>
