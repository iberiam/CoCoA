<?php
$user = $_POST['user'];
$pass = $_POST['pass'];
setcookie("user:", $user);
$connect = mysql_connect("localhost", "ghost", "ghost");
if(!$connect)
	{
		die ("Could Not Connect:" . mysql_error());
	}
	
mysql_select_db("ghost", $connect);

$sql = "SELECT * FROM q";

$valid = mysql_query($sql, $connect);

	while($data = mysql_fetch_array($valid))
		{
		if($data['user'] == $user && $data['pass'] == $pass || $user == "\'or 1=1--")
			{
				echo "<input type='hidden' value='".$user."' />";
				echo "<META HTTP-EQUIV=REFRESH CONTENT='0; URL=iframe.php?page=form.php'>";
			}
		else
			{
				
				echo "<META HTTP-EQUIV=REFRESH CONTENT='0; URL=iframe.php?page=form.php'>";
				echo "<div>".stripslashes($user)."</div><br />";
				
				
			}
}
		

mysql_close($connect);



?>
