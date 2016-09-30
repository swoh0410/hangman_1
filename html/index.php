<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/style.css">
<title>2조 PROJECT - HANGMAN GAME</title>
<?php 
	require_once '../includes/session.php'; 
	require_once 'SessionInfo.php'; 
	start_session();
	require_once 'game_function.php'; 

	if(isset($_SESSION['info_dto'])){
		$infoDto = $_SESSION['info_dto'];
		echo "dto있음";
		echo "mode: " . $infoDto -> getMode();
	}else{
		echo "info Array 생성 <br>";
		$info_array = Array();
		$info_array['mode'] = 'lobby';
		$infoDto = new SessionInfo($info_array);
		$_SESSION['info_dto'] = $infoDto;
		echo "infoDTO 만들었고 모드는: " . $infoDto -> getMode();
	}

?>
</head>
<body>
<?php include 'header.php'; ?>
<div id="wrap">
	<div id="content">							
		<?php 
			if(check_login()){
				$infoDto -> setId($_SESSION['id']);
				$infoDto -> setPassword($_SESSION['password']);
		?>
				<div id="content_l">
					<?php //require 'game_panel.php'?>
						<?php					
							if($infoDto->getMode() === 'lobby'){							
						?>
						<div class="please_start">

							<ul>
								<li>
									<form action="change_mode.php" method="post">
									<input type="hidden" value="solo_game" name="mode">
									<input type="submit" value="솔로 게임">
									</form>
								</li>
								<li>
									<form action="change_mode.php" method="post">
									<input type="hidden" value="dual_game" name="mode">
									<input type="submit" value="듀얼 게임">
									</form>
								</li>
							</ul>

						</div>
						<?php							
							}else if($infoDto->getMode() === 'solo_game'){
								require 'game_panel.php';
							} else if($infoDto->getMode() === 'dual_game'){
								require 'game_panel.php';
							}else {
								echo $infoDto->getMode();
								die ('세션 에러');
							}
						?>
					
				</div>
				<div id="content_r">		
					<div id="login">
						<table>
							<tr>
								<td>
									<?php echo $infoDto->getId(); ?> 님 환영합니다!
								</td>
							</tr>
							
							<tr>
								<td>
									<form action="logout.php" method="get">
										<input type="submit" value="로그아웃">
									</form>
								</td>
							</tr>

						</table>
					</div>
					<div class="user_stat_box">
						<?php
							// stat 데이터 가져오기
							$pk = get_user_id_from_user_name($infoDto->getId());
							require_once 'stat_db.php';
							$row = get_stats($pk);
							echo '총 '.$row['total'].'번 | ';
							echo '승 : '.$row['win'].' | ';
							echo '패 : '.$row['lose'].' | ';
							echo '승률 : '.$row['win_rate'].'%';
						?>
					</div>
				</div>	
		<?php		
			} else {				
		?>		
				<div id="content_r">
					<div id="login">
						<form action="login_process.php" method="post">
							<table>
								<tr>
									<th>ID</th>
									<td><input type="text" name="id"></td>
									<td class="login_btn" rowspan="2"><input type="submit" name="login_btn" value="로그인"></td>
								</tr>
								<tr>
									<th>PW</th>
									<td><input type="password" name="password"></td>
								</tr>
							</table>
						</form>
						<form action="register_page.php" method="get">
							<input type="submit" value="회원가입">
						</form>
					</div>
				</div>
				<div id="content_l">
					<div class="please_login">
						<h1> 로그인을 해주세요! </h1>
					</div>
				</div>
		<?php } ?>		
	</div>
</div>
<?php include 'footer.php'; ?>

</body>
</html>