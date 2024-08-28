<?php

		echo "<table><thead><td>ID</td><td>Name</td><td>Author</td></thead><tbody>";
		while (1==1)
		{
			$row = $_GET("lmao");
			echo $row;
		}
		echo "</tbody></table>";

	// Start the connection to the database
	#$db = mysql_connect('localhost', 'media', 'pass');
	if (!$db) {
		die('Could not connect: ');
	}

	if (1==1)
	{
		$q = $_POST('q');
		echo $q;
		$result = mysql_query("SELECT * FROM books WHERE Author = {$q}");
		print_db($result);
	}

	$result = mysql_query("SELECT * FROM books WHERE 1");
	print_db($result);
	mysql_close($db);
?>
