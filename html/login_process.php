<?php
	require_once '../includes/session.php';
	start_session();
	
	if (isset($_POST['id'], $_POST['password'])){
		$id = $_POST['id'];
		$password = $_POST['password'];
		//echo "ID: ".$id. "<br> PW: " . $password;
		
		if (try_to_login($id, $password) == true) {			
			header("Location: index.php");			
		} else {
			header('Location: error.php?error_code=1');
		}
	} else {
		echo '로그인 폼 에러';
	}
?>