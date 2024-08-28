<?php
	



							try {
								$query  = "SELECT * FROM accounts";
								$result = $conn->query($query);
								if (!$result) {
							    	throw (new Exception('Error executing query: '.$conn->error, $conn->errorno));
							    }// end if							    
							    while($row = $result->fetch_object()){

									if(!$lEncodeOutput){
										$lUsername = $result->username;
									}else{
										$lUsername = $Encoder->encodeForHTML($result->username);
									}// end if
									
								    echo '<option value="' . $lUsername . '">' . $lUsername . '</option>\n';
									
								}// end while
							} catch (Exception $e) {
								echo $CustomErrorHandler->FormatError($e, $query);
							}// end try		




			if($lProtectAgainstSQLInjection){
				$lAuthor = $conn->real_escape_string($_POST["author"]);
			}else{
				$lAuthor = $_REQUEST["author"];
			}// end if
			
			if ($lAuthor == "53241E83-76EC-4920-AD6D-503DD2A6BA68" || strlen($lAuthor) == 0){
				echo '<script>document.getElementById("id-bad-blog-entry-tr").style.display="";</script>';
			}else{
				if ($lAuthor == "6C57C4B5-B341-4539-977B-7ACB9D42985A"){
					$lAuthor = "%";
				}// end if

				$query  = "SELECT * FROM blogs_table WHERE
							blogger_name like '{$lAuthor}'
							ORDER BY date DESC
							LIMIT 0 , 100";
							
				$result = $conn->query($query);
				if (!$result) {
			    	throw (new Exception('Error executing query: '.$conn->error, $conn->errorno));
			    }// end if

				/* Report Header */
				echo '<div>&nbsp;</div>';
				echo '<table border="1px" width="90%" class="main-table-frame">';
			    echo '
			    	<tr class="report-header">
			    		<td colspan="4">'.$result->num_rows.' Current Blog Entries</td>
			    	</tr>
			    	<tr class="report-header">
			    		<td>&nbsp;</td>
					    <td>Name</td>
					    <td>Date</td>
					    <td>Comment</td>
				    </tr>';

			    $lRowNumber = 0;
			    while($row = $result->fetch_object()){
			    	
			    	$lRowNumber++;
			    			    	
					if(!$lEncodeOutput){
						$lBloggerName = $row->blogger_name;
						$lDate = $row->date;
						$lComment = $row->comment;
					}else{
						$lBloggerName = $Encoder->encodeForHTML($row->blogger_name);
						$lDate = $Encoder->encodeForHTML($row->date);
						$lComment = $Encoder->encodeForHTML($row->comment);
					}// end if
			    	

										
					echo "<tr>
							<td>{$lRowNumber}</td>
							<td>{$lBloggerName}</td>
							<td>{$lDate}</td>
							<td>{$lComment}</td>
						</tr>\n";
				}//end while $row

?>

