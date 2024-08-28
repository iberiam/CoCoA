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

echo "<div align=center>";

if(isset($_GET['paper'])){

$whitelist = array(NULL, //Will never load first entry for some reason, this is the fix.
	"http://milw0rm.com/papers/192",
	"http://milw0rm.com/papers/162",
	"http://milw0rm.com/papers/173",
	"http://milw0rm.com/papers/308",
	"http://milw0rm.com/papers/279",
	"http://milw0rm.com/papers/202",
	"http://milw0rm.com/papers/16",
	"http://milw0rm.com/papers/260",
	"http://milw0rm.com/papers/176",
	"http://destroy.net/machines/security/P49-14-Aleph-One"
);

if($guard_rfi){
  if(!array_search($_GET['paper'], $whitelist)){
    echo "<div align=center><h5>Unknown Resource</h5></div><br>";
  }else{
    echo "<div align=center><h5>- ";
    if($guard_refl_xss){
      echo htmlentities($_GET['type']);
    }else{
      echo $_GET['type'];
    }
    echo " -</h5></div><br>";
    echo "<textarea cols=100 rows=30 style=background-color:white;>";
    include($_GET['paper']);
    echo "</textarea>";
  }
}else{
  if($guard_refl_xss){
    echo "<div align=center><h5>- ".htmlentities($_GET['type'])." -</h5></div><br>";
  }else{
    echo "<div align=center><h5>- ".$_GET['type']." -</h5></div><br>";
  }
  echo "<textarea cols=100 rows=30 style=background-color:white;>";
  include($_GET['paper']);
  echo "</textarea>";
  
}

}else{

?>
<fieldset style=width:500;background-color:aliceblue;>
<legend style=background-color:aliceblue;><b>Papers</b></legend>
<fieldset class=box>
<div align=left>
<a href=<?php echo $peruggia_root."?action=learn&type=XSS&paper="; ?>http://milw0rm.com/papers/192><b>Cross Site Scripting - Attack and Defense Guide</b></a><br>
<b>At:</b> http://milw0rm.com/papers/192<br>
<b>By:</b> Xylitol<br>
<b>Catagoy:</b> XSS<br>
</fieldset>
<br>
<fieldset class=box>
<div align=left>
<a href=<?php echo $peruggia_root."?action=learn&type=XSS&paper="; ?>http://milw0rm.com/papers/162><b>Cross Site Scripting filtration Bypass</b></a><br>
<b>At:</b> http://milw0rm.com/papers/162<br>
<b>By:</b> t0pP8uZz<br>
<b>Catagoy:</b> XSS<br>
</fieldset>
<br>
<fieldset class=box>
<div align=left>
<a href=<?php echo $peruggia_root."?action=learn&type=XSS&paper="; ?>http://milw0rm.com/papers/173><b>XSS The Complete Walkthrough</b></a><br>
<b>At:</b> http://milw0rm.com/papers/173<br>
<b>By:</b> Xylitol<br>
<b>Catagoy:</b> XSS<br>
</fieldset>
<br>
<fieldset class=box>
<div align=left>
<a href=<?php echo $peruggia_root."?action=learn&type=SQLi&paper="; ?>http://milw0rm.com/papers/308><b>MySQL: Secure Web Apps - SQL Injection techniques</b></a><br>
<b>At:</b> http://milw0rm.com/papers/308<br>
<b>By:</b> Omni<br>
<b>Catagoy:</b> SQLi<br>
</fieldset>
<br>
<fieldset class=box>
<div align=left>
<a href=<?php echo $peruggia_root."?action=learn&type=SQLi&paper="; ?>http://milw0rm.com/papers/279><b>Full MSSQL Injection PWNage</b></a><br>
<b>At:</b> http://milw0rm.com/papers/279<br>
<b>By:</b> CWH Underground<br>
<b>Catagoy:</b> SQLi<br>
</fieldset>
<br>
<fieldset class=box>
<div align=left>
<a href=<?php echo $peruggia_root."?action=learn&type=SQLi&paper="; ?>http://milw0rm.com/papers/202><b>SQL Injection Tutorial</b></a><br>
<b>At:</b> http://milw0rm.com/papers/202<br>
<b>By:</b> Marezzi<br>
<b>Catagoy:</b> SQLi<br>
</fieldset>
<br>
<fieldset class=box>
<div align=left>
<a href=<?php echo $peruggia_root."?action=learn&type=SQLi&paper="; ?>http://milw0rm.com/papers/16><b>Sql Injection Paper </b></a><br>
<b>At:</b> http://milw0rm.com/papers/16<br>
<b>By:</b> zeroday<br>
<b>Catagoy:</b> SQLi<br>
</fieldset>
<br>
<fieldset class=box>
<div align=left>
<a href=<?php echo $peruggia_root."?action=learn&type=LFI&paper="; ?>http://milw0rm.com/papers/260><b>LFI to RCE Exploit with Perl Script</b></a><br>
<b>At:</b> http://milw0rm.com/papers/260<br>
<b>By:</b> CWH Underground<br>
<b>Catagoy:</b> LFI<br>
</fieldset>
<br>
<fieldset class=box>
<div align=left>
<a href=<?php echo $peruggia_root."?action=learn&type=RCE&paper="; ?>http://milw0rm.com/papers/176><b>Php Endangers - Remote Code Execution</b></a><br>
<b>At:</b> http://milw0rm.com/papers/176<br>
<b>By:</b> Arham Muhammad<br>
<b>Catagoy:</b> RCE<br>
</fieldset>
<br>
<i><b>Not really web related, but <u>everyone</u> should read...</b></i><br>
<br>
<fieldset class=box>
<div align=left>
<a href=<?php echo $peruggia_root."?action=learn&type=BoF&paper="; ?>http://destroy.net/machines/security/P49-14-Aleph-One><b>Smashing The Stack For Fun And Profit</b></a><br>
<b>At:</b> http://destroy.net/machines/security/P49-14-Aleph-One<br>
<b>By:</b> Aleph One<br>
<b>Catagoy:</b> BoF<br>
</fieldset>

<?php

}

echo "</div>";

?>
