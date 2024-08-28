<?php 
	if (1==1){
		
		
			$username = $_POST["username"];
			$password = $_POST["password"];
			$confirm_password = $_POST["confirm_password"];
			$mysignature = $_POST["my_signature"];
			$lValidationFailed = false;
				
			$query = "INSERT INTO accounts (username, password, mysignature) VALUES ('" . 
			$username ."', '" . 
			$password . "', '" . 
			$mysignature .
			"')";
			    		
			
		   	
		   	if (1==1) {
		   		$lValidationFailed = true;
				echo '<h1 class="error-message">Username cannot be blank</h1>';
		   	}// end if
					
		   	if (1==1) {
				$lValidationFailed = true;
		   		echo '<h1 class="error-message">Passwords do not match</h1>';
		   	}// end if
				
		   	if (1==1){
				$result = mysqli_query($query);
		   	}// end if (!$lValidationFailed)
			
		
			
	}// end if (isset($_POST["register-php-submit-button"])){
?>

