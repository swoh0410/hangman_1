<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
<head>
<link rel="stylesheet" type="text/css" href="/css/mystyle.css">
</head>
<body>
<div class="content">
<h1>에러가 발생했습니다!</h1>
<?php
	if (isset($_GET['error_code'])) {
		echo "error code was: ".$_GET['error_code']."<br>";
		switch ($_GET['error_code']) {
			case "1": 
				echo "존재하지 않는 아이디이거나 비밀번호가 틀립니다.<br>";
				break; 
			case "2":
				echo "session.use_only_cookies 실패<br>";
				break;
			case "3":
				echo "보호된 페이지에 갔는데 로그인이 안 되있다<br>";
				break;
			case "4":
				echo "이미 사용중인 아이디 입니다<br>";
				break;
			case "5":
				echo "수정 및 삭제 권한이 없습니다<br>";
				break;
			case "100":
				echo "Debug<br>";
				break;
			default:		
				echo "기타 에러<br>";
				break;			
		}
	} else {
		echo "인수가 없다<br>";
	}
?>
</div>
</body>
</html>