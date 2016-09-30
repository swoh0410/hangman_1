<!DOCTYPE html>

<html>
<?php
	include 'stat_db.php';
	$stat_array = get_data();

?>
<body>
	<h1> USER NAME </h1>
	<table>
		<tr>
		<th>경기수</th><th>승</th><th>무</th><th>패</th><th>승률</th>
		</tr>
		<tr>
			<td><?php echo $stat_array ['total']; ?></td> 
			<td><?php echo $stat_array ['win']; ?></td> 
			<td><?php echo $stat_array ['draw']; ?></td> 
			<td><?php echo $stat_array ['lose']; ?></td> 
			<td><?php echo $stat_array ['win_rate']; ?></td> 
		</tr>
	</table>

</body>

</html>