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

if(isset($_SESSION['admin']) && ($_SESSION['admin']==1)){

  echo "<div align=center><h5>You are already logged in</h5></div>";

}elseif(isset($_GET['check']) && ($_GET['check']==1)){
  if($guard_auth_sqli){
    $creds = mysql_query("SELECT * FROM users WHERE username='".mysql_real_escape_string($_POST['username'])."' AND password='".md5($_POST['password'])."'", $conx);
  }else{
    $creds = mysql_query("SELECT * FROM users WHERE username='".$_POST['username']."' AND password='".md5($_POST['password'])."'", $conx);
  }
  $creds = mysql_fetch_array($creds);
  if($creds){
    if($guard_pers_xss){
      $_SESSION['username'] = htmlentities($creds['username']);
    }else{
      $_SESSION['username'] = $creds['username'];
    }
    $_SESSION['password'] = $creds['password'];
    $_SESSION['admin'] = 1;
    header("Location: ".$peruggia_root);
  }else{
    header("Location: ".$peruggia_root."?action=login");
  }

}else{

  ?>

  <div align=center>
  <fieldset style=width:300;>
  <legend><b>Login</b></legend>
  <form action=<?php echo $peruggia_root."?action=login&check=1"; ?> method=post>
  <br>
  Username: <input type=text name=username><br>
  Password: <input type=password name=password><br>
  <br><input type=submit value=Login><br>
  </form>
  </fieldset>
  </div>

  <?php

}

?>
