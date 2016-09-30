<?php

	require_once '../includes/session.php'; 
	
	start_session();
	try_to_logout();
	destroy_session();

	
	header('Location: index.php');
		
	
?>