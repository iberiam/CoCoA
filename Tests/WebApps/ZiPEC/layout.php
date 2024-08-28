<?PHP
/*
=========================================================================

                               ZiPEC
                  ( Zenoss iPhone Event Console )

	Copyright 2008-2012 Willy Le Roy
	Released Under GNU GPL License - See LICENSE.txt

	ZiPEC is a 'webapp' allowing the user to display active
	alarms and events of a zenoss server on an iPhone or an iPod
	Touch

	Zenoss is an enterprise grade monitoring system that
	provides Inventory/Configuration, event, Performance and
	Availability management
	
	The Zenoss logo is a registered trademark of Zenoss, Inc.
	Zenoss and Open Enterprise Management are trademarks of 
	Zenoss, Inc. in the U.S. and other countries.

	'iPhone' and 'iPod Touch' are registered Trademarks
	of Apple Computers, Inc.

========================================================================
*/
?>

<!--<html manifest="ZiPEC.manifest.php">-->
<html>
<head>
	<meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=ISO-8859-1"/>
	<meta name='viewport' content='width = device-width, initial-scale=1,maximum-scale=1, user-scalable=no'/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta name="format-detection" content = "telephone=no"/>

	<!-- startup image for web apps - iPad - landscape (748x1024)
	Note: iPad landscape startup image has to be exactly 748x1024 pixels (portrait, with contents rotated).-->
	<link rel="apple-touch-startup-image" href="skins/<?PHP echo $cfg["ui_skin"]."/img/ipad-landscape-loading.png" ;?>" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)" />

	<!-- startup image for web apps - iPad - portrait (768x1004) -->
	<link rel="apple-touch-startup-image" href="skins/<?PHP echo $cfg["ui_skin"]."/img/ipad-portrait-loading.png" ;?>" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)" />

	<!-- startup image for web apps (320x460) -->
	<link rel="apple-touch-startup-image" href="skins/<?PHP echo $cfg["ui_skin"]."/img/iphone-loading.png" ;?>" media="screen and (max-device-width: 320px)" />

	<link rel='apple-touch-icon' href='skins/<?PHP echo $cfg["ui_skin"];?>/img/apple-touch-icon.png'/>
	<!-- Uncomment below if you don't want the glowing effect over your icon -->
<!--	<link rel="apple-touch-icon-precomposed" href='skins/<?PHP echo $cfg["ui_skin"];?>/img/apple-touch-icon-precomposed.png' /> -->
	<link type='text/css' rel='stylesheet' href='skins/<?PHP echo $cfg["ui_skin"]."/".$css_file ;?>' />
	<!--<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Droid+Sans">-->
	<title>ZiPEC <?PHP echo $VERSION;?></title>
<script type='text/javascript' src='js/functions.js'></script>
<script type='text/javascript'>
localStorage.skin = "<?PHP echo $cfg[ui_skin];?>";
</script>
<script type='text/javascript' src='js/ajax.js'></script>
<script type='text/javascript' src='js/ajax-dynamic-content.js'></script>

<script type='text/javascript' src='js/iscroll-min.js'></script>

<script type='text/javascript' src='js/test.js'></script>

</head>
<body>
	<div class='container'>
	<form action='index.php' id='events_form' name='event_form'>
		<div class='header'></div>
		<div class='headerOverlay'></div>
		<div class='statusBar'><?PHP echo $page["status"];?></div>	
			<div id='list_container'>
			<div id='list'>
					<?PHP echo $page["content"]?>
				</div>
				<div id='install_warning' class='' style='font-size:30pt; height: 300px; background-color: #666; color:#fff; padding: 20px; display: none;'>
				This application needs to be installed on the Slideboard.	
				</div>
			</div>
		<div class='toolbar'><?PHP echo $page["toolbar"];?></div>
		<div class='debug'><?PHP echo $page["debug"];?></div>
	</form>
	</div>
<script type='text/javascript'>
</script>
<script type='text/javascript' >
//if ((!window.navigator.standalone)&&((window.navigator.platform=='iPhone')||(window.navigator.platform=='iPod'))) {
if (false) {
	document.getElementById('list').style.display='none';
	document.getElementById('install_warning').style.display='block';
} else { 
	window.onload = ajax_loadContent('list','?mode=update&context=<?PHP echo $context;?>&show='+localStorage.show);
	if (!navigator.userAgent.match('OS 5_')) {	
		window.onload = myScroll = new iScroll('list');
	}
}
</script>
</body>
</html>
