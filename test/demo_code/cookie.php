<?php
setcookie("admin", "Tony");
//setcookie("admin", "Helen",time()+5);
setcookie("Tutor1", "Nathan");
//setcookie("tutor1", "Matt");
?>

<html> 

<body>
<?php
echo "Let's play with cookie</br>";

// Print a cookie
echo $_COOKIE["admin"]."</br>";

// A way to view all cookies
print_r($_COOKIE);
?>
</body>

<html>

