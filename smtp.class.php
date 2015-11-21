<?php

require_once('mime.class.php');

set_time_limit(0);
class smtp
{

	// Global Variables
	var $host_name;
	var $smtp_port;
	var $time_out;
	var $relay_host;
	var $auth;
	var $sasl_user;
	var $sasl_passwd;
	var $mail_type;
	var $log_file;
	var $debug;
	var $logs;
	var $qid;
	var $err;

	// Private Varibales
	var $sock;
	var $mime;

	// Constrator
	function smtp($relay_host="", $smtp_port=25, $auth=false, $sasl_user, $sasl_passwd)
	{
		$this->debug = FALSE;
		$this->smtp_port = $smtp_port;
		$this->relay_host = $relay_host;
		$this->time_out = 30;	// is used in fsockopen()

		$this->auth = $auth;
		$this->sasl_user = $sasl_user;	
		$this->sasl_passwd = $sasl_passwd;

		$this->host_name = "localhost";
		$this->log_file = "";
		$this->logs = "";
		$this->qid = "";
		$this->err = array();

		$this->sock = FALSE;
		$this->mime = new mime_mail();
	}

	// Set Mail Content-Type
	function set_type($type)
	{
		$this->mail_type = $type;
	}

	// Main Function
	function sendmail($from, $f_nick, $to, $subject="", $body="", $mail_type, $cc="", $bcc="", $additional_headers="", $attachments)
	{
		$sent = TRUE;

		$this->mime->set_sender($from);	
		$this->mime->set_from($from, $f_nick);

		$this->mime->set_to($to);		
		$this->mime->set_cc($cc);
		$this->mime->set_bcc($bcc);
	
		$this->mime->set_subject($subject);	
		$this->mime->set_priority(3);
		$this->mime->set_notification_to(false);	
		
		$html = "";
		if (strcasecmp($this->mail_type, "html") == 0)
		{
			$this->mime->set_body_type('html');
			$html = $body;
			$this->mime->add_text_body($this->mime->html_to_text($body));
		}
		else
		{
			$this->mime->set_body_type('plain');
			$this->mime->add_text_body($body);
		}

		$this->mime->add_html_body($html);	

/*
		if (!empty($attachments)) 
		{
			foreach ($attachments as $file)
			{
				if ($file['error'] == UPLOAD_ERR_NO_FILE) {
        	    	continue;
        		} elseif ($file['error'] == UPLOAD_ERR_PARTIAL) {
            		return MIMEMAIL_ATTACHMENT_PARTIAL;
        		} elseif ($file['error'] == UPLOAD_ERR_INI_SIZE ||
            		$file['error'] == UPLOAD_ERR_FORM_SIZE) {
            		return MIMEMAIL_ATT_OVERSIZE;
        		} elseif ($file['error'] == UPLOAD_ERR_OK && $file['size'] == 0) {
            		return MIMEMAIL_ATTACHMENT_EMPTY;
        		}

				$this->mime->add_attachment($file['tmp_name'], $file['name'], $file['type']);
			}

		}
*/
		foreach($attachments as $attch_file) {
			if (file_exists($attch_file)) {
				$attach_type = $this->mime_content_type($attch_file);
				$this->mime->add_attachment($attch_file, basename($attch_file), $attach_type, false);
			}
		}

		$this->mime->_build_body();

		$TO = explode(",", $this->strip_comment($to));
		if ($cc != "") 
			$TO = array_merge($TO, explode(",", $this->strip_comment($cc)));

		if ($bcc != "") 
			$TO = array_merge($TO, explode(",", $this->strip_comment($bcc)));

		if ($this->smtp_send($this->host_name, $from, $TO, $this->mime->_mail_header, $this->mime->_mail_body))
        {
            $this->log_write("E-mail has been sent to <".$rcpt_to.">\n");
        }
        else
        {
            $this->log_write("Error: Cannot send email to <".$rcpt_to.">\n");
            $sent = FALSE;
        }

/*
		foreach ($TO as $rcpt_to)
		{
			$rcpt_to = $this->get_address($rcpt_to);
			if (!$this->smtp_sockopen($rcpt_to))
			{
				$this->log_write("Error: Cannot send email to ".$rcpt_to."\n");
				$sent = FALSE;
				continue;
			}

			if ($this->smtp_send($this->host_name, $mail_from, $rcpt_to, $this->mime->_mail_header, $this->mime->_mail_body))
			{
				$this->log_write("E-mail has been sent to <".$rcpt_to.">\n");
			}
			else
			{
				$this->log_write("Error: Cannot send email to <".$rcpt_to.">\n");
				$sent = FALSE;
			}

			fclose($this->sock);
			$this->log_write("Disconnected from remote host\n");
		}
*/

		return $sent;
	}

