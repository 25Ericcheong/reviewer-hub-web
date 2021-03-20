<?php
session_start();
if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
   header('Location: loginform_session.php');
}
?>
<html>
<body>
<?php 
	echo 'Welcome to main page';
?>
</body>
</html>
