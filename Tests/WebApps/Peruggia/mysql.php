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

$conx = mysql_connect($mysql_host, $mysql_user, $mysql_pass) or die(
  "<title>Error</title>".
  "<b>Error: Couldn't connect to MySQL!</b><br>".
  "Did you set your mysql host/user/pass in conf.php yet?"
);

mysql_select_db($mysql_db) or die(
  "<title>Error</title>".
  "<b>Error: Couldn't select database!</b><br>".
  "Maybe you haven't run the <a href=".$peruggia_root."install.php>install script</a> yet?"
);

?>
