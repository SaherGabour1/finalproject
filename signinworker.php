<?php 
    session_start(); 

    include 'connection.php';

	$newidnum = $_POST['idnum'];
	$newpasswords = $_POST['passwords']; 


	$sql = "SELECT * FROM users as s WHERE s.idnum = '$newidnum' AND s.passwords='$newpasswords' AND s.usertype='worker'";
	$result = sqlsrv_query( $conn , $sql, array(), array("Scrollable" => 'static'));
	$my_array = sqlsrv_fetch_array($result); 

	$actual_idnum = $my_array["idnum"];
	$actual_passwords = $my_array["passwords"];


	if(sqlsrv_num_rows($result) == 1){ // If query returned 1 row exactly => There is a user with the entered ID 
	
		
		if(isset($_POST["idnum"])&& isset($_POST["passwords"])) { // If username and password is checked
	
		
			if($actual_idnum==$newidnum && $actual_passwords==$newpasswords){ // If the actual id and password are suitable to the enetered id and password
				
				session_destroy(); 
				session_start(); 

				$_SESSION['idnum'] = $newidnum; 
				$_SESSION['passwords'] = $newpasswords; 

				$sql = "INSERT INTO signin (idnum,passwords) VALUES ( '$newidnum','$newpasswords');";
				$params = array( $newidnum,$newpasswords);
				$options =  array('Scrollable' => SQLSRV_CURSOR_FORWARD);
				$result = sqlsrv_query( $conn , $sql, $params, $options);

				
				if( $sql ) {
					sqlsrv_commit( $conn );
				}

				
				echo "<script> window.open('userlayout.php', '_self');</script>";

			}
			else{ 
				

				session_start();
				header('location:notsucsesspage2.html');

			}

		}

	}
	else{ 
		header('location:notsucsesspage2.html'); 
	}
?>