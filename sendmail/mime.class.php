<?php

define("DEF_CHARSET", "UTF-8");
define('DEF_SENDMAIL_CHARSET', 'GBK');

define('B_LONG_ADDR_PATTERN', '/(.*)<(.*)>$/');


$D_MIMEMAIL_ERRMSGS = array(
	MIMEMAIL_SAVETOCUR_OK => '成功保存到草稿夹!',
	MIMEMAIL_SAVETOOUT_OK => '邮件已发送，并成功保存到已发送邮件夹!',
	MIMEMAIL_ACTION_OK	=> '邮件发送成功!',
	MIMEMAIL_SENDMAIL_ERROR	=>	'邮件发送失败!',
	MIMEMAIL_OPEN_ATTFILE_ERROR	=>	'打开附件文件失败!',
	MIMEMAIL_CONTENT_TYPE_ERROR	=>	'解析邮件失败!',
	MIMEMAIL_MAIL_ADDRESS_EXCEED => '邮件地址过多!',
	MIMEMAIL_MAIL_ADDRESS_ERROR => '邮件地址错误!',
	MIMEMAIL_SAVETOOUT_ERROR	=> '邮件已发送，保存到已发送邮件夹失败!',
	MIMEMAIL_SAVETOOUT_OVERQUOTA	=> '邮件已发送! 邮箱已超容，保存到已发送邮件夹失败!',
	MIMEMAIL_SAVETOCUR_ERROR => '保存到草稿夹失败!',
	MIMEMAIL_SAVETOCUR_OVERQUOTA	=> '邮箱已超容，保存到草稿夹失败!',
	MIMEMAIL_CREATE_TMPFILE_ERROR => '创建发信临时文件失败!',
	MIMEMAIL_CACHEDIR_ERROR =>	'用户目录错误!',
	MIMEMAIL_ATT_OVERSIZE => '您上传的附件过大!',
	MIMEMAIL_AUTHCODE_EMPTY => '请输入验证码!',
	MIMEMAIL_AUTHCODE_ERROR => '验证码输入错误!',
	MIMEMAIL_AUTHCODE_TIMEOUT => '验证码已过期!',
	MIMEMAIL_MAIL_EMPTY_ADDRESS	=> '没有邮件发送地址',
	MIMEMAIL_ATTACHMENT_EMPTY => '附件不存在或为空',
	MIMEMAIL_SUBMIT_TOKEN_ERROR => '验证码错误',
	MIMEMAIL_SUBJECT_ERROR => '主题字数超过了256个字的限制!',
	MIMEMAIL_ATTACHMENT_PARTIAL => '附件上传不完整',
        MIMEMAIL_FROM_ADDRESS_ERROR => '发件人地址错误',
	);

define('MIMEMAIL_ACTION_OK', 0);
define('MIMEMAIL_SAVETOOUT_OK', 3);	//为了与老的RIA兼容
define('MIMEMAIL_SAVETOCUR_OK', 2);
define('MIMEMAIL_SENDMAIL_ERROR', -1);
define('MIMEMAIL_OPEN_ATTFILE_ERROR', -2);
define('MIMEMAIL_CONTENT_TYPE_ERROR', -3);
define('MIMEMAIL_MAIL_ADDRESS_ERROR', -4);
define('MIMEMAIL_SAVETOOUT_ERROR', -5);
define('MIMEMAIL_SAVETOOUT_OVERQUOTA', -6);
define('MIMEMAIL_SAVETOCUR_ERROR' , -7);
define('MIMEMAIL_SAVETOCUR_OVERQUOTA' , -8);
define('MIMEMAIL_CREATE_TMPFILE_ERROR' , -9);
define('MIMEMAIL_CACHEDIR_ERROR' , -10);
define('MIMEMAIL_ATT_OVERSIZE', -11);
define('MIMEMAIL_MAIL_ADDRESS_EXCEED', -12);
define('MIMEMAIL_AUTHCODE_EMPTY', -13);
define('MIMEMAIL_AUTHCODE_ERROR', -14);
define('MIMEMAIL_AUTHCODE_TIMEOUT', -15);
define('MIMEMAIL_MAIL_EMPTY_ADDRESS', -16);
define('MIMEMAIL_SUBMIT_TOKEN_ERROR', -17);
define('MIMEMAIL_ATTACHMENT_EMPTY', -18);
define('MIMEMAIL_SUBJECT_ERROR', -19);
define('MIMEMAIL_ATTACHMENT_PARTIAL', -20);
define('MIMEMAIL_FROM_ADDRESS_ERROR', -21);


