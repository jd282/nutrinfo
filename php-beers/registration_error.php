<?php
$firstname = null;
$lastname = null;
$email = null; 
$password = null;
$dob = null; 
$error; 

session_start(); 
$_SESSION['error'] = false; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
    include("/etc/php5/pdo-mine.php");
    $dbh = dbconnect();
 	 } catch (PDOException $e) {
    print "Error connecting to the database: " . $e->getMessage() . "<br/>";
    die();
  	}
  	
    if(!empty($_POST["firstname"]) && !empty($_POST["lastname"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $dob = $_POST["dob"]; 
        $date = date('Y-m-d'); 
        
        //$_SESSION['error'] = false; 
    	$error = $_POST['error']; 
    	
    	$dbconn = pg_connect("host=localhost dbname=nutrinfo user=vagrant password=dbpasswd")
    		or die('Could not connect: ' . pg_last_error());
    	
    	//check if email is already registered and if so go to registration_error.php 	
    	$check_email = "SELECT * FROM Users WHERE user_email = $1";
    	$check_result = pg_prepare($dbconn, "my_q", $check_email);
    	$check_result = pg_execute($dbconn, "my_q", array($email));
    	$myrow = pg_fetch_assoc($check_result);
    	
    	if(!empty($myrow) || is_null($myrow)){
    		//$_SESSION['error'] = true; 
    		$error = true; 
     		header('Location: registration_error.php');
    		exit(); 
    	}
    	
    	//insert registration info into the Users table 
    	$query = "INSERT INTO Users(user_id, user_email, user_password, user_firstname, user_lastname, user_dob, user_registered) VALUES(DEFAULT, $1, $2, $3, $4, $5, $6)";
   		$result = pg_prepare($dbconn, "my_query", $query);
   		$result = pg_execute($dbconn, "my_query", array($email,$password,$firstname,$lastname,$dob, $date));
   		
   		header('Location: login.php');  
        
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
    <!-- CSS  -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="./materialize/css/icon" rel="stylesheet">
    <link href="./materialize/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection">
    <link href="./materialize/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
    <style>img.chromoji { width:1em !important; height:1em !important; }.chromoji-font, #chromoji-font { font-size:1em !important; }</style>
</head>
<body>
    <nav id='topbar'></nav>
    <div class='container'>
        <!-- [banner] -->
        <header id="banner">
        	<hgroup>
        		<h1>Registration</h1>
        	</hgroup>
        </header>  
        <h3> This email already exists!!</h3>  
        <?php 
        	//if ($_SESSION['error'] == true) {
        	if($error == true){
        		//print $_SESSION['error']; 
        		echo "<h3 style='color:red'>THIS EMAIL IS ALREADY REGISTERED!</h3>"; 
        		$error = false; 
        		//$_SESSION['error'] = false; 
        	}
        ?>
        <section id="content">
        	<form id='register' method='post' accept-charset='UTF-8'>
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
    				<label for='dob' >DOB:</label>
    				<input type='text' name='dob' id='dob' maxlength="50" required/>
    			</fieldset>
    			<br/>
    			<input type='submit' name='Submit' value='Submit' />
    		</form>
        </section>
        <!-- [/content] -->
        
    </div>
    <script src="bars.js"></script>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="./materialize/js/materialize.js"></script>
    <script src="./materialize/js/materialize.min.js"></script>
    <script src="./materialize/js/init.js"></script>
    <!-- [/page] -->
</body>
</html>
<?php } ?>