	// Private Function
	function smtp_send($helo, $from, $to, $header, $body="")
	{
		if (!$this->smtp_sockopen())
    	{
        	$this->log_write("Error: Cannot send email to ".$rcpt_to."\n");
        	return $this->smtp_error("connect fail");
    	}
	
		if (!$this->smtp_putcmd("HELO", $helo)) 
		{
			return $this->smtp_error("sending HELO command");
		}

		if ($this->auth) 
		{
			if (!$this->smtp_putcmd("AUTH LOGIN", base64_encode($this->sasl_user)))
			{
				return $this->smtp_error("sending AUTH command");
			}
			if (!$this->smtp_putcmd("", base64_encode($this->sasl_passwd)))
			{
				return $this->smtp_error("sending AUTH command");
			}
		}	

		if (!$this->smtp_putcmd("MAIL", "FROM:<". $from .">")) 
		{
			return $this->smtp_error("sending MAIL FROM command");
		}

		foreach ($to as $rcpt_to)
		{
			$rcpt_to = $this->get_address($rcpt_to);
			if (!$this->smtp_putcmd("RCPT", "TO:<". $rcpt_to .">"))
			{
				return $this->smtp_error("sending RCPT TO command");
			}
		}

		if (!$this->smtp_putcmd("DATA"))
		{
			return $this->smtp_error("sending DATA command");
		}

		if (!$this->smtp_message($header, $body))
		{
			return $this->smtp_error("sending message");
		}

		if (!$this->smtp_eom())
		{
			return $this->smtp_error("sending <CR><LF>.<CR><LF>");
		}

		if (!$this->smtp_putcmd("QUIT"))
		{
			return $this->smtp_error("sending QUIT command");
		}

		return TRUE;

	}	

	function smtp_sockopen($address = '')
	{
		if ($this->relay_host == "")
		{
			return $this->smtp_sockopen_mx($address);
		}
		else
		{
			return $this->smtp_sockopen_relay();
		}
	}

	function smtp_sockopen_relay()
	{
		$this->log_write("Trying to ". $this->relay_host .":". $this->smtp_port ."\n");
		
		$this->sock = @fsockopen($this->relay_host, $this->smtp_port, $errno, $errstr,
								$this->time_out);
		if (!($this->sock && $this->smtp_ok()))
		{
			$this->log_write("Error: Cannot connect to relay host ". $this->relay_host ."\n");
			$this->log_write("Error: ".$errstr."(".$errno.")\n");		
			return FALSE;
		}

		$this->log_write("Connected to relay host ". $this->relay_host ."\n");
		return TRUE;
	}

	function smtp_sockopen_mx($address)
	{
		$domain = ereg_replace("^.+@([^@]+)$", "\1", $address);
		if (!@getmxrr($domain, $MXHOSTS))
		{
			$this->log_write("Error: Cannot resolve MX \"". $domain ."\"\n");
			return FALSE;	
		}

		foreach($MXHOSTS as $host)
		{
			$this->log_write("Trying to ".$host.":".$this->smtp_port."\n");
			$this->sock = @fsockopen($host, $this->smtp_port, $errno, $errstr, $this->time_out);
			if (!($this->sock && $this->smtp_ok()))
			{
				$this->log_write("Warning: Cannot connect to mx host". $host ."\n");
				$this->log_write("Error: ". $errstr ."(". $errno .")\n");
				continue;
			}

			$this->log_write("Connected to mx host". $host ."\n");
			return TRUE;
		}

		$this->log_write("Error: Cannot connect to any mx hosts (". implode(",", $MXHOSTS) .")\n");
		return FALSE;
	}
	
	function smtp_message($header, $body)
	{
		fputs($this->sock, $header."\r\n\r\n".$body);
		$this->smtp_debug("> ".str_replace("\r\n", "\n"."> ", $header."\n> ".$body."\n>"));
		return TRUE;
	}

	function smtp_eom()
	{
		fputs($this->sock, "\r\n.\r\n");
		$this->smtp_debug(".\n");

		//return $this->smtp_ok(&$this->qid);
		return $this->smtp_ok($this->qid);
	}

	//function smtp_ok($res)
	function smtp_ok()
	{
		$response = fgets($this->sock, 512);
		$response = str_replace("\r\n", "", $response);
		$this->smtp_debug($response."\n");

		$res = $response;
		if (!ereg("^", $response) || $response[0] == '4' || $response[0] == '5')
		{
			$this->err[] = $response;

			fputs($this->sock, "QUIT\r\n");
			fgets($this->sock, 512);
			$this->log_write("Error: Remote host returned \"". $response ."\"\n");
			return FALSE;
		}

		return TRUE;
	}

	function smtp_putcmd($cmd, $arg="")
	{
		if ($arg != "")
		{
			if ($cmd == "")	
				$cmd = $arg;
			else 
				$cmd = $cmd." ".$arg;
		}

		fputs($this->sock, $cmd."\r\n");
		$this->smtp_debug("> ". $cmd ."\n");
		return $this->smtp_ok();
	}

	function smtp_error($string)
	{
		$this->log_write("Error: Error occurred while ". $string .".\n");
		return FALSE;
	}

	function log_write($message)
	{
		$this->smtp_debug($message);
		if ($this->log_file == "")
		{
			return TRUE;
		}

		$message = date("M d H:i:s ").get_current_user().": ".$message;
		if (!@file_exists($this->log_file) || !($fp = @fopen($this->log_file, "a")))
		{
			$this->smtp_debug("Warning: Cannot open log file \"". $this->log_file ."\"\n");
			return FALSE;
		}

		flock($fp, LOCK_EX);
		fputs($fp, $message);
		fclose($fp);

		return TRUE;
	}

	function strip_comment($address)
	{
		$comment = "\([^()]*\)";	
		while (ereg($comment, $address))
		{
			$address = ereg_replace($comment, "", $address);
		}

		return $address;
	}

	function get_address($address)
	{
		$address = ereg_replace("([ \t\r\n])+", "", $address);
		$address = ereg_replace("^.*<(.+)>.*$", "\1", $address);
		return $address;
	}

	function smtp_debug($message)
	{
		if ($this->debug)	
			echo $message;
	}

    function mime_content_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }

}

?>


