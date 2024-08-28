<?php 

			$targethost = $_REQUEST["target_host"]; 



			if($lEnableJavaScriptValidation){
				echo "var lInvalidPattern = /[;&]/;";
			}else{
				echo "var lInvalidPattern = /*/;";
			}// end if

		

	if (isset($_POST["dns-lookup-php-submit-button"])){
	    try{
	    	if ($targethost_validated){
	    		echo '<p class="report-header">Results for '.$lTargetHostText.'<p>';
    			echo '<pre class="report-details">';
    			echo shell_exec("nslookup " . $targethost);
				echo '<pre>';
				$LogHandler->writeToLog($conn, "Executed operating system command: nslookup " . $lTargetHostText);
	    	}else{
	    		echo '<script>document.getElementById("id-bad-cred-tr").style.display=""</script>';
	    	}// end if ($targethost_validated){

    	}catch(Exception $e){
			echo $CustomErrorHandler->FormatError($e, "Input: " . $targethost);
    	}// end try
    	
	}// end if (isset($_POST)) 
?>

