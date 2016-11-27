<?php
/* basic php authentication by Jonathan Schnittger 2013 */
$firstname = null;
$lastname = null;
$email = null; 
$password = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    require_once('database.php');
    
    if(!empty($_POST["firstname"]) && !empty($_POST["lastname"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $email = $_POST["email"];
        $password = $_POST["password"];
    
        $query = $connection->prepare("SELECT `user_id` FROM `users` WHERE `user_email` = ? and `user_password` = PASSWORD(?)");
        $query->bind_param("ss", $username, $password);
        $query->execute();
        $query->bind_result($userid);
        $query->fetch();
        $query->close();
        
        if(!empty($userid)) {
            session_start();
            $session_key = session_id();
            
            $query = $connection->prepare("INSERT INTO `sessions` ( `user_id`, `session_key`, `session_address`, `session_useragent`, `session_expires`) VALUES ( ?, ?, ?, ?, DATE_ADD(NOW(),INTERVAL 1 HOUR) );");
            $query->bind_param("isss", $userid, $session_key, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] );
            $query->execute();
            $query->close();
            
            header('Location: index.php');
        }
        else {
            header('Location: login.php');
        }
        
    } else {
        header('Location: login.php');
    }
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Creating a nutrition web application - registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div id="page">
    <!-- [banner] -->
    <header id="banner">
    	<hgroup>
    		<h1>Registration</h1>
    	</hgroup>
    </header>    
    <section id="content">
    	<form id='register' action='register.php' method='post' accept-charset='UTF-8'>
			<fieldset>
				<input type='hidden' name='submitted' id='submitted' value='1'/>
				<label for='name' >First Name: </label>
				<input type='text' name='firstname' id='firstname' maxlength="50" required/>
				<br />
				<label for='name' >Last Name: </label>
				<input type='text' name='lastname' id='lastname' maxlength="50" required/>
				<br />
				<label for='email' >Email Address:</label>
				<input type='text' name='email' id='email' maxlength="50" required/>
 				<br />
				<label for='password' >Password:</label>
				<input type='password' name='password' id='password' maxlength="50" required/>
				<br />
				<input type='submit' name='Submit' value='Submit' />
 
			</fieldset>
		</form>
    </section>
    <!-- [/content] -->
    
</div>
<!-- [/page] -->
</body>
</html>
<?php } ?>