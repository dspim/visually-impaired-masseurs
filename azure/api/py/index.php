<?php
	header("Content-Type: text/plain");

	$arg = $_GET["arg"];

	system("python ../../python/analyze.py $arg");
?>
