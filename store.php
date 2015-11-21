<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>HuaHua Store</title>

<style type="text/css">

/*
Im reseting this style for optimal view using Eric Meyer's CSS Reset - http://meyerweb.com/eric/tools/css/reset/
------------------------------------------------------------------ */
body, html  { height: 100%; }
html, body, div, span, applet, object, iframe,
/*h1, h2, h3, h4, h5, h6,*/ p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, font, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td {
	margin: 0;
	padding: 0;
	border: 0;
	outline: 0;
	font-size: 100%;
	vertical-align: baseline;
	background: transparent;
}
body { line-height: 1; }
ol, ul { list-style: none; }
blockquote, q { quotes: none; }
blockquote:before, blockquote:after, q:before, q:after { content: ''; content: none; }
:focus { outline: 0; }
del { text-decoration: line-through; }
table {border-spacing: 0; } /* IMPORTANT, I REMOVED border-collapse: collapse; FROM THIS LINE IN ORDER TO MAKE THE OUTER BORDER RADIUS WORK */

/*------------------------------------------------------------------ */

/*This is not important*/
body{
	font-family:Arial, Helvetica, sans-serif;
	background: url(img/background.jpg);
	margin:0 auto;
	width:1024px;
}
a:link {
	color: #666;
	font-weight: bold;
	text-decoration:none;
}
a:visited {
	color: #666;
	font-weight:bold;
	text-decoration:none;
}
a:active,
a:hover {
	color: #bd5a35;
	text-decoration:underline;
}


