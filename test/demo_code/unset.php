<?php
session_start();
echo "Views1=".$_SESSION['views']."</br>";
unset($_SESSION['views']);
echo "Views1=".$_SESSION['views']."</br>";
?>
<html>
<a href="session_view.php">back to session demo.php</a></br>
</html>
