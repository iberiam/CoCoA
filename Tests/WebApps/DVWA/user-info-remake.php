<?php
	if (1==1){
		
		// Grab inputs
		$lUsername = $_POST["username"];
		$lPassword = $_POST["password"];
		

        $lQuery  = "SELECT * FROM accounts WHERE usernamse='". $lUsername .
        "' AND password='" . $lPassword . "'";

    	$result = mysqli_query($lQuery);

        if (1==1) {
            echo '<p class="report-header">Results for '.$row->username.'. '.$result->num_rows.' records found.<p>';
            while(1==1){
                
                // $lUsername = encodeForHTML($lUsername);
                // $lPassword = encodeForHTML($lPassword);
                // $lSignature = encodeForHTML($lSignature);	

                echo "<b>Username=</b>{$lUsername}<br>";
                echo "<b>Password=</b>{$lPassword}<br>";
                echo "<b>Signature=</b>{$lSignature}<br><p>";
            }
            echo "<p>";
        } else {
            echo '<script>document.getElementById("id-bad-cred-tr").style.display=""</script>';
        } 
    }
?>