/*
* RFC 822: CR LF
* character "CR": hex value 0D
* character "LF": hex value 0A
* @var string
*/
define("CRLF", "\r\n");

/*
* RFC 2822: 2.1.1. Line Length Limits
* @var int
*/
define("MIME_LINE_LENGTH_LIMIT", 76);

class mime_mail
{
	// the charset of the email
	var $_mail_charset = DEF_SENDMAIL_CHARSET;
	var $_mail_text_charset = DEF_SENDMAIL_CHARSET;

	// the subject of the email
	var $_mail_subject;

	// the from address including display-name of the email
	var $_mail_from;

	// just keep the address of from
	var $_mail_from_addr;

	// the sender Address of the email
	var $_mail_sender;	

	// the to address list of the email
	var $_mail_to;

	// the cc address list of the email
	var $_mail_cc;

	// the bcc address list of the email
	var $_mail_bcc;

	// the priority of the email, default 3
	var $_mail_priority;

	// need notification or not
	var $_mail_need_reply;

	// notification address of the mail
	var $_mail_notification_to;

	// message id 
	var $_mail_message_id;

	// the text body of the email
	var $_mail_text_body;

	// the html body of the email
	var $_mail_html_body;
	var $_mail_body_type;

	// the default body content-type
	var $_mail_type;

	// the header of the email
	var $_mail_header;

	// the body of the email
	var $_mail_body;

	// the attachments array of the email, include (path, name, type)
	var $attachments = array();

	// the encode attachments of the email
	var $_mail_subpart_attachments = array();

	// the attachments index of the email
	var $_mail_attachments_index = 0;

	// count of embedded attachments
	var $_mail_embedded_count = 0;

	// the boundary for 'multipart/mixed' type
	var $_mail_boundary_mix;

	// the boundary for 'multipart/related' type
	var $_mail_boundary_rel;

	// the boundary for 'multipart/alternative' type
	var $_mail_boundary_alt;

	// 自定义邮件头
	var $_mail_user_headers = array();

	var $mime_types = array(
		'gif'  => 'image/gif',
		'jpg'  => 'image/jpeg',
		'jpeg' => 'image/jpeg',
		'jpe'  => 'image/jpeg',
		'bmp'  => 'image/bmp',
		'png'  => 'image/png',
		'tif'  => 'image/tiff',
		'tiff' => 'image/tiff',
		'swf'  => 'application/x-shockwave-flash',
		'doc'  => 'application/msword',
		'xls'  => 'application/vnd.ms-excel',
		'ppt'  => 'application/vnd.ms-powerpoint',
		'pdf'  => 'application/pdf',
		'ps'   => 'application/postscript',
		'eps'  => 'application/postscript',
		'rtf'  => 'application/rtf',
		'bz2'  => 'application/x-bzip2',
		'gz'   => 'application/x-gzip',
		'tgz'  => 'application/x-gzip',
		'tar'  => 'application/x-tar',
		'zip'  => 'application/zip',
		'html' => 'text/html',
		'htm'  => 'text/html',
		'txt'  => 'text/plain',
		'css'  => 'text/css',
		'js'   => 'text/javascript',
		'eml'	 =>	'message/rfc822'
	);	

	// construct mime_mail
	function mime_mail()
	{
		$this->_mail_charset = DEF_SENDMAIL_CHARSET;
		$this->_mail_text_charset = DEF_SENDMAIL_CHARSET;
		$this->_mail_subject = "";
		$this->_mail_from = "";
		$this->_mail_sender = "";
		$this->_mail_to = "";
		$this->_mail_cc = "";
		$this->_mail_bcc = "";
		$this->_mail_priority = "";
		$this->_mail_needReply = false;
		$this->_mail_notificationTo = "";
		$this->_mail_message_id = sprintf('%s.%s', microtime(true), getmypid());
		$this->_mail_text_body = "";
		$this->_mail_html_body = "";
		$this->_mail_type = "";
		$this->_mail_header = "";
		$this->_mail_body = "";

		$this->_mail_subpart_attachments = array();
		$this->_mail_attachments_index = 0;
		$this->_mail_boundary_mix = "=-sinamail_mix_" . md5(uniqid(rand(), true));
		$this->_mail_boundary_rel = "=-sinamail_rel_" . md5(uniqid(rand(), true));
		$this->_mail_boundary_alt = "=-sinamail_alt_" . md5(uniqid(rand(), true));
	}

