<?PHP
// vim:cms=/*%s*/
/*
	ZiPEC Config File
	See README.txt for instructions
*/

// Default parameters don't need to be edited if ZiPEC runs on the same machine as Zenoss
$cfg = Array(	
	// Zenoss Database Parameters
		"db_host" =>	"127.0.0.1:3307",	// Hostname/IP and TCP port of the Zenoss MySQL service
		"db_base" =>	"events",		// Zenoss Events MySQL Database
		"db_tabl" =>	"status",		// Zenoss events MySQL table name
		"db_user" =>	"zenoss",		// Zenoss MySQL user
		"db_pass" =>	"zenoss",		// Zenoss MySQL password

	// Event Filtering - Only displays events that match this filter (SQL syntax - see README.txt for examples)
		"ev_filt" =>	"status.prodState=1000 and status.severity>1 and status.eventstate!=2",

	// Contexts
		"use_con" =>	TRUE,			// if no context is used, set to FALSE - if set to TRUE
		"context" =>	Array(),		// See section named 'Contexts' at the bottom

	// Event Ordering - SQL 'Order by' syntax applies
		"ev_ordr" =>	"severity DESC,lasttime DESC",

	// Allow non iphone connections
		"restrct" =>	FALSE,			// TRUE or FALSE : allows non-iphone browser agent to connect to this service
	
	// UI Parameters
		"ui_skin" =>	"default",		// Layout Skin (See README.txt)
		"ui_lang" =>	"en_US",		// Available Locales : en_US, fr_FR
		"ui_refr" =>	"60" 			// Page Refresh Time in Seconds
);

// Contexts 
if ($cfg["use_con"]) { // This flag can be set at line 21, to activate filters
	// Context Example
		$cfg['context']['example'] =		TRUE ; 				// Set to FALSE to disable this filter
		$cfg['context']['example_label'] =	"Example Systems" ;		// Label for this filter
		$cfg['context']['example_filter'] = 	"systems like '%MySystems%'" ;	// SQL Clause : edit as needed
	// Context Another Example
		$cfg['context']['dummy'] = 		TRUE ;
		$cfg['context']['dummy_label'] =	"Example Location";
		$cfg['context']['dummy_filter'] =	"systems like '%MyLocation%'";
}

?>
