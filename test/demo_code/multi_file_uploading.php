<?php
$count = 0;
foreach ($_FILES['img']['name'] as $filename) 
{
    echo $filename.'</br>';
	$tmp=$_FILES['img']['tmp_name'][$count];
    $target ='upload/'.basename($filename);
    move_uploaded_file($tmp,$target);
    $count = $count+1;
	echo '...successfully uploaded '.$count.' file(s)</br>';
}
?>