	// get message id
	function get_messageid()
	{
		return $this->_mail_message_id;
	}

	function conv_enc($s, $charset=DEF_SENDMAIL_CHARSET)
	{
		if ($charset == DEF_CHARSET)
		{
			return array($s, DEF_CHARSET);
		}

		if ($charset == 'GBK' && preg_match('/[^\x00-\x7f\x{3000}-\x{303F}\x{4e00}-\x{9fff}\x{ff00}-\x{ffef}]/u', $s)) {
			return array($s, DEF_CHARSET);
		}

		$es = mb_convert_encoding($s, $charset, DEF_CHARSET);
		if ($es === false) {
			return array($s, DEF_CHARSET);
		} else {
			return array($es, $charset);
		}
	}

	// 取得用户自定义邮件头
	function get_user_header($name = '')
	{
		if (!$name)
			return $this->_mail_user_headers;

		return (isset($this->_mail_user_headers[$name])
					? $this->_mail_user_headers[$name]
					: false);
	}

	// 设置用户自定义邮件头
	function set_user_header($value, $name='')
	{
		$d = array();
		if ($name == '')
		{
			if (is_array($value))
				$d = $value;
			else
				return;
		}
		else
		{
			$d[$name] = $value;
		}	

		foreach ($d as $k => $v)
		{
			if (is_scalar($v))
				$this->_mail_user_headers[$k] = $v;

		}
	}	

	// 设置邮件主题
	function set_subject($subject)
	{
		if (!is_null($subject))
		{
			if (!$this->is_big($subject))
			{
				$this->_mail_subject = $subject;
			}
			else
			{
				list($subject, $charset) = $this->conv_enc($subject);
				$this->_mail_subject = '=?'.$charset.'?B?' .base64_encode($subject) .'?=';
			}
		}
	}

	// 设置真实发信地址
	function set_sender($sender)
	{
		$this->_mail_sender = $sender;
	}

	// 设置发件人
	// @param string $from 发件人地址
	// @param string $nick_name 发件人昵称
	function set_from($from, $nick_name='')
	{
		$this->_mail_from_addr = $from;
		if ($nick_name == '')
		{
			$this->_mail_from = $from;
		}
		else
		{
			if ($this->is_big($nick_name))
			{
				list($nick_name, $charset) = $this->conv_enc($nick_name);
				$this->_mail_from = sprintf("\"=?%s?B?%s?=\" <%s>", $charset, base64_encode($nickname), $from);
			}
			else
			{
				$this->_mail_from = '"' . $nickname . '" <'.$from .'>';
			}
		}
	}

	// 设置收件人列表
	function set_to($mail_to)
	{
		if (!is_null($mail_to))
		{
			list($mail_to, $charset) = $this->conv_enc($mail_to);
			$this->_mail_to = $this->_encode_addr($mail_to, $charset);
		}
	}

	// 设置抄送列表
	function set_cc($mail_cc)
	{
		if (!is_null($mail_cc))
		{
			list($mail_cc, $charset) = $this->conv_enc($mail_cc);
			$this->_mail_cc = $this->_encode_addr($mail_cc, $charset);
		}
	}

	// 设置密送列表
	function set_bcc($mail_bcc)
	{
		if (!is_null($mail_bcc))
		{
			list($mail_bcc, $charset) = $this->conv_enc($mail_bcc);
			$this->_mail_bcc = $this->_encode_addr($mail_bcc, $charset);
		}
	}

	// 设置邮件正文类型
	function set_body_type($text_type)
	{
		if ($text_type == 'html')
			$this->_mail_body_type = $text_type;
		else
			$this->_mail_body_type = 'plain';
	}

	// 设置邮件优先级别
	function set_priority($priority = 3)
	{
		$priority = !is_null($priority) ? $priority: 3;
		$this->_mail_priority = $priority;
	}

	// 设置回执
	// @param string $is_need_reply 是否需要回执
	// @param string $addr_notification_to 回执邮件地址
	function set_notification_to($is_need_reply=false, $addr_notification_to='')
	{
		if ($is_need_reply == true)
		{
			$this->_mail_need_reply = $is_need_reply;
			if (empty($addr_notification_to))
					$this->_mail_notification_to = $this->_mail_sender;
			else
				$this->_mail_notification_to = $addr_notification_to;
		}
	}

