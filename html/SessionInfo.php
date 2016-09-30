<?php
require_once '../includes/session.php';
require_once 'game_function.php';
require_once 'stat_db.php';
class SessionInfo {
	private $id;
	private $mode;
	private $gaming_status;
	private $correct_answer ;
	private $currentWord;
	private $wrong;
	private $turn;
	private $winner;
	private $user1;
	private $user2;
	private $gameRoomId;

  //SessionInfo 생성자
  
  function __construct($info_array){ 
		$this->mode = $info_array['mode'];
		$this->wrong = array();
		echo "컨스트럭터 에서 받은 모드: " .$info_array['mode'] . "<br>";
		if(isset($info_array['id'])){
			$this->id = $info_array['id'];
		}
		if(isset($info_array['password'])){
			$this->password = $info_array['password'];
		}
		
		if(isset($info_array['gaming_status'])){
			$this->gaming_status = $info_array['gaming_status'];
		}
		if(isset($info_array['answer'])){
			$this->correct_answer = $info_array['answer'];
		}
		if(isset($info_array['current'])){
			$this->current = $info_array['current'];
		}
		
		if(isset($info_array['turn'])){
			$this->turn = $info_array['turn'];
		}
		if(isset($info_array['winner'])){
			$this->winner = $inforArray['winner'];
		}
		if(isset($info_array['user1'])){
			$this->correct_answer = $info_array['user1'];
		}
		if(isset($info_array['user2'])){
			$this->correct_answer = $info_array['user2'];
		}
		if(isset($info_array['game_room_id'])){
			$this->correct_answer = $info_array['game_room_id'];
		}
	}
	//isMyTurn
	
  
  // id GETTER
  public function getId() {
      return $this->id;
  }
  // id SETTER
  public function setId($id){
	  $this->id = $id;
  }
  
    // password SETTER
  public function setPassword($password){
	  $this->password = $password;
  }
  
  
   // mode GETTER
  public function getMode() {
	  
	//echo "getMODE 에 MODE: " . $this->mode . "<br>";
	return $this->mode ;
  }
  
  // mode SETTER
  public function setMode($mode) {
	$this->mode = $mode;
  }
  
  // gaming_status GETTER
  public function getGamingStatus(){
		if($this->getMode() === 'solo_game') {
			if (implode('', $this->getCorrectAnswer()) === implode('', $this->getCurrent())){
				return 'end';
			} else {
				return 'my_turn';
			}
		} else if($this->getMode() === 'dual_game'){
			return get_gaming_status();
		}	
  }
  
  //gaming_status SETTER
  public function setGamingStatus($gaming_status){
	  $this->gaming_status = $gaming_status;
  }
  
   // answer GETTER
  public function getCorrectAnswer(){
      return $this->correct_answer;
  }
  
  // answer SETTER
  public function setCorrectAnswer($answer) {
      $this->correct_answer = $answer;
  }
  
   // current GETTER
  public function getCurrent() {
      return $this->currentWord;
  }
  
  // current SETTER
  public function setCurrent($current) {
      $this->currentWord = $current;
  }
  
    // wrong GETTER
  public function getWrong() {
      return $this->wrong;
  }
  
  // wrong SETTER
  public function setWrong($wrong) {
      $this->wrong = $wrong;
  }
  
     // turn GETTER
  public function getTurn () {
	return $this->turn;
  }
  
  // turn SETTER
  public function setTurn($turn) {
	$this->turn = $turn;
  }
  
  // Winner GETTER
  public function getWinner() {
      return $this->winner;
	}
	
  // Winner SETTER
  public function setWinner($winner){
	  $this->winner = $winner;
	}
  
  
  //User1 GETTER
  public function getUser1 (){
	  return $this->user1;
  }
  
  //User1 SETTER
  public function setUser1 ($user1){
	  $this->user1 = $user1;
  }
  
  //User2 GETTER
  public function getUser2 (){
	  return $this->user2;
  }
  
  //User1 SETTER
  public function setUser2 ($user1){
	  $this->user2 = $user2;
  }
  
  //RoomId GETTER
   public function getRoomId(){
	   
	  return $this->gameRoomId;
  }
  
