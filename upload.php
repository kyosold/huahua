
<?php

header("Content-Type: text/html; charset=utf-8");

define('STORE_PATH', '/data1/htdocs/www/huahua/store');

set_time_limit(0);

if (is_uploaded_file($_FILES['uploadedfile']['tmp_name'])) {
	$upfile = $_FILES['uploadedfile'];

	$name = $upfile['name'];
	$type = $upfile['type'];
	$size = $upfile['size'];
	$tmp_name = $upfile['tmp_name'];

	if ($_Files['file']['error'] > 0) {
		die("Error:". $_FILES['file']['error'] ."</br>");	
	}

	//$new_file = "/data1/htdocs/www/huahua_software/{$name}";
	$new_file = STORE_PATH."/{$name}";
	if (file_exists($new_file)) {
		die("File: ". $name . " already exists.");
	}

	if (move_uploaded_file($tmp_name, $new_file)) {
		echo "Stored Successful.&nbsp;&nbsp;&nbsp;&nbsp; <a href='./index.html'>Continue Upload</a></br></br>";
		echo "<a href='./store.php' target='_blank'>view store</a>";
	} else {
		echo "Upload Failed: ";
		print_r(error_get_last());
	}

} else {
	echo "None file was uploaded!";
}


?>

