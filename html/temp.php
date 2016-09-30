			
<?php

 function print_ui() {
	global $infoDto;
	global $current;
	?>
		<div class="panel_box">
			<?php if ($infoDto->getGamingStatus() === 'my_turn') { ?>
			<span id="timer" class="timer">Timer not started yet</span>
			<?php } ?>
			<div class="user_output">
				<ul>
				<?php 
					foreach ($current as $key => $value) {
						echo '<li>';
						echo $value;
						echo '</li>';
					}
				?>
				</ul>
			</div>
			<div class="user_input">
				<form id = "form" action = "change_mode.php" method = "post">
					<ul>
					<?php

						if ($infoDto->getGamingStatus() === 'my_turn') {
							printf ("<li><input type='text' name='user_input' size='35' autofocus></li> ");
							printf ("<li><input type='submit' value='Entre'></li>");
						} else {
							printf ("<li><input type='text' name='user_input' size='35' autofocus='true' disabled></li> ");
							printf ("<li><input type='submit' value='Entre' disabled></li>");
						}
					?>
					</ul>
				</form>
			</div>
		</div>
	
	<div class="wrong_input">
		<ul>
			<li>틀린답</li>
			<?php		
			echo '<li>';			
				echo implode(' ', $infoDto->getWrong());
			echo '</li>';
			?>
		</ul>
	</div>
<?php	
 }	