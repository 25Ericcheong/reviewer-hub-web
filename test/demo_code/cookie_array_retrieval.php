<?php
setcookie("admin", "Helen");

setcookie("cookie[three]","cookiethree", time()+60*30);
setcookie("cookie[two]","cookietwo", time()+60*30);
setcookie("cookie[one]","cookieone", time()+60*30);
?>

<html> 

<body>
<?php
echo "Let's play with cookie</br>";


// A way to view all cookies
if (isset($_COOKIE["cookie"])) {
	foreach ($_COOKIE["cookie"] as $name=>$value){
			echo "$name : $value </br>";
	}
}
?>
</body>

<html>
