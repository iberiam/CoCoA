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

if(isset($_GET['changepass'])){

  if($_POST['newpass'] != $_POST['confnewpass']){
    header("Location: ".$peruggia_root."?action=account");
  }else{
    $oldpass = $_SESSION['password'];
    $newpass = md5($_POST['newpass']);
    mysql_query("UPDATE users SET password='".$newpass."' WHERE password='".$oldpass."'", $conx);
    session_destroy();
    header("Location: ".$peruggia_root."?action=account");
  }

}elseif(isset($_GET['adduser'])){

  if($guard_sqli){
    $newuser = mysql_real_escape_string($_POST['newuser']);
    $newuserpass = mysql_real_escape_string(md5($_POST['newuserpass']));
  }else{
    $newuser = $_POST['newuser'];
    $newuserpass = md5($_POST['newuserpass']);
  }

  mysql_query("INSERT INTO users (username,password) VALUES ('".$newuser."','".$newuserpass."')", $conx);

  header("Location: ".$peruggia_root."?action=account");

}elseif(isset($_GET['deleteuser'])){

  if($_GET['deleteuser']==$_SESSION['username']){
    header("Location: ".$peruggia_root."?action=account");
  }else{
    if($guard_sqli){
      mysql_query("DELETE FROM users WHERE username='".mysql_real_escape_string($_GET['deleteuser'])."'", $conx);
    }else{
      mysql_query("DELETE FROM users WHERE username='".$_GET['deleteuser']."'", $conx);
    }
    header("Location: ".$peruggia_root."?action=account");
  }

}else{

  ?>

  <div align=center>
  <table width=1000 cellpadding=10 cellspacing=10>
  <tr>
  <td valign=top align=right>
  <fieldset style=width:300;>
  <legend><b>Change Password</b></legend>
  <form action=<?php echo $peruggia_root."?action=account&changepass=1"; ?> method=POST>
  New Password: <input type=password name=newpass><br>
  Confirm : <input type=password name=confnewpass><br>
  <br><div align=center><input type=submit value=Change></div>
  </form>
  </fieldset>
  </td>
  <td valign=top align=left>
  <fieldset style=width:300;>
  <legend><b>Add User</b></legend>
  <form action=<?php echo $peruggia_root."?action=account&adduser=1"; ?> method=POST>
  Username: <input type=text name=newuser><br>
  Password: <input type=text name=newuserpass><br>
  <br><div align=center><input type=submit value=Add></div>
  </form>  
  </fieldset><br>
  <fieldset style=width:300;>
  <legend><b>Delete User</b></legend>
  <table cellpadding=2 cellspacing=2 width=100%>
  <?php

$users = mysql_query("SELECT username FROM users", $conx);

$c = 0;
while($user = mysql_fetch_array($users)){

  echo "<tr>";
  if(($c % 2) == 0){
    echo "<td align=left class=box>";
  }else{
    echo "<td align=left class=box style=background-color:lightblue;>";
  }
  if($user['username']==$_SESSION['username']){
    echo "<b>".$user['username']."</b>";
  }else{
    echo $user['username'];
  }
  echo "</td>";
  if(($c % 2) == 0){
    echo "<td align=right class=box width=60>";
  }else{
    echo "<td align=right class=box width=60 style=background-color:lightblue;>";
  }
  if($user['username']==$_SESSION['username']){
    echo "<strike>[delete]</strike>&nbsp;";
  }else{
    echo "<a href=".$peruggia_root."?action=account&deleteuser=".$user['username'].">[delete]</a>&nbsp;";
  }  
  echo "</td>";
  echo "</tr>";
  $c++;
}

  ?>

  </table>
  </fieldset>
  </td>
  </tr>
  </table>

  <?php

}

?>
