<?php

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link type='text/css' rel='stylesheet' href='stylesheet/styles.css' />
</head>
<body style='background-color:#3a3d32'>
<!--Attn Developers: Username: test Password: 1234  please remove when development is complete-->
<center><img src="images/logo.jpg" width='80%' height='175px' style='padding-top:0px;' /><br /><a href='index.php'>XSS & CSRF</a><a href='iframe.php?page=form.php'>IFrame Injection</a><a href='iframe.php?page=form.php'>RFI & LFI</a><a href='flash.php'>Flash</a><a href='code.php'>Code Injection & Cookies</a><a href='walk.php'>Tutorials & Walkthroughs</a><br /><br />
<table width="10%" height="100%" align="left" cellpadding='0'><tr><td>
<a class='link' onclick="javascript:document.getElementById('loadCon').src = 'guides/xss.html';">Xss</a><br />
<a class='link' onclick="javascript:document.getElementById('loadCon').src = 'guides/cookie.html';">Code Injection</a><br />
<a class='link' onclick="javascript:document.getElementById('loadCon').src = 'guides/rfi.html';">RFI & LFI</a><br />
<a class='link' onclick="javascript:document.getElementById('loadCon').src = 'guides/bug.html';">Browser Bugs</a><br />
<a class='link' onclick="javascript:document.getElementById('loadCon').src = 'guides/iframe.html';">Iframe Injection</a><br />
<a class='link' onclick="javascript:document.getElementById('loadCon').src = 'guides/flash.html';">Flash</a><br />
<a class='link' onclick="javascript:document.getElementById('loadCon').src = 'guides/fb.html';">Forced Browsing</a><br />
<a class='link' onclick="javascript:document.getElementById('loadCon').src = 'guides/csrf.html';">CSRF</a><br />
<a class='link' onclick="javascript:document.getElementById('loadCon').src = 'guides/ref.html';">Tools/References</a><br />
</td></tr></table>
<table width='90%' height="400px" align="right"><tr><td>
<iframe id='loadCon' src="guides/intro.html" width='90%' height="400px"></iframe>
<center><p>Developed By: <a href="http://www.webdevelopmentsolutions.org">Gh0$7</a></p></center>
</td></tr></table>
</body>
</html>