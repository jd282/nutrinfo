<?php
$firstname = null;
$lastname = null;
$email = null; 
$password = null;

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
        $date = date('Y-m-d'); 
        
    
    	$dbconn = pg_connect("host=localhost dbname=nutrinfo user=vagrant password=dbpasswd")
    		or die('Could not connect: ' . pg_last_error());
    		
    	$check_email = "SELECT * FROM Users WHERE user_email = $1";
    	$check_result = pg_prepare($dbconn, "my_q", $check_email);
    	$check_result = pg_execute($dbconn, "my_q", array($email));
    	$myrow = pg_fetch_assoc($check_result);
    	print $myrow; 
    	
    	if(!empty($myrow) || is_null($myrow)){
    		header('Location: registration_error.php');
    		exit(); 
    	}
    	
    	$query = "INSERT INTO Users(user_id, user_email, user_password, user_firstname, user_lastname, user_registered) VALUES(DEFAULT, $1, $2, $3, $4, $5)";
   		$result = pg_prepare($dbconn, "my_query", $query);
   		$result = pg_execute($dbconn, "my_query", array($email,$password,$firstname,$lastname,$date));
   		
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
</head>
<body>
<div id="page">
    <!-- [banner] -->
    <header id="banner">
    	<hgroup>
    		<h1>Registration</h1>
    	</hgroup>
    </header>  
    <h2> This email already exists!! </h2>  
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