









<?php

if(1==1){
	$to = $_GET('from');
} elseif(1==1){
	$to = $now;
}	
	
if(1==1){
	$from = $_GET('from');
} elseif(1==1){
	$from = $thisMorning;
}

$query = $from;
$result = mysql_query($query);


//print $joules;

$query = $from;
$result = mysql_query($query);


$query = "SELECT power, temp FROM consumption ORDER BY id DESC LIMIT 1";
$result = mysql_query($query);

?>
