<?php
/* basic php authentication by Jonathan Schnittger 2013 */
$username = null;
$password = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    /*require_once('database.php'); */
    
    if(!empty($_POST["email"]) && !empty($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        /*
        $sql = "SELECT user_id FROM Users WHERE user_email= '" . $email . "' and user_password='" . $password . "'"; 
   		$st = $dbh->query($sql);
    	$myrow = $st->fetch();    
    	*/
		$dbconn = pg_connect("host=localhost dbname=nutrinfo user=vagrant password=dbpasswd")
    		or die('Could not connect: ' . pg_last_error());
        $result = pg_prepare($dbconn, "my_query", "SELECT user_id FROM Users WHERE user_email= $1 and user_password=$2");
		$result = pg_execute($dbconn, "my_query", array($email,$password));
		$myrow = pg_fetch_assoc($result);
        
   		 /*
        $query = $dbh->prepare("SELECT `user_id` FROM `users` WHERE `user_email` = ? and `user_password` = PASSWORD(?)");
        $query->bind_param("ss", $email, $password);
        $query->execute();
        $query->bind_result($userid);
        $query->fetch();
        $query->close();
        */
        
        if(!empty($myrow)) {
            session_start();
            $userid = $myrow['user_id'];

           	$session_key = session_id();
            
            header('Location: index.php');
        }
        else {
        	
            header('Location: login_error.php');
        }
        
    } else {
        header('Location: login_error.php');
    }
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Nutrinfo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div id="page">
    <!-- [banner] -->
    <p>
    	INVALID EMAIL AND/OR PASSWORD
	</p>
    <header id="banner">
        <hgroup>
            <h1>Login</h1>
        </hgroup>        
    </header>
    <!-- [content] -->
    <section id="content">
        <form id="login" method="post">
            <label for="email">Email:</label>
            <input id="email" name="email" type="text" required>
            <label for="password">Password:</label>
            <input id="password" name="password" type="password" required>                    
            <br />
            <br />
            <input type="submit" value="Login">
        </form>
    </section>
    <p>
    	<a href="registration.php">Sign-up!</a>
    </p>
    <!-- [/content] -->
    
</div>
<!-- [/page] -->
</body>
</html>
<?php } ?>