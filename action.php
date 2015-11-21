<?php

$type = $_POST['type'];
$file = $_POST['file'];

if ($type == 'del') {
	if (strlen($file) > 0 && file_exists($file) ) {

		if (unlink($file)) {
			echo 0;
			return 0;
		} else {
			echo 1;
			return 1;
		}
	}

	echo 1;
	return 1;

}


?>
