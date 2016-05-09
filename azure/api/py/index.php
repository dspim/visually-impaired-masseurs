<?php
    header('Access-Control-Allow-Origin: *');
	header("Content-Type: text/plain");

	if(isset($_GET['arg'])) {
		$arg = $_GET["arg"];
	} else {
<<<<<<< HEAD
		$arg = "--compare assigned --between masseur --from 2015-04-1 --to 2015-04-30";	
=======
		$arg = "-h";	
>>>>>>> 48c3d1d188b4f275f6d8911eb5766d371854a9ff
	}
	
	system("python ../../python/analyze.py $arg");
?>
