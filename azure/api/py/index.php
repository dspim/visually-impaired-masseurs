<?php
    header('Access-Control-Allow-Origin: *');
	header("Content-Type: text/plain");

	if(isset($_GET['arg'])) {
		$arg = $_GET["arg"];
	} else {
		$arg = "--compare assigned --between masseur --from 2015-04-1 --to 2015-04-30";	
	}
	
	system("python ../../python/analyze.py $arg");
?>
