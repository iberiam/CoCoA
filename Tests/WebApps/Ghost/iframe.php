<?php
$user = $_COOKIE['user:'];
$file =$_GET['page']; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link type='text/css' rel='stylesheet' href='stylesheet/styles.css' />
<script language="javascript">
function showHint()
{
document.getElementById('hint').style.visibility = "visible";	
}
</script>
</head>
<body style='background-color:#3a3d32'>
<center><img src="images/logo.jpg" width='80%' height='175px' style='padding-top:0px;' /><br /><a href='index.php'>XSS & CSRF</a><a href='iframe.php?page=form.php'>IFrame Injection</a><a href='iframe.php?page=form.php'>RFI & LFI</a><a href='flash.php'>Flash</a><a href='code.php'>Code Injection & Cookies</a><a href="walk.php">Tutorials & Walkthroughs</a><br /><br /></center>
<?php
include('blog.php');
include($file);
?>
<center><p>Developed By: <a href="http://www.webdevelopmentsolutions.org">Gh0$7</a></p></center>
</body>
</html>