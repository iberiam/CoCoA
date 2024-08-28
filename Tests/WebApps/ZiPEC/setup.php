<?PHP
/*
	ZiPEC Setup UI

*/

foreach ( $cfg['context'] as $key => $value ) {
	$options .= "<option value='$key'>".$cfg['context'][$key]['label']."</option>";
}


?>
<script type='text/javascript'>
function save_settings() {
	localStorage.context=document.getElementById('context').value ;
	localStorage.login=document.getElementById('login').value ;
//	localStorage.password=document.getElementById('password').value ; // No password is required, we don't rely on Zenoss' auth system
}
function setSelectedIndex(s, v) {
    for ( var i = 0; i < s.options.length; i++ ) {
        if ( s.options[i].value == v ) {
            s.options[i].selected = true;
            return;
        }
    }
}

</script>
<div id='setup_page'>
	<h2>Setup</h2>
	<p>ZiPEC v<?PHP echo $VERSION;?></p>
	<form id='setup' onsubmit='save_settings();'>
	<label for='context'>Context</label><select name='context' id='context'><?PHP echo $options; ?></select>
	<label for='login' >Login</label><input type='text' id='login' name='login' value='' />
	<br/><i>(Used as the owner name in the log)</i>
	<!--	<a href='#' onclick='save_settings();document.getElementById("login").value=localStorage.login' >Save</a>-->
	<!-- // No authentication is really being done, so we just use login to write the owner name when events are aknowledged
	<label for='password' >Password</label><input type='password' id='password' name='password' value='' />
	-->
	<br/>
	<input type='submit' value='Save' onclick='save_settings();' />
</form>
</div>
<script type='text/javascript'>
	// load settings from localStorage
	document.getElementById('login').value=localStorage.login ;
	if (localStorage.context) {
		document.getElementById('context').options[localStorage.context].selected=true;
	}
	document.getElementById('setup').src='skins/'+localStorage.skin+'/img/setup_on.png';
	// Prevent iScroll from interfering with our select box
	if (!navigator.userAgent.match('OS 5_')) {
		var selectField = document.getElementById('context');
		selectField.addEventListener('touchstart' /*'mousedown'*/, function(e) {e.stopPropagation();}, false);
	}
 </script>
