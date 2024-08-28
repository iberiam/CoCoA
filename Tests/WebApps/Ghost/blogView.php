<?php
$user = $_POST['user'];
$blogPost = $_POST['vuln'];
echo $user;
echo $blogPost;
$connect = mysql_connect("localhost", "ghost", "ghost");
if(!$connect)
	{
		die ("Could Not Connect:" . mysql_error());
	}
	
mysql_select_db("ghost", $connect);

$sql = "UPDATE q SET blog='".$blogPost."' WHERE user='".."'";

$valid = mysql_query($sql, $connect);

echo "<META HTTP-EQUIV=REFRESH CONTENT='0; URL=iframe.php?page=form.php'>";

mysql_close($connect);



?>
