<?php
/* basic php authentication by Jonathan Schnittger 2013 */
$firstname = null;
$lastname = null;
$email = null; 
$password = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	/*
      try {
    // Including connection info (including database password) from outside
    // the public HTML directory means it is not exposed by the web server,
    // so it is safer than putting it directly in php code:
    include("/etc/php5/pdo-mine.php");
    $dbh = dbconnect();
  } catch (PDOException $e) {
    print "Error connecting to the database: " . $e->getMessage() . "<br/>";
    die();
  }

    
    if(!empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        $sql = "SELECT user_id FROM Users WHERE user_email= '" . $email . "' and user_password='" . $password . "'"; 
   		$st = $dbh->query($sql);
    	$myrow = $st->fetch();    
    	
		$dbconn = pg_connect("host=localhost dbname=nutrinfo user=vagrant password=dbpasswd")
    		or die('Could not connect: ' . pg_last_error());
        $result = pg_prepare($dbconn, "my_query", "SELECT user_id,user_firstname FROM Users WHERE user_email= $1 and user_password=$2");
		$result = pg_execute($dbconn, "my_query", array($email,$password));
		$myrow = pg_fetch_assoc($result);
        
        if(!empty($myrow)) {
            session_start();
            $userid = $myrow['user_id'];
           	$session_key = session_id();
           	$firstname = $myrow['user_firstname'];
            $_SESSION['user_id'] = $userid;
            $_SESSION['session_key'] = $session_key;
            $_SESSION['user_firstname'] = $firstname; 
            
    */
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