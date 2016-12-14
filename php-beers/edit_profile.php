<?php
	session_start(); 
	$userid = $_SESSION['user_id']; 
	$default_firstname = null;
	$default_lastname = null;  
	$default_email = null;  
	$default_height = null; 
	$default_weight = null; 
	
	$default_minCals = null; 
	$default_maxCals = null; 
	$default_minFat = null; 
	$default_maxFat = null; 
	$default_minSug = null; 
	$default_maxSug = null;
	$default_minSodium = null; 
	$default_maxSodium = null;
	$default_minProtein = null; 
	$default_maxProtein = null;	

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
  
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  	try{
  		$firstname = $_POST["firstname"];
  		$lastname = $_POST["lastname"];
  		$email = $_POST["email"];
        $height = $_POST["height"]; 
		$weight = $_POST["weight"]; 
		$minCals = $_POST["minCals"];
    	$maxCals = $_POST["maxCals"];
    	$minFat = $_POST["minFat"];
    	$maxFat = $_POST["maxFat"];
    	$minSug = $_POST["minSug"];
    	$maxSug = $_POST["maxSug"];
    	$minSodium = $_POST["minSodium"];
    	$maxSodium = $_POST["maxSodium"];
    	$minProtein = $_POST["minProtein"];
    	$maxProtein = $_POST["maxProtein"];
    	
    	//update user table
    	$update = "UPDATE Users SET user_email=?, user_firstname=?, user_lastname=?, user_weight=?, user_height=? WHERE user_id=?" ;
    	$update_result = $dbh->prepare($update);
    	$update_result->execute(array($email, $firstname, $lastname, $weight, $height, $userid));
    	
    	//Check if user already has existing goals in goals table 
    	$goals_query = "SELECT * FROM Goals Where goals_userid=" . $userid;
    	$g_query = $dbh->query($goals_query); 
		$g_row = $g_query->fetch(); 
		if(!empty($g_row)){
			//update goals table if user already in goals table
    		$goal_update = "UPDATE Goals SET minCals=?, maxCals=?, minFat=?, maxfat=?, minSug=?, maxSug=?, minSodium=?, maxSodium=?, minProtein=?, maxProtein=? WHERE goals_userid=?";
    		$goal_result = $dbh->prepare($goal_update); 
    		$goal_result->execute(array($minCals, $maxCals, $minFat, $maxFat, $minSug, $maxSug, $minSodium, $maxSodium, $minProtein, $maxProtein, $userid));
		}
		else{
    		//if user not in goals table, then insert into goals table
    		$goal_insert = "INSERT INTO Goals(goals_userid, maxCals, maxFat, maxSug, maxSodium, maxProtein, minCals, minFat, minSug, minSodium, minProtein) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    		$goal_result = $dbh->prepare($goal_insert); 
    		$goal_result->execute(array($userid, $maxCals, $maxFat, $maxSug, $maxSodium, $maxProtein, $minCals, $minFat, $minSug, $minSodium, $minProtein));
    	}

	}catch (PDOException $e) {
    	print "Database error: " . $e->getMessage() . "<br/>";
   	 	die();
  	}
  }
  
  
  try{
  	//set default values in the HTML form to what already exists in the database
  	$query = "SELECT * FROM Users WHERE user_id=" . $userid; 
	$q = $dbh->query($query); 
	$user_info = $q->fetch(); 
	
	$default_firstname = $user_info['user_firstname'];
	$default_lastname = $user_info['user_lastname'];  
	$default_email = $user_info['user_email'];  
	$default_height = $user_info['user_height'];
	$default_weight = $user_info['user_weight']; 
	
	
	$query = "SELECT minCals,maxCals, minFat, maxFat, minSug, maxSug, minSodium, maxSodium, minProtein, maxProtein FROM Goals WHERE goals_userid=" . $userid; 
	$q = $dbh->query($query); 
	$goal_info = $q->fetch(); 
  	
  	$default_minCals = $goal_info[0]; 
	$default_maxCals = $goal_info[1];
	$default_minFat = $goal_info[2]; 
	$default_maxFat = $goal_info[3]; 
	$default_minSug = $goal_info[4]; 
	$default_maxSug = $goal_info[5]; 
	$default_minSodium = $goal_info[6]; 
	$default_maxSodium = $goal_info[7]; 
	$default_minProtein = $goal_info[8]; 
	$default_maxProtein = $goal_info[9]; 

  }catch (PDOException $e) {
    print "Database error: " . $e->getMessage() . "<br/>";
    die();
  }

?>

<!DOCTYPE html>
<!-- saved from url=(0065)http://materializecss.com/templates/starter-template/preview.html -->
<html lang='en'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
  
  <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1.0'>
  <title>Edit Profile</title>

  <link href='./Media/dukedining.png' rel='icon'>

  <!-- CSS  -->
  <link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet'>
  <link href='./materialize/css/icon' rel='stylesheet'>
  <link href='./materialize/css/materialize.min.css' type='text/css' rel='stylesheet' media='screen,projection'>
  <link href='./materialize/css/materialize.css' type='text/css' rel='stylesheet' media='screen,projection'>
  <style>img.chromoji { width:1em !important; height:1em !important; }.chromoji-font, #chromoji-font { font-size:1em !important; }</style>
