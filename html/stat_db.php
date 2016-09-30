<?php
require_once '../includes/session.php';
require_once 'game_function.php';

function insert_stats() { //이겼을때 stats테이블 insert/update
	$my_id = get_user_id_from_user_name ($_SESSION['id']);
	$enemy_id = get_enemy_id();
	add_stats($my_id, true);
	add_stats($enemy_id, false);
}

function add_stats($user_id, $is_win) { //이겼을때 stats테이블 insert/update
	$id = $user_id; //id값 지정
	$conn = get_connection ();
	$select_query = sprintf("SELECT COUNT(*) AS num FROM stats WHERE user_account_id=%d;", $id); //유저 id가 테이블에 있는지 확인
	$result = mysqli_query($conn, $select_query);
	$num = mysqli_fetch_assoc($result)['num'];
	if ($is_win === true){
		$match_result = 'win';
	}else {
		$match_result = 'lose';
	}
	
	if ($num > 0) {// id가 테이블에 있을때
		if (get_gaming_status() === 'win') {
			$update_query = sprintf("UPDATE stats SET total=total+1, %s=%s+1 WHERE 
			user_account_id=%d", $match_result, $match_result, $id); //stats_id가 같은 곳에 total, win update
			mysqli_query($conn, $update_query);
			$win_rate = calculate_win_rate($id); //win_rate 업데이트
			update_win_rate($win_rate, $id);
		} else {
			die(get_gaming_status());
		}
	} else {//id가 테이블에 없을때
		if (get_gaming_status() === 'win') {
			$insert_query = sprintf("INSERT INTO stats (user_account_id, total, %s) VALUES (%d, 1, 1);", $match_result, $id); //유저 id, total=1, win=1 insert
			mysqli_query($conn, $insert_query);
			$win_rate = calculate_win_rate($id); //win_rate 업데이트
			update_win_rate($win_rate, $id);
		} else {
			//echo $_SESSION['gaming_status'].'<br>';
			die ('신규 스탯 인설트 에러');
		}
	}
	mysqli_close($conn);
}

function get_stats($id) {
	$conn = get_connection ();
	$select_query = sprintf('SELECT total, win, lose, win_rate FROM hangman.stats WHERE user_account_id = %d', $id);
	$result = mysqli_query($conn, $select_query);
	
	if($result === false) {
		echo 'cannot read stat data from DB!';
		mysqli_error($conn);
	} else {
		$row = mysqli_fetch_assoc($result);
	}
	//die ('get_stats');
	return $row;		
}




function calculate_win_rate($id){
	$row = get_stats($id);
		
	$win_rate = (intval($row['win']) / intval($row['total'])) * 100;
	
	mysqli_close($conn);	
	return $win_rate;
}

function update_win_rate($win_rate, $id) {
	$conn = get_connection ();
	$query = sprintf("UPDATE hangman.stats SET win_rate = %d WHERE user_account_id = %d", $win_rate, $id);
	mysqli_query($conn, $query);
	mysqli_close($conn);
}


?>