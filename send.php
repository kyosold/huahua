<?php

include "smtp.class.php";

define('MAIL_BODY_TYPE', 'HTML');

$smtp_server = array();

$qq = array();
$qq['smtp_server'] = "smtp.qq.com";
$qq['smtp_port'] = "25";
$qq['is_auth'] = "true";
$qq['sasl_user'] = "xxxxx@qq.com";
$qq['sasl_passwd'] = "";
$qq['from'] = "";
$qq['f_nickname'] = "";

$w163 = array();
$w163['smtp_server'] = "smtp.163.com";
$w163['smtp_port'] = "25";
$w163['is_auth'] = "true";
$w163['sasl_user'] = "xxxxx@163.com";
$w163['sasl_passwd'] = "";
$w163['from'] = "";
$w163['f_nickname'] = "";

$smtp_server['xxx@163.com'] = $w163;
$smtp_server['xxx@qq.com'] = $qq;


$sender = $_POST['sender'];
$to = $_POST['email'];
$subject = $_POST['subject'];
$body = $_POST['message'];
$attach_list = $_POST['attach'];

$file_list = getfilename_with_inodelist("store/", $attach_list);

$mail_type = MAIL_BODY_TYPE;

$smtp_info = $smtp_server[$sender];
if (count($smtp_info) == 0) {
	$smtp_info = $qq;
}

$smtp = new smtp($smtp_info['smtp_server'], $smtp_info['smtp_port'], $smtp_info['is_auth'], $smtp_info['sasl_user'], $smtp_info['sasl_passwd'], $smtp_info['from']);
$smtp->debug = FALSE;
$smtp->mail_type = $mail_type;
$snd = $smtp->sendmail($smtp_info['from'], $smtp_info['f_nickname'], $to, $subject, $body, $mail_type, "", "", "", $file_list);
if ($snd == 1) {
	echo "Send Success.";
} else {
	$fail_str = "";
	foreach ($smtp->err as $val)
		$fail_str .= $val.";";

	echo "Send Fail: {$fail_str}";
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
