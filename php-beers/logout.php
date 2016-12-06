<?php  
	session_unset();
	session_destroy();
	header("Location: login.php");
	exit; 
	
?>

<html>
	<body>
		<p>
		You've successfullly logged out!
		</p>
	</body>
</html>