</head>

<body>
    <nav id='topbar'></nav>
    <div class='container'>
        <!-- [banner] -->
        <header id="banner">
        	<hgroup>
        		<h1>Edit Profile</h1>
        	</hgroup>
        </header>    
        <div class="row">
        	<form class='col s12' id='register' method='post' accept-charset='UTF-8' action='edit_profile.php'>
    			
    				<input type='hidden' name='submitted' id='submitted' value='1'/>

    				<h3>Personal Information</h3>
            <div class='row'>
              <div class='input-field col s6'>
        				<label for='name' >First Name: </label>
        				<input type='text' name='firstname' id='firstname' maxlength="50" value='<?php echo $default_firstname; ?>' />
              </div>

              <div class='input-field col s6'>
        				<label for='name' >Last Name: </label>
        				<input type='text' name='lastname' id='lastname' maxlength="50" value='<?php echo $default_lastname; ?>' />
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s6'>
        				<label for='email' >Email Address:</label>
        				<input type='email' data-error='Please Input a Valid Email' name='email' id='email' maxlength="50" value='<?php echo $default_email; ?>'/>
              </div>
            </div>
            
            <div class='row'>
              <div class='input-field col s12'>
                <p> Please slide to your height (in inches) </p>
                <p class="range-field">
                  <input type="range" name ="height" id="height" min="0" max="96" value='<?php echo $default_height; ?>'required/>
                </p>
              </div>
            </div>

     				<!-- <label for='weight' >Weight:</label>
    				<input type='text' name='weight' id='weight' maxlength="50" value='<?php echo $default_weight; ?>'/>
     				<br /> -->

            <div class='row'>
              <div class='input-field col s12'>
                <p> Please slide to your weight (in pounds) </p>
                <p class="range-field">
                  <input type="range" name="weight" id="weight" min="50" max="500" value='<?php echo $default_weight; ?>'required/>
                </p>
              </div>
            </div>
			<br/>
     				<h3>Goals </h3>

            <div class='row'>
              <div class='input-field col s2'>
        				<label for='minCals' >Minimum Calories:</label>
        				<input type='number' name='minCals' id='minCals' min='0' value='<?php echo $default_minCals; ?>' required/>
              </div>
              <div class='input-field col s2'>
        				<label for='maxCals' >Maximum Calories:</label>
        				<input type='number' name='maxCals' id='maxCals' maxlength="5" value='<?php echo $default_maxCals; ?>'required/>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s2'>
        				<label for='minFat' >Minimum Fat:</label>
        				<input type='number' name='minFat' id='minFat' min='0' value='<?php echo $default_minFat; ?>'required/>
              </div>
              <div class='input-field col s2'>
        				<label for='maxFat' >Maximum Fat:</label>
        				<input type='number' name='maxFat' id='maxFat' value='<?php echo $default_maxFat; ?>' required/>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s2'>
        				<label for='minSug' >Minimum Sugar:</label>
        				<input type='number' name='minSug' id='minSug' min='0' value='<?php echo $default_minSug; ?>'required/>
              </div>
              <div class='input-field col s2'>
        				<label for='maxSug' >Maximum Sugar:</label>
        				<input type='number' name='maxSug' id='maxSug' value='<?php echo $default_maxSug; ?>' required/>
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s2'>
        				<label for='minSodium' >Minimum Sodium:</label>
        				<input type='number' name='minSodium' id='minSodium' min='0' value='<?php echo $default_minSodium; ?>'required/>
              </div>
              <div class='input-field col s2'>
        				<label for='maxSodium' >Maximum Sodium:</label>
        				<input type='number' name='maxSodium' id='maxSodium'  value='<?php echo $default_maxSodium; ?>'required />
              </div>
            </div>

            <div class='row'>
              <div class='input-field col s2'>
        				<label for='minProtein' >Minimum Protein:</label>
        				<input type='number' name='minProtein' id='minProtein' min='0' value='<?php echo $default_minFat; ?>'required/>
              </div>
              <div class='input-field col s2'>
        				<label for='maxProtein' >Maximum Protein:</label>
        				<input type='number' name='maxProtein' id='maxProtein' maxlength="5" value='<?php echo $default_maxProtein; ?>' required/>
              </div>
            </div>

    				<input type='submit' name='Submit' value='Update' />
     
    		
    		</form>
        <!-- [/content] -->
        
    </div>
    
    <a href='index.php'>Cancel</a>

    <!--  Scripts-->
  <script src='loggedInBars.js'></script>
  <!-- // <script src='./materialize/js/jquery-2.1.1.min.js'></script> -->
  <script src='https://code.jquery.com/jquery-2.1.1.min.js'></script>
  <script src='./materialize/js/materialize.js'></script>
  <script src='./materialize/js/materialize.min.js'></script>
  <script src='./materialize/js/init.js'></script>

</body>
