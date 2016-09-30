<?php
	require_once '../includes/session.php';
	require_once 'game_function.php';
	require_once './SessionInfo.php';
	start_session();
	$infoDto = $_SESSION['info_dto'];
	if(isset($_POST['mode'])){ 
		//var_dump($infoDto);
		$infoDto->setMode($_POST['mode']);
		
		if ($infoDto->getMode() === 'solo_game') { // solo_game 시작을 클릭했을때

			$infoDto->startSoloGame();
			header("Location: index.php");
		} else if ($infoDto->getMode() === 'lobby') { //리셋했을때
			
			header("Location: index.php");
		}else if($infoDto->getMode() === 'dual_game'){ // dual_game 클릭 했을때 
		
			$infoDto -> start_dual_game();
				//echo '111';
		
			header("Location: index.php");
		}
	}else if(isset($_POST['user_input'])){
		$infoDto->refresh();
		$user_input = $_POST['user_input'];
		$infoDto->play($user_input);
		header('Location: index.php');
	}else{
		die('Change_mode에서 error 남.');
	}
	
	
?>
