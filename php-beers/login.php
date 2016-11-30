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
    <title>Log In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="./materialize/css/icon" rel="stylesheet">
    <link href="./materialize/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="./materialize/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <style>img.chromoji { width:1em !important; height:1em !important; }.chromoji-font, #chromoji-font { font-size:1em !important; }</style>
</head>
<body>

    <nav id='topbar'></nav>

    <div class="container">
        <!-- [banner] -->
        <header id="banner">
            <hgroup>
                <h2>Login</h2>
            </hgroup>        
        </header>
        <!-- [content] -->
        <div id='form'>
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
        </div>
        <br/>
        <button type='button' style='color:black'>
        	<a href="registration.php">Sign-up!</a>
        </button>
        <!-- [/content] -->
        
    </div>

    <nav id='bottombar' style='bottom:0; position:absolute;'></nav>

    <!--  Scripts-->
    <!-- // <script src="./materialize/js/jquery-2.1.1.min.js"></script> -->
    <script src="bars.js"></script>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="./materialize/js/materialize.js"></script>
    <script src="./materialize/js/materialize.min.js"></script>
    <script src="./materialize/js/init.js"></script>
<!-- [/page] -->
</body>
</html>
<?php } ?>