<html>
	<body>

<?php
session_start();
$username = $_REQUEST["username"];
$password = $_REQUEST["password"];
if ($username == "infs" && $password == "3202"){
$_SESSION['username'] = $username;
$_SESSION['password'] = $password;

?>
My name is <?php echo $username; ?>!<br />
My password is <?php echo $password; ?> .

<?php
}
else {
	//echo "incorrect";
	header("Location: loginform.php");
}

?>
</body>
</html>

