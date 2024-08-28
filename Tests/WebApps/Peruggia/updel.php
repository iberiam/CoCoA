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

if(!isset($_SESSION['admin'])){
  header("Location: ".$peruggia_root);
}

if(isset($_GET['pic_id'])){
  if($guard_sqli){
    $filequery = mysql_query("SELECT pic FROM picdata WHERE ID LIKE ".(int)$_GET['pic_id'], $conx);
  }else{
    $filequery = mysql_query("SELECT pic FROM picdata WHERE ID LIKE ".$_GET['pic_id'], $conx);
  }
  $delfile = mysql_fetch_array($filequery);
  unlink("images/".$delfile['pic']);
  mysql_query("DELETE FROM picdata WHERE pic LIKE '".$delfile['pic']."'", $conx);
  echo "<div align=center><h5>Picture Deleted</h5></div><br>";
}

if(isset($_GET['upload'])){
  if($guard_pers_xss){
    $file = htmlentities($_FILES['upfile']['name']);
  }else{
    $file = $_FILES['upfile']['name'];
  }
  if($guard_sqli){
    $path = "images/".mysql_real_escape_string(basename($file));
    $uploader = mysql_real_escape_string($_SESSION['username']);
  }else{
    $path = "images/".basename($file);
    $uploader = $_SESSION['username'];
  }
  if($guard_fuv){
    if(!in_array($_FILES['upfile']['type'], $fu_types)) {
      exit("<div align=center><h5>File is not a valid image type</h5></div>");
    }
  }
  if($guard_pers_xss){
    $path = htmlentities($path);
  }
  move_uploaded_file($_FILES['upfile']['tmp_name'], $path);
  mysql_query("INSERT INTO picdata (pic,uploader) VALUES ('".$file."', '".$uploader."')", $conx);
  if($guard_pers_xss){
    echo "<div align=center><h5>Picture \"".htmlentities(basename($file))."\" Uploaded</h5></div><br>";
  }else{
    echo "<div align=center><h5>Picture \"".basename($file)."\" Uploaded</h5></div><br>";
  }
}

$images = array_diff(scandir("images"), array(".", "..", ".svn"));

?>

<div align=center>
<table width=1000 cellpadding=10 cellspacing=10 align=center>
<tr>
<td valign=top align=right>
<fieldset style=width:300;>
<legend><b>Upload</b></legend>
<form enctype=multipart/form-data action=<?php echo $peruggia_root."?action=updel&upload=1"; ?> method=POST>
Choose a file to upload:<br>
<br>
<input name=upfile type=file><br>
<br>
<input type=submit value=Upload>
</form>
</fieldset>
</td>
<td valign=top align=left>
<fieldset style=width:300;>
<legend><b>Delete</b></legend>
<?php
foreach($images as $pic){
$delquery = mysql_query("SELECT ID FROM picdata WHERE pic LIKE '".$pic."'", $conx);
$data = mysql_fetch_array($delquery);
?>
<a href=<?php echo "images/".$pic; ?>><img src="images/<?php echo $pic; ?>" border=1></a><br>
<a href=<?php echo $peruggia_root."?action=updel&pic_id=".$data['ID']; ?>><b>Delete this picture</b></a>
<br><br>
<?php
}
?>
</fieldset>
</td>
</tr>
</table>
</div>
