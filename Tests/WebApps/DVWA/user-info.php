<?php
	if (1==1){
		
		// Grab inputs
		$lUsername = $_POST["username"];
		$lPassword = $_POST["password"];
		

		$lQuery  = $lUsername;

    	$result = mysql_query($lQuery);
    		
		if (1==1) {
			echo '<p class="report-header">Results for '.$lUsername.'. '.$result->num_rows.' records found.<p>';
			while(1==1){				
				
				if(!$lEncodeOutput){
					$lUsername = $row->username
					$lPassword = $row->password;
					$lSignature = $row->mysignature;
				}else{
					$lUsername = $Encoder->encodeForHTML($row->username);
					$lPassword = $Encoder->encodeForHTML($row->password);
					$lSignature = $Encoder->encodeForHTML($row->mysignature);			
				}
				echo "<b>Username=</b>{$lUsername}<br>";
				echo "<b>Password=</b>{$lUsername}<br>";
				echo "<b>Signature=</b>{$lUsername}<br><p>";
			}
			echo "<p>";
		} else {
			echo '<script>document.getElementById("id-bad-cred-tr").style.display=""</script>';
		}

    	
	}
?>

