<?PHP
// vim:cms=/*%s*/
/*
	ZiPEC Functions
	No modifications should be done in this file,
	unless you know what you're doing.

	As of now, this is more of a placeholder than anything
	allowing future features to be implemented
*/

// Init variables /*{{{*/
function init() {
	global $context,$page,$cfg,$mode,$show,$user_filter ;
	if(!isset($context)) { 			$context = ""; }
	if(!isset($_GET['context'])) { 		$_GET['context'] = Array(); }
	if(!isset($_GET['context'][""])) { 	$_GET['context'][""] = ""; }
	$context = $_GET['context'];
	if(!isset($cfg["context"]["$context"])) { $cfg["context"]["$context"] = ""; }
	$page = Array( 'content'=>"", "status"=>"", "toolbar"=>"");
	if(!isset($_GET['show'])) {	$_GET['show'] = ""; }
	if(!isset($_GET['mode'])) {	$_GET['mode'] = ""; }
	if(!isset($_GET['action'])) {	$_GET['action'] = ""; }
	if(!isset($_GET['evid'])) {	$_GET['evid'] = ""; }
	$show = $_GET['show'];
	$user_filter = NULL;
	$mode = $_GET['mode'];
	$action = $_GET['action'] ;
	$evid = $_GET['evid'];
}
/*}}}*/

// DB	/*{{{*/
// Opens a SQL connection to the DB server specified in include/config.php /*{{{*/
// in : N/A ; out : SQL resource
function DB_Connect() {
	global $cfg ;
	$c = @mysql_connect($cfg[db_host],$cfg[db_user],$cfg[db_pass]) ;
	return $c ;
}
/*}}}*/

// Closes a given SQL connection /*{{{*/
// in : SQL resource ; out : bool
function DB_Close($r) {
	$o = @mysql_close($r);
	return $o ;
}
/*}}}*/
/*}}}*/

// Special functions
function datediff($timestamp) {
	global $msg ;
	$fullDays = floor($timestamp/(60*60*24));
	$fullHours = sprintf('%02d',floor(($timestamp-($fullDays*60*60*24))/(60*60)));
	$fullMinutes = sprintf('%02d',floor(($timestamp-($fullDays*60*60*24)-($fullHours*60*60))/60));
	return "$fullDays $msg[days] ".$fullHours.$msg[hours].$fullMinutes; 	
}


?>
