<?php  
header("Content-Type: text/plain");

if(isset($_GET['arg'])) {
	$arg = $_GET['arg'];
} else {
	$arg = "-h";
}
system("python ../../python/analyze.py" . " " . $arg);

?>