  // RoomId SETTER
  public function setRoomId($roomId){
	  $this->gameRoomId = $roomId;
  }
  
	public function start_dual_game(){
		//빈자리가 있는 방 찾기
		$is_room_created = false;
		$conn = get_connection();
		$room_query = sprintf("SELECT game_room_id FROM game_room WHERE user2_id is NULL;");
		$result = mysqli_query ($conn, $room_query);
		if (mysqli_num_rows($result) > 0) {//대기자가 있는 경우
			$row = mysqli_fetch_assoc($result);
			$room = $row['game_room_id']; //방번호			
			// 방이 다차면 첫번째 플레이어가 할 차례
			$user2_query = sprintf ("UPDATE game_room SET user2_id=%d, turn=1 WHERE game_room_id=%d;", get_user_id_from_user_name($this->getId()), $room);
			//맞는 방번호에 user2_id 업데이트
			mysqli_query ($conn, $user2_query);
			$answer_query = sprintf("SELECT answer, current, wrong FROM game_room WHERE game_room_id=%d;", $room);
			$result = mysqli_query ($conn, $answer_query);
			if($result == false) {
				mysqli_error($conn);
			}
			$row = mysqli_fetch_assoc($result);
			$this->setCorrectAnswer(preg_split('//u', $row['answer'], -1, PREG_SPLIT_NO_EMPTY));
			$this->setCurrent(preg_split('//u', $row['current'], -1, PREG_SPLIT_NO_EMPTY));
			$this->setWrong(preg_split('//u', $row['wrong'], -1, PREG_SPLIT_NO_EMPTY));
			$this->setRoomId($room);
			//echo '조인';
			$is_room_created = false;
		} else {//없는경우 방생성
			 //단어 생성하기
		$this->setCorrectAnswer(preg_split('//u', $this->getRandomWord(), -1, PREG_SPLIT_NO_EMPTY)); 
		$current = $this->create_empty_array (count($this->getCorrectAnswer()));
		$this->setCurrent($current);
		$this->setWrong(array());			
			
			$answer = implode('',$this->getCorrectAnswer()); //answer 변수 지정
			$current = implode('',$this->getCurrent()); //current 변수지정
			$wrong = implode('',$this->getWrong());
			$create_query = sprintf("INSERT INTO game_room (answer, current, wrong, user1_id) VALUES ('%s', '%s', '%s', %d);", $answer, $current, $wrong, get_user_id_from_user_name($_SESSION['id']));
			//game_room테이블에 answer, current, user1_id INSERT
			mysqli_query($conn, $create_query);
			$this->setRoomId(mysqli_insert_id($conn)); //나중에 setRoomId 만들어야함
			//echo '방생성';
			$is_room_created = true;
		}
		mysqli_close($conn);
		
		return $is_room_created;
	}
  
	public function play($user_input){
		$result = $this->checkCharacter($this->getCorrectAnswer(), 
				$user_input, $this->getCurrent(), $this->getWrong());
		if($this->getMode() === 'solo_game'){
			$this->setCurrent($result[0]);
			$this->setWrong($result[1]);
		} else {//듀얼게임
			$this->update_current_and_wrong($result[0], $result[1]);
			$this->refresh();
		}		
		if (implode('', $this->getCorrectAnswer()) === implode('', $this->getCurrent())){
			$this->win_game();
		}
	}

