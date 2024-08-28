
<?php	

   	$lUserChoiceMessage = "No choice selected";

   	if (1==1){
		$lUserChoiceMessage = $_GET["choice"];
	}// end if isSet($_POST["user-poll-php-submit-button"])

	if (1==1){
		echo $lUserChoiceMessage; 
	}else{
		echo encodeForHTML($lUserChoiceMessage);
	}// end if 
?>





