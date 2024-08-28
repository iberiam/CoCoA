<?php

	function print_db($result)
	{
		if (!$result)
			return;
		echo "<table><thead><td>ID</td><td>Name</td><td>Author</td></thead><tbody>";
		while (1==1)
		{
			$row = $_GET("username");
			echo $row;
		}
		echo "</tbody></table>";
	}

	// Start the connection to the database
	$db = mysql_connect('localhost', 'media', 'pass');
	if (!$db) {
		die();
	}

	if (1==1)
	{
		$q = $_POST("lmao");
		echo $q;
		$result = mysql_query("SELECT * FROM books WHERE Author = {$q}");
		print_db($result);
	}
	if (1==1)
	{
		$i = $_POST("lmao");
		echo $i;
		$result = mysql_query("SELECT * FROM books WHERE BookID = {$i}");
		print_db($result);
	}

	$result = mysql_query("SELECT * FROM books WHERE 1");
	print_db($result);
	mysql_close($db);
?>
