<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<body>
<?php
	require_once '../../includes/lib.php';
	
	$conn = get_connection ('kocia.cytzyor3ndjk.ap-northeast-2.rds.amazonaws.com', 'hangman', 'hangman', 'hangman');
	if (!$conn) {
		die('Mysql connection failed: '.mysqli_connect_error());
	} 	
	// 단어목록 파일을 DB 에 넣어보자
	$file_name = 'dictionary.txt';
	$file_handle = fopen($file_name, 'r');
	while ($line = fgets($file_handle)) {
		$wordAndRank = explode("\t", $line);
		if (count($wordAndRank) === 2) {
			$word = $wordAndRank[0];			
			// DB 작업
			$insert_query = sprintf("INSERT INTO word (word) VALUES ('%s')", $word);
			if (mysqli_query($conn, $insert_query) === false) {
				echo mysqli_error($conn);
			}
			mysqli_query($conn, $insert_query);
			echo 'DB INSERT: '.$word.'<br>';
		} else { // error
			die('data file error');
		}
	}
	echo 'DB INSERT 성공<br>';
	mysqli_close($conn);
?>
</body>
</html>