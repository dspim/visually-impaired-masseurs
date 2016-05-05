<?php  
header("Content-Type: text/plain");
system("python ../../python/analyze.py --compare assigned --between shop");

?>
