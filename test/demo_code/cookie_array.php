<?php
setcookie("admin", "Tony");

setcookie("cookie[three]","cookiethree");
setcookie("cookie[two]","cookietwo");
setcookie("cookie[one]","cookieone");
?>

<html> 

<body>
<?php
echo "Let's play with cookie</br>";


// A way to view all cookies
print_r($_COOKIE);
?>
</body>

<html>