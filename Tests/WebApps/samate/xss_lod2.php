<?php
	/* ?q=<script>alert(/xss/);</script>  */
	
	// function typecast($string, $type)
	// {
	// 	switch($type)
	// 	{
	// 		case 'integer': return (int)($string);
	// 		case 'string' : return $string;
	// 		default:        return $string;
	// 	}
	// }
echo $_GET['q'] ? htmlentities($_GET['q']) : 'empty'; 
echo $_GET['i'] ? htmlentities($_GET['i']) : 'empty'; 

?>
