<?php
session_start();
$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
if ($username == 'infs' && $password == '3202'){
   $_SESSION['username'] = 'infs';
   $_SESSION['password'] = '3202';
   header('Location: main.php');
}
else {
	echo "Wrong!";
	header("Location: loginform_session.php");
}
?>