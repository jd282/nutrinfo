<?php  
	session_unset();
	session_destroy();
	header("Location: home.php");
	
?>

<html>
	<body>
		<p>
		You've successfullly logged out!
		</p>
	</body>
</html>
