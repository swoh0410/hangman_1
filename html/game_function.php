<?php

	function get_user_id_from_user_name ($user_name) {//유저 네임으로  pk 찾기	
		$conn = get_connection();
		$id_query = sprintf("SELECT user_account_id FROM user_account WHERE id='%s';", $user_name);
		$result = mysqli_query ($conn, $id_query);
		$row = mysqli_fetch_assoc($result);
		$id = intval($row['user_account_id']);
		mysqli_close($conn);
		return ($id);
	}
	
	function get_user_name_from_user_id ($user_id) {//pk로 유저 네임 찾기	
		$conn = get_connection();
		$id_query = sprintf("SELECT id FROM user_account WHERE user_account_id=%d;", $user_id);
		$result = mysqli_query ($conn, $id_query);
		$row = mysqli_fetch_assoc($result);
		$id = $row['id'];
		mysqli_close($conn);
		return ($id);
	}
	
	function get_gaming_status() {
		$conn = get_connection();
		$room_query = sprintf("SELECT turn, winner FROM game_room WHERE game_room_id=%d;", get_my_game_room_id());
		$result = mysqli_query ($conn, $room_query);
		$row = mysqli_fetch_assoc($result);
		$turn = intval($row['turn']);
		$winner = intval($row['winner']);
		
		if ($winner !== 0){//게임이 끝남
			$my_position = get_my_position();
			if ($winner == $my_position) {
				return 'win';
			} else {
				return 'lose';
			}
		} else {
			if ($turn === 0){
				return 'waiting';//대기중
			} else {//진행
				if (is_my_turn()){
					return 'my_turn';
				} else {
					return 'enemy_turn';
				}
			}
		}
		mysqli_close($conn);
	}
	
	function get_my_position() {
		$conn = get_connection();
		$select_query = sprintf ('SELECT user1_id FROM hangman.game_room WHERE game_room_id = %d', get_my_game_room_id());
		$result = mysqli_query($conn, $select_query);
		$row = mysqli_fetch_assoc($result);
		$my_id = intval(get_user_id_from_user_name($_SESSION['info_dto']->getId()));
		if(intval($row['user1_id']) === $my_id){
			return 1;
		} else {
			return 2;
		}
		mysqli_close($conn);
	}
	
	function is_my_turn(){
		$conn = get_connection();
		$my_position = get_my_position();				
		$select_query = sprintf ('SELECT turn FROM hangman.game_room WHERE game_room_id= %d', get_my_game_room_id());
		$result = mysqli_query($conn, $select_query);
		
		while(NULL !==($row = mysqli_fetch_assoc($result))) {
			$turn = intval($row['turn']); 
		}		
		mysqli_free_result($result);
		if($my_position === $turn){
			
			$turn = true;
			
		}else{
			$turn = false;
		}
		mysqli_close($conn);
		return $turn;
	}
	
	function get_my_game_room_id() {
		if($_SESSION['info_dto']->getMode() === 'solo_game'){
			
		}else{
			if (null !== $_SESSION['info_dto']->getRoomId()) {
				return $_SESSION['info_dto']->getRoomId();
			} else {
				die('방번호 지정 에러');
			}
		}
	}
	
	function get_user_ids(){
		$conn = get_connection();
		$select_query = sprintf ('SELECT user1_id, user2_id FROM hangman.game_room WHERE game_room_id= %d', get_my_game_room_id());
		$result = mysqli_query($conn, $select_query);
		$row = mysqli_fetch_assoc($result);
		mysqli_close($conn);
		return array(intval($row['user1_id']), intval($row['user2_id']));
	}
	
	function get_enemy_id() {		
		if (get_user_ids()[0] === get_user_id_from_user_name($_SESSION['id'])){
			return get_user_ids()[1];
		} else {
			return get_user_ids()[0];
		}
		mysqli_close($conn);
	}

?>