	// 添加文本格式正文
	function add_text_body($text)
	{
		list($text, $charset) = $this->conv_enc($text, $this->_mail_text_charset);
		if ($charset != $this->_mail_text_charset)
		{
			$this->_mail_text_body = mb_convert_encoding($this->_mail_text_body, $charset, $this->_mail_text_charset);
			$this->_mail_text_charset = $charset;
		}
		$this->_mail_text_body .= $text;
	}
	
	// 添加html格式正文
	function add_html_body($html)
	{
		list($html, $charset) = $this->conv_enc($html, $this->_mail_charset);
		if ($charset != $this->_mail_charset) {
			$this->_mail_html_body = mb_convert_encoding($this->_mail_html_body, $charset, $this->_mail_charset);
			$this->_mail_charset = $charset;
		}
		$this->_mail_html_body .= $html;
	}	

	// 生成邮件header
	function _build_header($mail_content_type)
	{
		$this->_mail_header ='Return-path: '. $this->_mail_sender . CRLF;

		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $fromIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $fromIp = $_SERVER['REMOTE_ADDR'];
                }
                $a = explode('|', $fromIp);
                $ip = trim($a[0]);
                if (!$ip) {
                    $ip = $_SERVER['SERVER_ADDR'];
                }
		$this->_mail_header .=
				'Received: from ' .$this->_mail_sender . '(['. $ip .']) by '
				. $_SERVER['HTTP_HOST'] . ' via HTTP;' . CRLF
				.' '. date("D, d M Y H:i:s") . " +0800 (CST)" . CRLF;

		$this->_mail_header .=
			 'Date: ' . date("D, d M Y H:i:s") . " +0800 " . CRLF;

		if (strcasecmp($this->_mail_sender, $this->_mail_from_addr) != 0) {
			$this->_mail_header .= 'Sender: ' . $this->_mail_sender . CRLF;
		}

		if (!is_null($this->_mail_from))
			$this->_mail_header .=
					"From: " . $this->_split_addr_list($this->_mail_from) . CRLF;

		if (!is_null($this->_mail_to))
			$this->_mail_header .=
					"To: " .  $this->_split_addr_list($this->_mail_to) . CRLF;

		if (!empty($this->_mail_cc))
			$this->_mail_header .=
					"Cc: " .  $this->_split_addr_list($this->_mail_cc) . CRLF;

		if (!empty($this->_mail_bcc))
			$this->_mail_header .= "Bcc: " . $this->_split_addr_list($this->_mail_bcc) . CRLF;

		if (!is_null($this->_mail_subject))
			$this->_mail_header .= "Subject: " . $this->_mail_subject . CRLF;

		$this->_mail_header .= "MIME-Version: 1.0" . CRLF;
		$this->_mail_header .= "X-Priority: " .$this->_mail_priority . CRLF;

		if ($this->_mail_needReply == true)
			$this->_mail_header .=
					"Disposition-Notification-To: " .$this->_mail_notificationTo . CRLF;

		$this->_mail_header .= 'X-MessageID: ' . $this->_mail_messageId . CRLF;

		$this->_mail_header .= 'X-Originating-IP: [' . $_SERVER["SERVER_ADDR"] . "]". CRLF;
		$this->_mail_header .= "X-Mailer: Sina WebMail 4.0" . CRLF;

		foreach ($this->_mail_user_headers as $k => $v) {
			$hn = str_replace(' ', '-', ucwords(str_replace('-', ' ', $k)));
			$this->_mail_header .= "X-$hn: $v" . CRLF;
		}