	public function refresh (){
	  if($this->getMode() === 'solo_game'){//솔로게임이면
		  
		  return;
	  }else {//듀얼게임이면
			if(isset($this->gameRoomId)){
			
			$select_query = sprintf('SELECT answer, current, wrong, turn, winner, user1_id, user2_id from hangman.game_room where game_room_id = %d',$this->gameRoomId);
			$conn = get_connection();
			$result = mysqli_query($conn,$select_query);
			
			if($row = mysqli_fetch_assoc($result)){
				$this->correct_answer = preg_split('//u', $row['answer'], -1, PREG_SPLIT_NO_EMPTY);
				$this->currentWord = preg_split('//u', $row['current'], -1, PREG_SPLIT_NO_EMPTY);
				//echo "Row Current : " . $row['current'] . "<br>";
				$this->wrong = preg_split('//u', $row['wrong'], -1, PREG_SPLIT_NO_EMPTY);
				$this->turn = $row['turn'];
				$this->winner = $row['winner'];
				$this->user1_id = $row['user1_id'];
				$this->user2_id = $row['user2_id'];
			}else{
				die ('리프레시 할때 데이터 못 가지고 옴.');
			}
		  }else{
			  die ('gameRoomId 없음! refresh Error');
		  }
		}
	}
/*
	getMode();
	getCorrectAnswer();
	
	getCurrent();
	getWrong();
	getTurn();
	getWinner();
	user1
	user2
*/
	function getRandomWord() {
	
		$conn = get_connection ();
		$get_word_query = "SELECT word FROM vocabulary ORDER BY rand() LIMIT 1"; //랜덤으로 단어 하나 불러오는 query.
		$data = mysqli_query ($conn, $get_word_query);
		
		if ($data === false) {
			echo mysqli_error($conn);
			echo "vocabulary DB 에서 데이터를 불러올 수 없습니다.";
			die;
		
		}	
		
		$row = mysqli_fetch_assoc($data);
		$word = strtolower($row['word']);
		
		mysqli_close($conn);
		return $word;
	}
	
	function checkCharacter($ans_array, $character, $current, $wrong){ 
		$match_found = false;
		$char_check_result[0] = $current;
		$char_check_result[1] = $wrong;
		foreach($ans_array as $key => $value){
			if($value === $character){
				$current[$key] = $character;
				$char_check_result[0] = $current;
				$match_found = true;
			}
			
		}
		
		if($match_found === false){
			$char_check_result[1][] = $character;
			//턴변경
			$this->change_turn();
		}
		
		return $char_check_result;
	}
	
	function update_current_and_wrong($current, $wrong) {
		$conn = get_connection();
		$update_query = sprintf ("UPDATE game_room SET current='%s', wrong='%s' WHERE game_room_id=%d;", implode('',$current), implode('',$wrong), get_my_game_room_id());
		mysqli_query ($conn, $update_query);
	}
	
	function create_empty_array ($length){ 
		for($i = 0; $i < $length; $i++){

			$current[] = '_';
		}
		return $current;
	}
	
	function change_turn() {
		$conn = get_connection();
		$select_query = sprintf ('SELECT turn FROM hangman.game_room WHERE game_room_id= %d', get_my_game_room_id());
		$result = mysqli_query($conn, $select_query);
		$row = mysqli_fetch_assoc($result);
		$turn = intval($row['turn']);
		if ($turn === 1){//턴이 1이면 2로 변경
			$update_query = sprintf ("UPDATE game_room SET turn=2 WHERE game_room_id=%d;", get_my_game_room_id());			
			mysqli_query ($conn, $update_query);
		} else {
			$update_query = sprintf ("UPDATE game_room SET turn=1 WHERE game_room_id=%d;", get_my_game_room_id());			
			mysqli_query ($conn, $update_query);
		}
		mysqli_close($conn);
	}
	
	function win_game(){
		if ($this->getMode() === 'solo_game'){
			return;
		} else {
			$conn = get_connection();
			$update_query = sprintf ("UPDATE game_room SET winner=%d WHERE game_room_id=%d;", get_my_position(), get_my_game_room_id());
			mysqli_query ($conn, $update_query);
			insert_stats();
		}
	}
	
	function getCurrentAndWrong() {
		$conn = get_connection();
		$select_query = sprintf ('SELECT current, wrong FROM hangman.game_room WHERE game_room_id= %d', get_my_game_room_id());
		$result = mysqli_query($conn, $select_query);
		$row = mysqli_fetch_assoc($result);
	
		return array(preg_split('//u', $row['current'], -1, PREG_SPLIT_NO_EMPTY), 
							preg_split('//u', $row['wrong'], -1, PREG_SPLIT_NO_EMPTY));		
	}
	
	function startSoloGame(){
		$this->setCorrectAnswer(preg_split('//u', $this->getRandomWord(), -1, PREG_SPLIT_NO_EMPTY)); 
		$current = $this->create_empty_array (count($this->getCorrectAnswer()));
		$this->setCurrent($current);
		$this->setWrong(array());
		
	}
}
?>