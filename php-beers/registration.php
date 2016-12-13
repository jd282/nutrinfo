<?php
$firstname = null;
$lastname = null;
$email = null; 
$password = null;
$dob = null; 
$sex = null; 
$height = null; 
$weight = null; 

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
        $sex = $_POST["sex"]; 
        $height = $_POST["height"]; 
		$weight = $_POST["weight"]; 
        $date = date('Y-m-d'); 
        
    	$dbconn = pg_connect("host=localhost dbname=nutrinfo user=vagrant password=dbpasswd")
    		or die('Could not connect: ' . pg_last_error());
    	
    	//check if email is already registered and if so go to registration_error.php 	
    	$check_email = "SELECT * FROM Users WHERE user_email = $1";
    	$check_result = pg_prepare($dbconn, "my_q", $check_email);
    	$check_result = pg_execute($dbconn, "my_q", array($email));
    	$myrow = pg_fetch_assoc($check_result);
    	
    	if(!empty($myrow) || is_null($myrow)){
     		header('Location: registration_error.php');
    		exit(); 
    	}
    	
    	//insert registration info into the Users table     	
    	$query = "INSERT INTO Users(user_id, user_email, user_password, user_firstname, user_lastname, user_dob, user_registered, user_sex, user_height, user_weight) VALUES(DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
   		$result = $dbh->prepare($query);
   		$result->execute(array($email, $password, $firstname, $lastname, $dob, $date, $sex, $height, $weight));
   		
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
    
        <div class='row'>
        	<form class='col s12' id='register' method='post' accept-charset='UTF-8'>
    			<!-- <fieldset>
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
    			</fieldset> -->

                <div class='row'>
                    <div class='input-field col s6'>
                        <input placeholder='First Name' name='firstname' id='firstname' type='text' maxlength='50' required/>
                        <label for='firstname'> First Name </label>
                    </div>
                    <div class='input-field col s6'>
                        <input placeholder='Last Name' name='lastname' id='lastname' type='text' maxlength='50' required/>
                        <label for='lastname'>  Last Name </label>
                    </div>
                </div>

                <div class='row'>
                    <div class='input-field col s2'>
                        <input placeholder='MM/DD/YYYY' id='dob' name='dob' type='date' class='datepicker' required/>
                        <!-- <label for='dob'>Date of Birth (MM/DD/YYYY)</label> -->
                    </div>
                </div>

                <div class='row'>
                    <div class='input-field col s12'>
                        <input placeholder='E-Mail' name='email' id='email' maxlength='50' type='email' class='validate' required/>
                        <label for='email' data-error='Please Input a Valid Email'> E-Mail Address </label>
                    </div>
                </div>

                <div class='row'>
                    <div class='input-field col s12'>
                        <input placeholder='Password' name='password' id='password' type='password' maxlength='50' required/>
                        <label for='password'> Password </label>
                    </div>
                </div>
                
                <div class='row'>
                    <!-- <div class='input-field col s12'>
                        <input placeholder='Sex' name='sex' id='sex' type='text' maxlength='50' required/>
                        <label for='sex'> Sex </label>
                    </div> -->
                    <label>Sex</label>
                    <select class='browser-default' required id='sex' name='sex'>
                        <option value='' disabeled selected>Please Choose Your Sex</option>
                        <option value='M'>Male</option>
                        <option value='F'>Female</option>
                    </select>
                </div>
                <!-- <div class='row'>
                    <div class='input-field col s12'>
                        <input placeholder='Height' name='height' id='height' type='text' maxlength='50' required/>
                        <label for='height'> Height </label>
                    </div>
                </div>
                
                <div class='row'>
                    <div class='input-field col s12'>
                        <input placeholder='Weight' name='weight' id='weight' type='text' maxlength='50' required/>
                        <label for='weight'> Weight </label>
                    </div>
                </div> -->

                <div class='row'>
                    <div class='input-field col s12'>
                        <p> Please slide to your height (in inches) </p>
                        <p class="range-field">
                            <input type="range" name='height' id="height" min="0" max="96" value='-1' required />
                        </p>
                    </div>
                </div>

                <div class='row'>
                    <div class='input-field col s12'>
                        <p> Please slide to your weight (in pounds) </p>
                        <p class="range-field">
                            <input type="range" name='weight' id="weight" min="12" max="96" value='' required />
                        </p>
                    </div>
                </div>

    			<br/>
    			<input type='submit' name='Submit' value='Submit' />
    		</form>
        </div>
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