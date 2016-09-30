<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/style.css">
<title>2조 PROJECT - HANGMAN GAME</title>
</head>
<body>
<?php include 'header.php'; ?>
<div id="wrap">
	<div id="content">
		<div class="register_page">
			<h1>회원 가입</h1>
			<form action="register_process.php" method="post">
				<table>
					<tr>
						<th>ID</th>
						<td><input type="text" name="id"></td>
						<td rowspan="2"><input type="submit" value="회원 가입"></td>
					</tr>
					<tr>
						<th>PW</th>
						<td><input type="text" name="password"></td>
					</tr>
				</table>
			</form>
		</div>
		<p><a href="index.php"><input type="button" value="홈으로"></a></p>
	</div>
</div>
<?php include 'footer.php'; ?>
</body>
</html>