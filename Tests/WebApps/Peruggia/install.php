<?php

/*
 * This file is part of Peruggia.
 *
 * Peruggia is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 3 of the License, or (at your option) any later
 * version.
 *
 * Peruggia is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Peruggia; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */
?>

<html>
<head>
<title>Peruggia Installer</title>
<link rel=stylesheet href=style.css>
</head>
<body>
<br>
<table align=center>
<tr>
<td valign=top>
<fieldset style=width:300;>
<legend><b>Setup</b></legend>
<b>

<?php

include("conf.php");

$app_user = "admin";
$app_pass = md5("password");

$conx = mysql_connect($mysql_host, $mysql_user, $mysql_pass);

if(!$conx){
  echo "<font color=red>[-] Connect to MySQL</font><br>";
  echo mysql_error();
  $error = 1;
}else{
  echo "<font color=green>[+] Connect to MySQL</font><br>";
}

if(!mysql_select_db($mysql_db, $conx)){
  if(!mysql_query("CREATE DATABASE $mysql_db")){
    echo "<font color=red>[-] Create database</font><br>";
    echo mysql_error();
    $error = 1;
  }else{
    echo "<font color=green>[+] Create database</font><br>";
    mysql_select_db($mysql_db, $conx);
  }
}else{
  echo "<font color=green>[+] Create database (exists)</font><br>";
  mysql_select_db($mysql_db, $conx);
}

$create_table_users = mysql_query("
CREATE TABLE users (
ID MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
username VARCHAR(60), 
password VARCHAR(60)
) 
", $conx);

$create_table_picdata = mysql_query("
CREATE TABLE picdata (
ID MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
pic VARCHAR(60), 
comments VARCHAR(1000), 
uploader VARCHAR(1000)
) 
", $conx);

if(!mysql_num_rows(mysql_query("SHOW TABLES LIKE 'users'"))){
  if(!($create_table_users) || !($create_table_picdata)){
    echo "<font color=red>[-] Create table</font><br>";
    echo mysql_error();
    $error = 1;
  }else{
    echo "<font color=green>[+] Create table</font><br>";
  }
}else{
  echo "<font color=green>[+] Create table (exists)</font><br>";
}

$populate = mysql_query("
INSERT INTO users (username, password)
VALUES ('$app_user', '$app_pass')
", $conx);

if(!$populate){
  echo "<font color=red>[-] Populate users</font><br>";
  echo mysql_error();
  $error = 1;
}else{
  echo "<font color=green>[+] Populate users</font><br>";
}

$populate = mysql_query("
INSERT INTO picdata (pic,uploader)
VALUES ('lolhax.jpg', 'Peruggia')
", $conx);

if(!$populate){
  echo "<font color=red>[-] Populate gallery</font><br>";
  echo mysql_error();
  $error = 1;
}else{
  echo "<font color=green>[+] Populate gallery</font><br>";
}

if(isset($error)){
  echo "<font color=red>Error!</font><br>";
  echo mysql_error();
}else{
  echo "<font color=green>Success!</font><br>";
}

mysql_close($conx);

echo "<br><a href=index.php><b>Main Page</b></a><br>";
echo "<a href=index.php?action=login><b>Log in</b></a>";

?>

</b>
</td>
<td valign=top>
<fieldset style=width:300;>
<legend><b>Information</b></legend>
Default admin username/password<br>
 - Username: <b>admin</b><br>
 - Password: <b>password</b><br>
<br>
Please delete this installer once it has completed successfuly.  Not doing so may leave undesired vulnerabilities.<br>
<br>
<b>Happy Hacking!</b>
</fieldset>
</td>
</tr>
</table>
</body>
</html>
</html>