/*
Table Style - This is what you want
------------------------------------------------------------------ */
table a:link {
	color: #666;
	font-weight: bold;
	text-decoration:none;
}
table a:visited {
	color: #999999;
	font-weight:bold;
	text-decoration:none;
}
table a:active,
table a:hover {
	color: #bd5a35;
	text-decoration:underline;
}
table {
	font-family:Arial, Helvetica, sans-serif;
	color:#666;
	font-size:12px;
	text-shadow: 1px 1px 0px #fff;
	background:#eaebec;
	margin:20px;
	border:#ccc 1px solid;

	-moz-border-radius:3px;
	-webkit-border-radius:3px;
	border-radius:3px;

	-moz-box-shadow: 0 1px 2px #d1d1d1;
	-webkit-box-shadow: 0 1px 2px #d1d1d1;
	box-shadow: 0 1px 2px #d1d1d1;
}
table th {
	padding:21px 25px 22px 25px;
	border-top:1px solid #fafafa;
	border-bottom:1px solid #e0e0e0;

	background: #ededed;
	background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#ebebeb));
	background: -moz-linear-gradient(top,  #ededed,  #ebebeb);
}
table th:first-child{
	text-align: left;
	padding-left:20px;
}
table tr:first-child th:first-child{
	-moz-border-radius-topleft:3px;
	-webkit-border-top-left-radius:3px;
	border-top-left-radius:3px;
}
table tr:first-child th:last-child{
	-moz-border-radius-topright:3px;
	-webkit-border-top-right-radius:3px;
	border-top-right-radius:3px;
}
table tr{
	text-align: center;
	padding-left:20px;
}
table tr td:first-child{
	text-align: left;
	padding-left:20px;
	border-left: 0;
}
table tr td {
	padding:18px;
	border-top: 1px solid #ffffff;
	border-bottom:1px solid #e0e0e0;
	border-left: 1px solid #e0e0e0;
	
	background: #fafafa;
	background: -webkit-gradient(linear, left top, left bottom, from(#fbfbfb), to(#fafafa));
	background: -moz-linear-gradient(top,  #fbfbfb,  #fafafa);
}
table tr.even td{
	background: #f6f6f6;
	background: -webkit-gradient(linear, left top, left bottom, from(#f8f8f8), to(#f6f6f6));
	background: -moz-linear-gradient(top,  #f8f8f8,  #f6f6f6);
}
table tr:last-child td{
	border-bottom:0;
}
table tr:last-child td:first-child{
	-moz-border-radius-bottomleft:3px;
	-webkit-border-bottom-left-radius:3px;
	border-bottom-left-radius:3px;
}
table tr:last-child td:last-child{
	-moz-border-radius-bottomright:3px;
	-webkit-border-bottom-right-radius:3px;
	border-bottom-right-radius:3px;
}
table tr:hover td{
	background: #f2f2f2;
	background: -webkit-gradient(linear, left top, left bottom, from(#f2f2f2), to(#f0f0f0));
	background: -moz-linear-gradient(top,  #f2f2f2,  #f0f0f0);	
}

</style>

</head>

<body>

<form id="sub_frm" action="./sendmail.php" method="get"> 
<table cellspacing='0'> <!-- cellspacing='0' is important, must stay -->
	<thead>
	<tr>
		<th><input type="checkbox" id="all_cb" name="all_cb" onclick="all_checked()" /></th>
		<th>Upload Date</th>
		<th>File Name</th>
		<th>File Size</th>
		<th>Action</th>
		<th>Send Email</th>
	</tr>
	</thead>
	<!-- Table Header -->
    
	<!--tr><td>Create pretty table design</td><td>100%</td><td>Yes</td></tr>
	<tr class='even'><td>Take the dog for a walk</td><td>100%</td><td>Yes</td></tr>

	<tr><td>Waste half the day on Twitter</td><td>20%</td><td>No</td></tr>
	<tr class='even'><td>Feel inferior after viewing Dribble</td><td>80%</td><td>No</td></tr>
	
    <tr><td>Wince at "to do" list</td><td>100%</td><td>Yes</td></tr>
	<tr class='even'><td>Vow to complete personal project</td><td>23%</td><td>yes</td></tr>

	<tr><td>Procrastinate</td><td>80%</td><td>No</td></tr>
    <tr class='even'><td><a href="#yep-iit-doesnt-exist">Hyperlink Example</a></td><td>80%</td><td><a href="#inexistent-id">Another</a></td></tr-->

	<tbody>
<?php

function show_dir($file_dir, $host) {
	$tbl_body = "";
	$i = 0;

    $dir_hd = @opendir($file_dir) or die("can't open:{$file_dir}");
    while (($file = readdir($dir_hd)) !== false) {
        if (is_dir($file)) {
            // dir
        } else {
            //echo "<a href='{$host}/{$file}'>". basename($file) ."</a>". "    ".filesize($file)."KB</br>";

        	$file = $file_dir ."/". $file;
			$urlenc_file = urlencode($file);
			$file_url = $host."/". $file;


			if ($i % 2 == 0) {
				//$tbl_body .= "<tr><td><a href='{$file_url}' target='_blank'>".basename($file)."</a></td><td>".filesize($file)."KB</td><td><a href='javascript:void(0);' onclick='del_action(\"{$file}\");'>Delete</a></td></td><td><a href='./sendmail.php?file={$urlenc_file}' target='_blank'>Send Email</a></td>";

				$tbl_body .= "<tr>";
				//$tbl_body .= "<td><a href='{$file_url}' target='_blank'>".basename($file)."</a></td><td>".filesize($file)."KB</td><td><a href='javascript:void(0);' onclick='del_action(\"{$file}\");'>Delete</a></td></td><td><a href='./sendmail.php?file={$urlenc_file}' target='_blank'>Send Email</a></td>";


			} else {
				//$tbl_body .= "<tr class='even'><td><a href='{$file_url}' target='_blank'>".basename($file)."</a></td><td>".filesize($file)."KB</td><td><a href=''>Delete</a></td></td><td><a href='./sendmail.php?file={$urlenc_file}' target='_blank'>Send Email</a></td>";
				$tbl_body .= "<tr class='even'>";
				//$tbl_body .= "<td><a href='{$file_url}' target='_blank'>".basename($file)."</a></td><td>".filesize($file)."KB</td><td><a href=''>Delete</a></td></td><td><a href='./sendmail.php?file={$urlenc_file}' target='_blank'>Send Email</a></td>";
			}

			$file_inode = fileinode($file);

			$tbl_body .= "<td><input type='checkbox' name='file_cb[]' value='{$file_inode}' onclick='file_checked(this)' /></td>";
			$tbl_body .= "<td>".date("Y-m-d H:i:s", filectime($file))."</td>";
			$tbl_body .= "<td><a href='{$file_url}' target='_blank'>".basename($file)."</a></td>";
			$tbl_body .= "<td>".filesize($file)."KB</td>";
			$tbl_body .= "<td><a href='javascript:void(0);' onclick='del_action(\"{$file}\");'>Delete</a></td>";
			//$tbl_body .= "<td><a href='./sendmail.php?file={$urlenc_file}' target='_blank'>Send Email</a></td>";
			$tbl_body .= "<td><a href='javascript:void(0);' onclick='send_action(\"{$file_inode}\");' target='_blank'>Send Email</a></td>";
			$tbl_body .= "</tr>";

        }   
		$i++;
    }   

	echo $tbl_body;
}

$host = "http://".$_SERVER['SERVER_NAME']."/huahua/";
show_dir("store/", $host);


?>
	</tbody>

	<tfoot>
	</tfoot>

</table>
&nbsp;&nbsp;<input type="button" id="sendsel_btn" name="sendsel_btn" value="Send" onclick="sendsel_action()" />&nbsp;&nbsp;&nbsp;&nbsp;
<!--input type="button" id="deletesel_btn" name="deletesel_btn" value="Delete" onclick="deletesel_action()" /-->&nbsp;&nbsp;&nbsp;&nbsp;

</form>

<script src="js/jquery.js"></script>
<script src="js/jquery.blockUI.js"></script>
<script>

function sendsel_action()
{
	var checked_cb_objs = document.getElementsByName("file_cb[]");
	for (var i=0; i<checked_cb_objs.length; i++) {
		if (checked_cb_objs[i].checked == true) {
			$("#sub_frm")[0].submit();
			return;
		}
	}
}

function all_checked()
{
	if ($("#all_cb")[0].checked == true) {
		$("input[name='file_cb[]']").attr("checked", "true");
	} else {
		$("input[name='file_cb[]']").removeAttr("checked");
	}
	//$("input[name='file_cb']").each(function(){
		
	//});
}

function file_checked(self)
{
	if (self.checked == false) {
		$("#all_cb")[0].checked = false;
	} else {
		$("#all_cb")[0].checked = true;
		$("input[name='file_cb[]']").each(function(){
			if ($(this).attr("checked") != "checked") {
				$("#all_cb")[0].checked = false;
				return;
			}
		});
	}
}

function send_action(inode)
{
	$("input[name='file_cb[]']").each(function(){
		if ($(this).val() == inode) {
			$(this).attr("checked", "true");

			$("#sub_frm")[0].submit();
		}
	});
}

function del_action(file)
{
	var answer = confirm("你确定要删除该文件吗? "+ file);
	if (!answer) { 
		return 0;
	}

	$.ajax({
		type: "post",
		url: "./action.php",
		data: "type=del&file="+file,
		beforeSend: function(XMLHttpRequest) {
			 $.blockUI({ message: '<h1><img src="img/busy.gif" /> 请等待，正在处理...</h1>' });
		},
		error: function() { 
			$(document).ajaxStop($.unblockUI); 
			alert('删除失败!!');
		},
		success: function(data, textStatus) { 
			$(document).ajaxStop($.unblockUI);

			var res_obj = eval("("+ data +")");
			if (res_obj == 0) {
				alert("删除成功");
				window.location.href = './store.php';
			} else {
				alert("删除失败");
			}
		},
		complete: function(XMLHttpRequest, textStatus) { 
			$(document).ajaxStop($.unblockUI); 
		}	
	});
}
</script>

</body>
</html>



