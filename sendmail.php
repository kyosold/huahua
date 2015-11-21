<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Send Mail</title>

<link href="css/form_style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<h3>Send Email</h3>
<form action="./send.php" method="post" class="basic-grey">
    <h1>文件被做为附件发送
        <span>Please fill all the texts in the fields.</span>
    </h1>

    <label>
        <span>Sender Email :</span>
<?php
	
	echo "<select id='sender' name='sender'>";
	echo "<option value='wufang_840313@163.com'>wufang_840313@163.com</option>";
	echo "<option value='kyosold@qq.com'>kyosold@qq.com</option>";
	echo "</select>";

?>
    </label>
    
    <label>
        <span>Receiver Email :</span>
        <input id="email" type="email" name="email" placeholder="Valid Email Address" />
    </label>

	<label>
		<span>Subject :</span>
<?php

	$attach_list = $_GET['file_cb'];

	$file_list = getfilename_with_inodelist("store/", $attach_list);

	if (count($file_list) > 1) {
		echo '<input id="subject"  type="text" name="subject" placeholder="Subject" />';
	} else {
		$attach = $file_list[0];
		echo '<input id="subject"  type="text" name="subject" placeholder="Subject" value="Fw: '. basename($attach) .'" />';
	}

	$i = 1;
	foreach ($file_list as $attach) {
		echo "<label>";
		echo "<span>Attachments {$i}:</span>";
		echo "<input type='text' disabled value='". basename($attach) ."' />";
		echo "</label>";
		$i++;
	}

	foreach ($attach_list as $inode) {
		echo "<input type='hidden' name='attach[]' id='attach[]' value='{$inode}' />";
	}

function getfilename_with_inodelist($file_dir, $attach_list) {
    $result = array(); 
    $i = 0;

    $dir_hd = @opendir($file_dir) or die("can't open:{$file_dir}");
    while (($file = readdir($dir_hd)) !== false) {
        if (is_dir($file)) {
            // dir
        } else {
			$file = $file_dir ."/". $file;
			$file_inode = fileinode($file);
			foreach ($attach_list as $item) {
				if ($item == $file_inode) {
					$result[] = $file;
					break;
				}
			}	
		}
	}

	return $result;
}

?>
   	</label> 

    <label>
        <span>Message :</span>
        <textarea id="message" name="message" placeholder="Your Message"></textarea>
    </label> 

    <label>
        <span>&nbsp;</span> 
        <input type="submit" class="button" value="Send" /> 
    </label>    
</form>


</body>
</html>
