<?php

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

				echo "<div><p>".$data['user']." wrote: ".stripslashes($data['blog'])."</p></div>";
				
}
		

mysql_close($connect);



?>