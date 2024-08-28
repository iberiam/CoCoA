<?PHP

error_reporting(''); // Set to E_ALL to see debugging message

// MySQL settings
// Adjust to your context
$db_host = 	":/usr/local/zenoss/mysql/tmp/mysql.sock"; // Zenoss Event Database
//
$db_user = 	"zenoss";
$db_pass = 	"zenoss";
$db_port = 	"3307"; // unused
$dbtable =	"events";

// Zenoss URL (used in links)
$server = 	"http://zenoss:8080"; // http://yourzenoss.domain.tld:8080

// SQL query used to list events
$query = "select device,summary,severity,ownerid,DeviceClass,count from status where prodState=1000 and severity >=4 and eventState=2 order by severity desc";
?>
