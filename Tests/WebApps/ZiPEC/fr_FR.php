<?PHP
// vim:cms=/*%s*/
/*
	iPEC French Language File
	See README.txt for instructions
*/

// Format de Date : voir http://dev.mysql.com/doc/refman/5.0/en/date-and-time-functions.html#function_date-format
$date_format = "%d/%m/%y %H:%i"; // Standard  Month-Day-Year Hours:Minutes format for France

$msg = Array(
	// Page Titles
		"Title" =>		"ZiPEC",
		"Error" =>		"Erreur",
	
	// Event Information
		"no_ev" =>		"Aucune Alarme",
		"ackby" =>		"Acquitté par",
		"noack" =>		"Non Acquitté",
		"count" =>		"Nbre",
		"event" =>		"Évènements", 		// Used in status bar : 'X Events Listed'
		"component" =>		"Composant",
		"message" =>		"Message",
		"first" =>		"Début",
		"last" =>		"Dernière màj",
		"diff" =>		"Durée",
		"location" =>		"Situation",
		"days" =>		"Jours",
		"hours" =>		"h",

	// Error Messages
		"err_db" =>		"Erreur de connexion à la base de données.",
		"err_tb" =>		"La base '$cfg[db_base]' ne peut pas être sélectionnée.",
		"undefined" =>		"Non défini",
		"dummy" =>		"Dummy message"
);

