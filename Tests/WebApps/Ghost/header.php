<?php
include 'config.inc';
include 'opendb.inc';

// Grab inputs
$username = $_REQUEST["user_name"];
$password = $_REQUEST["password"];
$dosomething = $_REQUEST["do"];

if ($username <> "" and $password <> "") {
	$query  = "SELECT * FROM accounts WHERE username='". $username ."' AND password='".stripslashes($password)."'";
	$result = mysql_query($query) or die('Did you <a href="setupreset.php">setup/reset the DB</a>? <p><b>SQL Error:</b>' . mysql_error($conn) . '<p><b>SQL Statement:</b>' . $query);
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_array($result, MYSQL_ASSOC);
		setcookie("uid", $row['cid']); 
		$failedloginflag=0;
		echo '<meta http-equiv="refresh" content="0;url=index.php">';
	} else {
		$failedloginflag=1;
	}
}

switch ($dosomething) {
	case "logout":
		setcookie('uid','',1);
		break;
	case "togglehints":
		if ($_COOKIE["showhints"] == 0) {
		setcookie('showhints','1');
		} else {
		setcookie('showhints','0');
		}		
		break;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
<?php
if ($dosomething  == "logout") {
	echo '<meta http-equiv="refresh" content="0;url=index.php">';
}
?>
  <meta content="text/html; charset=us-ascii" http-equiv="content-type">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" /> 
</head>
<body>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr><td bgcolor="#88ff88"align="center" colspan="2">
		<table width="100%">
		<td valign="top"><a href="index.php"><img border="0" align="top" src="images/coykillericon.png"></a><br>Version 1.3</td>
		<td align="center" valign="top"><h1><b>Mutillidae: Hack, Learn, Secure, Have Fun!!!</b></h1>
		<?php
		$query  = "SELECT * FROM accounts WHERE cid='".$_COOKIE["uid"]."'";
		$result = mysql_query($query) or die('Did you <a href="setupreset.php">setup/reset the DB</a>?');
		echo mysql_error($conn);
		echo mysql_error($conn);
		if (mysql_num_rows($result) > 0) {
			while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$logged_in_user = $row['username'];
				$logged_in_usersignature = $row['mysignature'];				
				echo '<blink><font color="#0000ff"><h2>You are logged in as ' . $logged_in_user . '</h2>' . $logged_in_usersignature . '</font></blink>';
			}
		} else {
			$logged_in_user = "anonymous";
			echo '<font color="#ff0000">Not logged in</font>';
		}
		?>
		</td>
		<!-- <td align="right" valign="top"><img src="http://irongeek.com/sigs/logo.php"></td> -->		
		</table>
	</td></tr>
	<tr>
		<td bgcolor="#88ff88" valign="top" width="12%">
		<hr>
		<hr>
		<b>Core Controls</a></b><br>
		<a href="index.php">Home</a><br>
		<a href="?page=register.php">Register</a><br>
		<a href="?page=login.php">Login</a><br>
		<a href="?do=logout">Logout</a><br>
		<a href="?do=togglehints">Toggle hints</a><br>
		<a href="setupreset.php">Setup/reset the DB</a><br>
		<a href="?page=show-log.php">Show log</a><br>
		<a href="?page=credits.htm">Credits</a><br>		
		<hr>
		<hr>
		<b><a href="http://www.owasp.org/index.php/Top_10_2007-A1" target="_blank">A1</a> - Cross Site Scripting (XSS)</a></b><p>
		<a href="?page=add-to-your-blog.php">Add to your blog</a><p>
		<a href="?page=view-someones-blog.php">View someone's blog</a><p>
		<a href="?page=browser-info.php">Browser info</a><p>
		<a href="?page=show-log.php">Show log</a><br>
		<hr>
		<b><a href="http://www.owasp.org/index.php/Top_10_2007-A2" target="_blank">A2</a> - Injection Flaws (SQL and Command)</b><p>
		<a href="?page=login.php">Login</a><p>
		<a href="?page=user-info.php">User info</a><p>
		<a href="?page=dns-lookup.php">DNS Lookup</a><p>
		<a href="?page=register.php">Register</a><p>		
		<hr>
		<b><a href="http://www.owasp.org/index.php/Top_10_2007-A3" target="_blank">A3</a> - Malicious File Execution</b><p>		
		<a href="?page=text-file-viewer.php">Text file viewer</a><p>
		<a href="?page=source-viewer.php">Source viewer</a><p>
		<hr>
		<b><a href="http://www.owasp.org/index.php/Top_10_2007-A4" target="_blank">A4</a> - Insecure Direct Object Reference</b><p>
		<a href="?page=text-file-viewer.php">Text file viewer</a><p>
		<a href="?page=source-viewer.php">Source viewer</a><p>
		<a href="index.php">Whole damn site</a><p>
		<hr>
		<b><a href="http://www.owasp.org/index.php/Top_10_2007-A5" target="_blank">A5</a> - Cross Site Request Forgery (CSRF)</b><p>
		<a href="?page=add-to-your-blog.php">Add to your blog</a><p>
		<a href="?page=view-someones-blog.php">View someone's blog</a><p>
		<a href="?page=show-log.php">Show log</a><p>
		<hr>
		<b><a href="http://www.owasp.org/index.php/Top_10_2007-A6" target="_blank">A6</a> - Information Leakage and Improper Error Handling</a></b><p>
		<a href="index.php">Whole damn site</a><br>
		<hr>
		<b><a href="http://www.owasp.org/index.php/Top_10_2007-A7" target="_blank">A7</a> - Broken Authentication and Session Management</a></b><p>
		<a href="index.php">Whole damn site</a><br>
		<a href="?page=login.php">Login</a><p>
		<a href="?page=user-info.php">User info</a><p>
		<hr>
		<b><a href="http://www.owasp.org/index.php/Top_10_2007-A8" target="_blank">A8</a> - Insecure Cryptographic Storage</a></b><p>
		<a href="?page=user-info.php">User info</a><p>
		<hr>
		<b><a href="http://www.owasp.org/index.php/Top_10_2007-A9" target="_blank">A9</a> - Insecure Communications</a></b><p>
		<a href="?page=login.php">Login</a><p>
		<a href="?page=user-info.php">User info</a><p>
		<hr>
		<b><a href="http://www.owasp.org/index.php/Top_10_2007-A10" target="_blank">A10</a> - Failure to Restrict URL Access</a></b><p>
		Well, they exist, but if I pointed you to them that would miss the point. How would you find directories someone would want to hide?<p>
		<hr>
		<hr>
		</td>
		<td  valign="top" width="80%">
		<blockquote>
		<!-- Begin Content -->
