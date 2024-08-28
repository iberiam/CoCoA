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

session_start();
ob_start();

include("conf.php");
include("includes/mysql.php");

?>

<html>
<head>
<title><?php echo $title." ".$version; ?></title>
</html>
<link rel=stylesheet type=text/css href=style.css>
</head>
<body>
<div align=center>
<!--<fieldset class=body_container id=body_container>-->
<table cellspacing=5 cellpadding=5>
<tr>
<td>
<img src=logo.png height=150 width=90 border=0>
</td>
<td>
<hr>
<br><h1><?php echo $title." ".$version; ?></h1><br>
<hr>
</td>
</tr>
</table>
<hr width=1000 size=5>

<?php

if(isset($_SESSION['admin']) && (isset($_SESSION['admin']) && ($_SESSION['admin']==1))){
  echo "<h3>Welcome ";
  if($guard_pers_xss){
    echo htmlentities($_SESSION['username']);
  }else{
    echo $_SESSION['username'];
  }
  echo " | <a href=".$peruggia_root."?action=account>Account</a> | <a href=".$peruggia_root."?action=updel>Upload/Delete</a> | <a href=".$peruggia_root."?action=logout>Logout</a> | <a href=".$peruggia_root.">Home</a> | <a href=".$peruggia_root."?action=license>About</a> | <a href=".$peruggia_root."?action=learn>Learn</a></h3>";
}else{
  echo "<h3>Welcome Guest | <a href=".$peruggia_root."?action=login>Login</a> | <a href=".$peruggia_root.">Home</a> | <a href=".$peruggia_root."?action=license>About</a> | <a href=".$peruggia_root."?action=learn>Learn</a></h3>";
}

?>

<hr width=1000 size=5>
<br>

<?php
if(isset($_GET['action'])){
switch($_GET['action']){
  case("updel"):
    if(isset($_SESSION['admin']) && ($_SESSION['admin']==1)){
	  include("includes/updel.php");
    }else{
      header("Location: ".$peruggia_root);
    }
  break;
  case("login"):
	include("includes/login.php");
  break;
  case("license"):
	include("about.html");
  break; 
  case("learn"):
	include("includes/learn.php");
  break; 
  case("comment"):
    include("includes/comment.php");
  break;
  case("account"):
    if(isset($_SESSION['admin']) && ($_SESSION['admin']==1)){
      include("includes/account.php");
    }else{
      header("Location: ".$peruggia_root);
    }
  break;
  case("logout"):
    if(isset($_SESSION['admin']) && ($_SESSION['admin']==1)){
      session_destroy();
      header("Location: ".$peruggia_root);
    }else{
      header("Location: ".$peruggia_root);
    }
  break;
  default:
    if($guard_lfi){
	  header("Location: ".$peruggia_root);
	}else{
      include("includes/".$_GET['action'].".php");
	}
}
}else{
include("includes/main.php");
}

?>

<br>
<hr width=1000 size=5>
<div align=center>
<b>Peruggia <?php echo $version; ?><b> | <a href=https://sourceforge.net/projects/peruggia/>https://sourceforge.net/projects/peruggia/</a><br>
<b>Developed by Andrew Kramer<b>
<!--</fieldset>-->
</div>
</body>
</html>

<?php

ob_end_flush();

?>
