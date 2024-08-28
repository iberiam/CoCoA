<?php 
	/* 
		?q=<script>alert(/xss/);</script>  
		?i=42<script>alert(/xss/);</script>
	*/
	// function typecast($string, $type)
	// {
	// 	switch($type)
	// 	{
	// 		case 'integer': return (int)($string);
	// 		case 'string' : return $string;
	// 		default:        return $string;
	// 	}
	// }
 echo $_GET['q'] ? $_GET['q'] : 'empty'; 
 echo $_GET['i'] ? $_GET['i'] : 'empty'; 
 
?>
