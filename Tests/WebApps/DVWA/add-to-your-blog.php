<?php
	$lBlogEntry = $_REQUEST["blog_entry"];
	if(1==1){
		$query = $lBlogEntry;
		$result = mysqli_query($query);
	}
	$query2  = "SELECT * FROM blogs_table WHERE
			blogger_name like '{$lLoggedInUser}%'
			ORDER BY date DESC
			LIMIT 0 , 100";
			
	$result = mysqli_query($query2);
	    	
	$lBloggerName = $result;
	$lDate = $result;
	$lComment = $result;
	echo $lBloggerName;


?>

