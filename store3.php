<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8">

<?php


$host = "http://".$_SERVER['SERVER_NAME'];

function show_dir($file_dir) {
	$dir_hd = @opendir($file_dir) or die("can't open:{$file_dir}");

	while (($file = readdir($dir_hd)) !== false) {
		$file = $file_dir ."/". $file;
		if (is_dir($file)) {
			// dir
		} else {
			echo "<a href='{$host}/{$file}'>". basename($file) ."</a>". "    ".filesize($file)."KB</br>";
		}
	}
}

show_dir("./store/");


?>

	</head>
</html>

