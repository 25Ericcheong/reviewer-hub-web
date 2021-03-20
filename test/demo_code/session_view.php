<?php
session_start();

if (isset($_SESSION['views']))
	$_SESSION['views'] = $_SESSION['views'] + 1;
else
	$_SESSION['views'] = 1;

echo "Views1=".$_SESSION['views']."</br>";

if (isset($_SESSION['views2']))
	$_SESSION['views2'] = $_SESSION['views2'] + 1;
else
	$_SESSION['views2'] = 1;

echo "Views2=".$_SESSION['views2']."</br>";
?>
<html>
<body>
	<a href="destroy_session.php">destroy session</a></br>
	<a href="unset.php">unset Views1</a></br>
</body>
</html>


</body>
</html>
