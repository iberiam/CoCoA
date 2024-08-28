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

//MySQL
$mysql_host = 		"localhost";
$mysql_user = 		"peruggia";
$mysql_pass = 		"peruggia";
$mysql_db = 		"peruggia";

//General
$title = 		"Peruggia";
$version = 		"1.2";
$peruggia_root = $_SERVER['PHP_SELF'];
error_reporting(1);	// You may or may not want to see these, your choice

//Vulnerabilities (true or false)
//NOTE: disabling some vulnerabilities may render others useless
$guard_pers_xss = 	false; //Block persistent cross site scripting
$guard_refl_xss = 	false; //Block reflected cross site scripting
//$guard_csrf = 	true; //Block cross site request forgery //Blocker not yet implemented
//$guard_sesfix = 	true; //Block session fixation //Blocker not yet implemented
$guard_sqli = 		false; //Block SQL injection
$guard_auth_sqli = 	false; //Block authentication bypass SQL injection
$guard_lfi = 		false; //Block local file inclusions
$guard_rfi = 		false; //Block remote file inclusions
$guard_fuv = 		false; //Block file upload vulnerabilities
$fu_types = array(	//Allowed file upload types (mime types)
	"image/gif",
	"image/jpeg",
	"image/pjpeg",
);

?>
