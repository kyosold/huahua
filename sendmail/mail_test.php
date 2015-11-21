<?php

//print_r($_POST);
//echo "--------------------------------<br><br>";
//print_r($_FILES);
//$qid = rand();
//echo "{'result':'succ', 'queue_id': '".$qid."'}";

define('MAIL_BODY_TYPE', 'HTML');

include "smtp.class.php";

$smtp_server = "smtp.163.com";
$smtp_port = "25";
$is_auth = "true";
$sasl_user = "kyosold@163.com";
$sasl_passwd = "metalryu6S";

$from = "kyosold@163.com";
$f_nickname = "tt7";
$to = "kyosold@qq.com";
$subject = "http api sendmail test";
$body = "this is sendmail http interface test";
$attach = array();

$mail_type = MAIL_BODY_TYPE;


if (isset($_POST['smtp_server']))
	$smtp_server = $_POST['smtp_server'];
if (isset($_POST['smtp_port']))
    $smtp_port = $_POST['smtp_port'];
if (isset($_POST['is_auth']))
    $is_auth = $_POST['is_auth'];
if (isset($_POST['sasl_user']))
    $sasl_user = $_POST['sasl_user'];
if (isset($_POST['sasl_passwd']))
    $sasl_passwd = $_POST['sasl_passwd'];

if (isset($_POST['from']))
    $from = $_POST['from'];
if (isset($_POST['f_nickname']))
    $f_nickname = $_POST['f_nickname'];
else
	$f_nickname = $from;
if (isset($_POST['to']))
    $to = $_POST['to'];
if (isset($_POST['subject']))
    $subject = $_POST['subject'];
if (isset($_POST['body']))
    $body = $_POST['body'];

if (isset($_FILES))
	$attach = $_FILES;

$smtp = new smtp($smtp_server, $smtp_port, $is_auth, $sasl_user, $sasl_passwd, $from);
$smtp->debug = TRUE;
$smtp->mail_type = $mail_type;
$snd = $smtp->sendmail($from, $f_nickname, $to, $subject, $body, $mail_type, "", "", "", $attach);
if ($snd == 1)
{
	out_json('succ', $smtp->qid);	
}
else
{
	$fail_str = "";
	foreach ($smtp->err as $val)
	{
		$fail_str .= $val.";";
	}
	out_json('fail', $fail_str);
}


function out_json($status, $res)
{
	die("{'result':'". $status ."', 'desc':'". $res ."'}");
}


?>


