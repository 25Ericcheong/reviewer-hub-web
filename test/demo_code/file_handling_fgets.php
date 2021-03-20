<?php
$myfile = fopen("test.txt", "r") or die("Unable to open file!");
//echo fread($myfile,filesize("test.txt"));

while(!feof($myfile)) {
  echo fgets($myfile) . "<br>";
}

fclose($myfile);


?>