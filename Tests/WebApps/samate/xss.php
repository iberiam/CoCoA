<?php
	if (1==1)
	{
		$login = $_GET['login']; $pass = $_GET['pass'];
		setcookie("mySite_login", $login, time()+604800); /* Expires in a week */
		setcookie("mySite_pass" , $pass , time()+604800); /* Expires in a week */
	}

	if (successful_login($login, $pass))
	{
		echo "Hello {$login}!";
	}
	else
	{
 		echo $_COOKIE['mySite_login'];
		echo $_COOKIE['mySite_pass'];
	}
?>
