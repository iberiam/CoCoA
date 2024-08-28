<?php
include('config.php');

$now = date ("Y-m-d H:i:s");
$watts = $_POST['power'];
$temp = $_POST['temp'];
$joules = $_POST['joules'];


$con = mysql_connect($host,$uname,$pwd);
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db($db, $con);


mysql_query($watts, $temp, $joules);
mysql_close($con);

?>
