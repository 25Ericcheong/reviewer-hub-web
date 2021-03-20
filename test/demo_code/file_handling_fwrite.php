<?php
$myfile = fopen("test2.txt", "w");

fwrite($myfile, "I want to say ... ");
fwrite($myfile, "hello world ");


fclose($myfile);


?>