		$this->_mail_header .= $mail_content_type;
	}

	// 生成邮件体
	function _build_body()
	{
		if(count($this->attachments) > 0)
		{
			foreach ($this->attachments as $a_AttFile)
			{
				$r = $this->_get_attachment($a_AttFile['attFilePath'], $a_AttFile['attName'],
					$a_AttFile['attType'], $a_AttFile['attEmbedded']);
				if ($r != MIMEMAIL_ACTION_OK)
				{
                	return $r;
                }
			}
		}

		switch ($this->_parse_elements())
		{
			case 1:		//text/plain
				$this->_build_header("Content-Type: text/plain; charset=$this->_mail_text_charset\nContent-Transfer-Encoding: base64");
				$this->_mail_body = $this->_get_text_mail_body();
				break;

			case 3:		//text/plain && text/html
				$this->_build_header("Content-Type: multipart/alternative;\n\t boundary=\"$this->_mail_boundary_alt\"");
				$this->_mail_body = "--" . $this->_mail_boundary_alt . CRLF;
				$this->_mail_body .=
						"Content-Type: text/plain;\n\tcharset=$this->_mail_text_charset" . CRLF;

				$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF ;
				$this->_mail_body .= "Content-Disposition: inline" . CRLF . CRLF;
				$this->_mail_body .= $this->_get_text_mail_body() . CRLF . CRLF;
				$this->_mail_body .= "--" . $this->_mail_boundary_alt . CRLF;

				$this->_mail_body .=
						"Content-Type: text/html; \n\tcharset=$this->_mail_charset" . CRLF;
				$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF ;
				$this->_mail_body .= "Content-Disposition: inline" . CRLF . CRLF;
				$this->_mail_body .= $this->_get_html_mail_body() . CRLF . CRLF;
				$this->_mail_body .= "--" . $this->_mail_boundary_alt . "--" . CRLF;
				break;

			case 5:		// text/plain && attachments
				$this->_build_header("Content-Type: multipart/mixed;\n\t boundary=\"$this->_mail_boundary_mix\"");
				$this->_mail_body = "--" . $this->_mail_boundary_mix . CRLF;
				$this->_mail_body .=
						"Content-Type: text/plain;\n\tcharset=$this->_mail_text_charset" . CRLF;
				$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF ;
				$this->_mail_body .= "Content-Disposition: inline" . CRLF . CRLF;
				$this->_mail_body .= $this->_get_text_mail_body() . CRLF . CRLF;

				foreach($this->_mail_subpart_attachments as $value)
				{
					$this->_mail_body .= "--" . $this->_mail_boundary_mix . CRLF;
					$this->_mail_body .=
						"Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . CRLF;
					$this->_mail_body .=
						"Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . CRLF;
					if($value['type'] == 'message/rfc822')
					{
						$this->_mail_body .= "Content-Transfer-Encoding: 8bit" . CRLF . CRLF;
					}
					else
					{
						$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF . CRLF;
					}
					$this->_mail_body .= $value['content'] . CRLF . CRLF;
				}

				$this->_mail_body .= "--" . $this->_mail_boundary_mix . "--" . CRLF;
				break;

			case 7:		//text/plain && text/html && attachment
				$this->_build_header("Content-Type: multipart/mixed;\n\t boundary=\"$this->_mail_boundary_mix\"");
				$this->_mail_body = "--" . $this->_mail_boundary_mix . CRLF;
				$this->_mail_body .= "Content-Type: multipart/alternative;\n\t boundary=\"$this->_mail_boundary_alt\"" . CRLF . CRLF;
				$this->_mail_body .= "--" . $this->_mail_boundary_alt . CRLF;
				$this->_mail_body .= "Content-Type: text/plain;\n\tcharset=$this->_mail_text_charset" . CRLF;
				$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF ;
				$this->_mail_body .= "Content-Disposition: inline" . CRLF . CRLF;
				$this->_mail_body .= $this->_get_text_mail_body() . CRLF . CRLF;
				$this->_mail_body .= "--" . $this->_mail_boundary_alt . CRLF;

				$this->_mail_body .= "Content-Type: text/html; charset=$this->_mail_charset" . CRLF;
				$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF ;
				$this->_mail_body .= "Content-Disposition: inline" . CRLF . CRLF;
				$this->_mail_body .= $this->_get_html_mail_body() . CRLF . CRLF;
				$this->_mail_body .= "--" . $this->_mail_boundary_alt . "--" . CRLF . CRLF;

				foreach($this->_mail_subpart_attachments as $value)
				{
					$this->_mail_body .= "--" . $this->_mail_boundary_mix . CRLF;
					$this->_mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . CRLF;
					$this->_mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . CRLF;
					if($value['type'] == 'message/rfc822')
					{
						$this->_mail_body .= "Content-Transfer-Encoding: 8bit" . CRLF . CRLF;
					}
					else
					{
						$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF . CRLF;
					}
					$this->_mail_body .= $value['content'] . CRLF . CRLF;
				}
				$this->_mail_body .= "--" . $this->_mail_boundary_mix . "--" . CRLF;
				break;

			case 11:
				$this->_build_header("Content-Type: multipart/related; type=\"multipart/alternative\";\n\t boundary=\"$this->_mail_boundary_rel\"");
				$this->_mail_body = "--" . $this->_mail_boundary_rel . CRLF;
				$this->_mail_body .= "Content-Type: multipart/alternative;\n\t boundary=\"$this->_mail_boundary_alt\"" . CRLF . CRLF;
				$this->_mail_body .= "--" . $this->_mail_boundary_alt . CRLF;
				$this->_mail_body .= "Content-Type: text/plain;\n\tcharset=$this->_mail_text_charset" . CRLF;
				$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF ;
				$this->_mail_body .= "Content-Disposition: inline" . CRLF . CRLF;
				$this->_mail_body .= $this->_get_text_mail_body() . CRLF . CRLF;
				$this->_mail_body .= "--" . $this->_mail_boundary_alt . CRLF;

				$this->_mail_body .= "Content-Type: text/html; charset=$this->_mail_charset" . CRLF;
				$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF ;
				$this->_mail_body .= "Content-Disposition: inline" . CRLF . CRLF;
				$this->_mail_body .= $this->_get_html_mail_body() . CRLF . CRLF;
				$this->_mail_body .= "--" . $this->_mail_boundary_alt . "--" . CRLF . CRLF;

				foreach($this->_mail_subpart_attachments as $value)
				{
					if ($value['embedded']){
						$this->_mail_body .= "--" . $this->_mail_boundary_rel . CRLF;
						$this->_mail_body .= "Content-ID: <" . $value['embedded'] . ">" . CRLF;
						$this->_mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . CRLF;
						$this->_mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . CRLF;
						$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF . CRLF;
						$this->_mail_body .= $value['content'] . CRLF . CRLF;
					}
				}
				$this->_mail_body .= "--" . $this->_mail_boundary_rel . "--" . CRLF;
				break;

			case 15:
				$this->_build_header("Content-Type: multipart/mixed;\n\t boundary=\"$this->_mail_boundary_mix\"");
				$this->_mail_body .= "--" . $this->_mail_boundary_mix . CRLF;

				$this->_mail_body .= "Content-Type: multipart/related; type=\"multipart/alternative\"; boundary=\"$this->_mail_boundary_rel\"" . CRLF . CRLF;
				$this->_mail_body .= "--" . $this->_mail_boundary_rel . CRLF;

				$this->_mail_body .= "Content-Type: multipart/alternative;\n\t boundary=\"$this->_mail_boundary_alt\"" . CRLF . CRLF;
				$this->_mail_body .= "--" . $this->_mail_boundary_alt . CRLF;

				$this->_mail_body .= "Content-Type: text/plain" . CRLF;
				$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF ;
				$this->_mail_body .= "Content-Disposition: inline" . CRLF . CRLF;
				$this->_mail_body .= $this->_get_text_mail_body() . CRLF . CRLF;
				$this->_mail_body .= "--" . $this->_mail_boundary_alt . CRLF;

				$this->_mail_body .= "Content-Type: text/html; charset=$this->_mail_charset" . CRLF;
				$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF ;
				$this->_mail_body .= "Content-Disposition: inline" . CRLF . CRLF;
				$this->_mail_body .= $this->_get_html_mail_body() . CRLF . CRLF;
				$this->_mail_body .= "--" . $this->_mail_boundary_alt . "--" . CRLF . CRLF;

				foreach($this->_mail_subpart_attachments as $value){
					if ($value['embedded']){
						$this->_mail_body .= "--" . $this->_mail_boundary_rel . CRLF;
						$this->_mail_body .= "Content-ID: <" . $value['embedded'] . ">" . CRLF;
						$this->_mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . CRLF;
						$this->_mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . CRLF;
						$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF . CRLF;
						$this->_mail_body .= $value['content'] . CRLF . CRLF;
					}
				}
				$this->_mail_body .= "--" . $this->_mail_boundary_rel . "--" . CRLF . CRLF;

				foreach($this->_mail_subpart_attachments as $value)
				{
					if (!$value['embedded']){
						$this->_mail_body .= "--" . $this->_mail_boundary_mix . CRLF;
						$this->_mail_body .= "Content-Type: " . $value['type'] . "; name=\"" . $value['name'] . "\"" . CRLF;
						$this->_mail_body .= "Content-Disposition: attachment; filename=\"" . $value['name'] . "\"" . CRLF;
						//$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF . CRLF;
						if($value['type'] == 'message/rfc822')
						{
							$this->_mail_body .= "Content-Transfer-Encoding: 8bit" . CRLF . CRLF;
						}
						else
						{
							$this->_mail_body .= "Content-Transfer-Encoding: base64" . CRLF . CRLF;
						}
						$this->_mail_body .= $value['content'] . CRLF . CRLF;
					}
				}
				$this->_mail_body .= "--" . $this->_mail_boundary_mix . "--" . CRLF;
				break;

			default:
				return MIMEMAIL_CONTENT_TYPE_ERROR;
		}
		//$this->sended_index++;
		return MIMEMAIL_ACTION_OK;
	}

	// 分析content-type
	function _parse_elements()
	{
		if ($this->_mail_body_type == 'html')
		{
			$this->_mail_type = 3;
			if (!empty($this->mail_html))
			{
				if (empty($this->mail_text))
					$this->mail_text = $this->html_to_text($this->mail_html);
			}
		}
		else
		{
			$this->_mail_type = 1;
		}

		if ($this->_mail_attachments_index != 0)
		{
			if ($this->_mail_type == 3 && $this->_mail_embedded_count > 0)
			{
				$this->_mail_type = $this->_mail_type + 8;
				if ((count($this->attachments) - $this->_mail_embedded_count) >= 1)
				{
					$this->_mail_type = $this->_mail_type + 4;
				}
			}
			else if (count($this->attachments) > 0)
			{
				$this->_mail_type = $this->_mail_type + 4;
			}
		}

		return $this->_mail_type;
	}

	// 添加附件
	// @param string $attFile 附件文件绝对路径
	// @param string $attName 附件名称
	// @param string $attFileType 附件文件类型
	// @param bool   $attEmbedded 附件是否为嵌入
	// @return 成功 0，失败 错误代码	
	function add_attachment($sAttFilePath, $sAttName, $sAttType='', $sAttEmbedded = false)
	{
		if (is_null($sAttFilePath)) {
			return;
		}

		$embedded = false;
		if ($sAttEmbedded) {
			$this->_mail_embedded_count++;
			$embedded = sprintf('part%s.%s', $this->_mail_embedded_count, $this->_mail_message_id);
		}

		$this->attachments[$this->_mail_attachments_index] = array(
			'attFilePath' => $sAttFilePath,
			'attName' =>	$sAttName ,
			'attType' => $sAttType,
			'attEmbedded' => $embedded
			);
		$this->_mail_attachments_index++;
	}

	// 获取附件subpart信息
	// @param string $attFile 附件文件绝对路径
	// @param string $attName 附件名称
	// @param string $attFileType 附件文件类型
	// @param int    $attEmbedded 附件嵌入ID
	// @return 成功 0，失败 错误代码
	function _get_attachment($attFile, $attName, $attFileType = "", $attEmbedded)
	{
		$content = file_get_contents($attFile);

		if ($content !== false)
		{
			list($attName, $charset) = $this->conv_enc($attName);
			$attFileType = empty($attFileType) ? $this->_get_mime_type($attName) : $attFileType;
			if($attFileType == 'message/rfc822')
			{
				$this->_mail_subpart_attachments[] = array(
				'content' => $content,
				'name' => '=?'.$charset.'?B?'.base64_encode($attName).'?=',
				'type' => $attFileType,
				'embedded' => false
				);
			}
			else
			{
				$this->_mail_subpart_attachments[] = array(
				'content' => chunk_split(base64_encode($content), MIME_LINE_LENGTH_LIMIT, CRLF),
				'name' => '=?'.$charset.'?B?'.base64_encode($attName).'?=',
				'type' => $attFileType,
				'embedded' => $attEmbedded
				);
			}

			return MIMEMAIL_ACTION_OK;
		}
		else
		{
			return	MIMEMAIL_OPEN_ATTFILE_ERROR;
		}
	}

	// 将文本格式正文按照rfc规范编码并拆分成行
	function _get_text_mail_body()
	{
		if(isset($this->_mail_text_body) && !is_null($this->_mail_text_body))
			return chunk_split(
					base64_encode($this->_mail_text_body), MIME_LINE_LENGTH_LIMIT, CRLF);
		else
			return	'';
	}	

	// 将html格式正文按照rfc规范编码并拆分成行
	function _get_html_mail_body()
	{
		if(isset($this->_mail_html_body) && !is_null($this->_mail_html_body)) {
			if ($this->_mail_embedded_count > 0) {
				$this->_mail_html_body = $this->_replace_embedded($this->_mail_html_body);
			}
			return chunk_split(
					base64_encode($this->_mail_html_body), MIME_LINE_LENGTH_LIMIT, CRLF);
		} else {
			return	'';
		}
	}

	// 将嵌入附件的标记替换为实际的Content-ID
	function _replace_embedded($body)
	{
		foreach ($this->attachments as $att) {
			if (!$att['attEmbedded']) {
				continue;
			}

			$search = '__CID__' . md5($att['attFilePath']);
			$repl = 'cid:' . $att['attEmbedded'];
			$body = str_replace($search, $repl, $body);
		}
		return $body;
	}

	// 获取附件类型
	function _get_mime_type($attName)
	{
		$ext_array = explode(".", $attName);
		if (($last = count($ext_array) - 1) != 0)
		{
			$ext = strtolower($ext_array[$last]);
			if (isset($this->mime_types[$ext]))
				return $this->mime_types[$ext];
		}
		return "application/octet-stream";
	}

	// 按照rfc规范将邮件列表拆分成行
	function _split_addr_list($inputAddr)
	{
		if(is_null($inputAddr) || MIME_LINE_LENGTH_LIMIT > strlen($inputAddr))
			return	$inputAddr;
		else
		{
			$a_splitAddr = explode("," , $inputAddr);
			$curLen = 0;
			foreach($a_splitAddr as $key => $address)
			{
				$curLen += strlen($address);

				if(MIME_LINE_LENGTH_LIMIT < $curLen)
				{
					$outputAddr .= CRLF. ' '. $address . ",";
					$curLen = strlen($address);
				}
				else
				{
					$outputAddr .= $address . ",";
				}
			}
			return $outputAddr;
		}
	}

	// encode addlist ofr mime header
	function _encode_addr($addr_str, $charset = DEF_SENDMAIL_CHARSET)
	{
		$addr_ret = '';

		$addr_str = mb_ereg_replace(mb_convert_encoding('，', $charset, DEF_CHARSET), ',', $addr_str, $charset);
		$addrs = $this->_split_address_str($addr_str);
		foreach ($addrs as $key => $addr)
		{
			$addr = trim($addr);
			if ($key != 0) $addr_ret .= ', ';

			if (preg_match(B_LONG_ADDR_PATTERN, $addr, $addr_split))
			{

				$addr_name = trim($addr_split[1]);
				$addr_name = trim($addr_name, '"');
				$addr_mail = trim($addr_split[2]);
				mb_internal_encoding($charset);

				if (!$this->is_big($addr_name))
				{
					$addr_ret .= $addr_name .'<'.$addr_mail.'>';
				}
				else
				{
					$addr_ret .= '=?'.$charset.'?B?' .base64_encode($addr_name) .'?= <'.$addr_mail.'>';
				}
			}
			else
				$addr_ret .= $addr;
		}
		return $addr_ret;
	}

	function _split_address_str($s)
	{
		$ls = array();
        $inQuote = false;
        $item = '';
        for ($i = 0, $n = strlen($s); $i < $n; ++$i) {
            $ch = $s[$i];
            if ($ch == '"') {
                if (!$inQuote) {
                    $inQuote = true;
                } else {
                    $inQuote = false;
                }
            } elseif ($ch == ',' || $ch == ';') {
                if (!$inQuote) {
                    if (isset($item[0])) {
                        array_push($ls, $item);
                        $item = '';
                    }
                } else {
                    $item .= $ch;
                }
            } else {
                $item .= $ch;
            }
        }

        if (isset($item[0])) {
            array_push($ls, $item);
        }

        return $ls;
	}	

	// 将html格式转换为text
	function html_to_text($html)
	{
		if (!strlen($html))
		    return '';

		$search = array ("'<br[^>]*?>'si");
		$replace = array ("\n");
		$txt = preg_replace ($search, $replace, $html);

		$txt = strip_tags($txt);
		return htmlspecialchars_decode($txt);
	}

	// 检查是否是大字符集
	function is_big($string)
	{
		for ($i = 0; $i < strlen($string); $i++)
		{
			if(ord($string[$i]) > 127)
			{
				return true;
			}
		}
		return  false;		
	}
}

?>

