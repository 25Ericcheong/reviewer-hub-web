<?php
session_start();
if(isset($_SESSION['username']) && isset($_SESSION['password'])){
   header('Location: main.php');
}
?>
<html>
<head>
<title>Login form </title>
</head>
<body>
<form method="POST"     action="login_session.php">
	Login Information:<br>
	Name: <input type="text" name="username" size="20" class="content" /><br>
	Password: <input type="text" name="password" size="20" class="content" /><br>

	<input type="submit" value="Submit" name="submit" class="content" />
	<input type="reset" value="Reset" class="content" />
</form>

</body>
</html>
