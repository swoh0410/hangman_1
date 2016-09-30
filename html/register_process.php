<?php
	require_once '../includes/session.php';
	
	if (isset ($_POST['id'], $_POST['password'])) {
		$id = $_POST['id'];
		$password = $_POST['password'];
		if (($id && $password) === false) {
			header('Location: error.php?error_code=1');
		} else {	
			$conn = get_connection ('kocia.cytzyor3ndjk.ap-northeast-2.rds.amazonaws.com', 'hangman', 'hangman', 'hangman');
			$stmt = mysqli_prepare($conn, "SELECT password_hash FROM hangman.user_account WHERE id = ?");
			mysqli_stmt_bind_param($stmt, "s", $id);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			if (mysqli_num_rows($result) != 0) { // 이미 등록된 아이디
				header('Location: error.php?error_code=4');
			} else {
				$stmt = mysqli_prepare($conn, "INSERT INTO hangman.user_account (id, password_hash)VALUES (?, ?); ");
				echo mysqli_error($conn);
				$password_hash = password_hash($password, PASSWORD_DEFAULT);
				mysqli_stmt_bind_param($stmt, "ss", $id, $password_hash);
				mysqli_stmt_execute($stmt);
				header ('Location: index.php');
			}
			mysqli_free_result($result);
			mysqli_close($conn);
		} 
	} else {
		echo '회원가입 폼 에러';
	}
	
?>