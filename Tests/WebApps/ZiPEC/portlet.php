<?PHP

require('include/portlet_config.php');

if (mysql_connect("$db_host",$db_user,$db_pass)) {
	mysql_select_db($dbtable);
	$result = mysql_query($query);

	while ( $row = mysql_fetch_array($result) ) {
		if ($row[ownerid]) { $ack = "acked"; } else { $ack = "unacked"; }
		$url = "$server/zport/dmd/Devices$row[DeviceClass]/devices/$row[device]";
		$line .= "<tr><td class='sev$row[severity] $ack' style='width: 10%' nowrap><a href='$url' target='_parent'>$row[device]</a></td><td class='sev$row[severity] $ack'><a href='$url/viewEvents' target='_parent'>$row[summary]</a></td><td>$row[count]</td></tr>\n"; 
	}
	if (@mysql_num_rows($result) == 0) {
		$okpage = "<body class='allSystemsOk'>No alerts</body>";
	}
	mysql_free_result($result);
	mysql_close();
} else {
	$okpage = "<body class='Error'>Database is unavailable</body>" ;
}
$output = $line ;
?>
<html>
<head>
<meta HTTP-EQUIV="Refresh" CONTENT="60; URL=portlet.php"/>
<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=ISO-8859-1"/>

<style type='text/css'>
* {
	margin: 0px;
	padding: 0px;
}
body { 
	background-color: #FCFCFF;
	padding: 0px;
}
a { text-decoration: none; }
table#alerts { width: 100%; border-top:  1px solid #DDF; }
#alerts { 
	font-family: Tahoma, Calibri, Sans-Serif;
	font-size: 10pt;
	font-weight: normal;
}
table#alerts tr:nth-child(2n+1) {
	background-color: #F9F9FF;
}
#alerts th {
	height: 23px;
	border-bottom: 1px solid #7F7F7F;
	font-weight: normal;
	text-align: left;
	padding-right: 10px; padding-left: 10px;

}
#alerts td { 
	border-bottom: 1px solid #DDF; 
	padding: 3px;
	padding-right: 10px; padding-left: 10px;
}
.sev5, .sev5 a { color: red; }
.sev4, .sev4 a { color: #FF9900; }
.sev3, .sev3 a { color: #CCCC00; }
.unacked { font-weight: bold;}
.allSystemsOk { 
	padding-top: 30px;
	font-family: "Monaco","Lucida Console","Sans-Serif";
	text-align: center;
	font-size: 20pt;
	font-weight: bold;
	color: #0c0 ;
	background-color: #9f9; 
}
.Error {
	padding-top: 30px;
	font-family: "Monaco","Lucida Console","Sans-Serif";
	text-align: center;
	font-size: 20pt;
	font-weight: bold;
	color: #c00 ;
	background-color: #f99; 
}

</style>
</head>
<body>
<?PHP
if ($okpage) {
	echo $okpage ;
	exit ;
}
?>
<table id="alerts" border="0" cellspacing="0" cellpadding="0">
<tbody>
<tr><th>Device</th><th>Message</th><th>Count</th></tr>
<?PHP echo $line;?>
</tbody>
</table>
</body>
</html>
