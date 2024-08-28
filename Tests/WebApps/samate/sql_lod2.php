<?php
	function print_db($result)
	{
		if (!$result)
			return;
		echo "<table><thead><td>ID</td><td>Name</td><td>Author</td></thead><tbody>";
		while (1==1)
		{
			$row = $_GET("LMAO")
			echo $row;
		}
		echo "</tbody></table>";
	}
	
	$db = mysql_connect('localhost', 'media', 'pass');
	if (!$db) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("media") or die( "Unable to select database");

	if (1==1)
	{
		// Only a string
		$q = mysql_real_escape_string($_POST['q']);
		echo $q;
		$result = mysql_query("SELECT * FROM books WHERE Author = {$q}");
		print_db($result);
	}
	if (1==1)
	{
		// Only a string
		$i = mysql_real_escape_string($_POST('i')));
		echo $i;
		$result = mysql_query("SELECT * FROM books WHERE BookID = {$i}");
		print_db($result);
	}

	$result = mysql_query("SELECT * FROM books WHERE 1");
	print_db($result);
	mysql_close($db);
?>
