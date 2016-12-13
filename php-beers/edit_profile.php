<?php
	session_start(); 
	$userid = $_SESSION['user_id']; 
	$default_firstname = null;
	$default_lastname = null;  
	$default_email = null;  
	
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
    	$update = "UPDATE Users SET user_email=?, user_firstname=?, user_lastname=? WHERE user_id=?" ;
    	$update_result = $dbh->prepare($update);
    	$update_result->execute(array($email, $firstname, $lastname, $userid));
    	
    	//Check if user already has existing goals in goals table 
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
    		$goal_result = $dbh->prepare($goal_update); 
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

<body>
    <nav id='topbar'></nav>
    <div class='container'>
        <!-- [banner] -->
        <header id="banner">
        	<hgroup>
        		<h1>Edit Profile</h1>
        	</hgroup>
        </header>    
        <section id="content">
        	<form id='register' method='post' accept-charset='UTF-8' action='edit_profile.php'>
    			<fieldset>
    				<input type='hidden' name='submitted' id='submitted' value='1'/>
    				<h3>Personal Information</h3>
    				<label for='name' >First Name: </label>
    				<input type='text' name='firstname' id='firstname' maxlength="50" value='<?php echo $default_firstname; ?>' />
    				<br />
    				<label for='name' >Last Name: </label>
    				<input type='text' name='lastname' id='lastname' maxlength="50" value='<?php echo $default_lastname; ?>' />
    				<br />
    				<label for='email' >Email Address:</label>
    				<input type='text' name='email' id='email' maxlength="50" value='<?php echo $default_email; ?>'/>
     				<br />
     				<h3>Goals </h3>
    				<label for='minCals' >Minimum Calories:</label>
    				<input type='text' name='minCals' id='minCals' maxlength="5" value='<?php echo $default_minCals; ?>'/>
    				<br />
    				<label for='maxCals' >Maximum Calories:</label>
    				<input type='text' name='maxCals' id='maxCals' maxlength="5" value='<?php echo $default_maxCals; ?>'/>
    				<br />
    				<label for='minFat' >Minimum Fat:</label>
    				<input type='text' name='minFat' id='minFat' maxlength="5" value='<?php echo $default_minFat; ?>'/>
    				<br />
    				<label for='maxFat' >Maximum Fat:</label>
    				<input type='text' name='maxFat' id='maxFat' maxlength="5" value='<?php echo $default_maxFat; ?>' />
    				<br />
    				<label for='minFat' >Minimum Sugar:</label>
    				<input type='text' name='minSug' id='minSug' maxlength="5" value='<?php echo $default_minSug; ?>'/>
    				<br />
    				<label for='maxFat' >Maximum Sugar:</label>
    				<input type='text' name='maxSug' id='maxSug' maxlength="5" value='<?php echo $default_maxSug; ?>' />
    				<br />
    				<label for='minFat' >Minimum Sodium:</label>
    				<input type='text' name='minSodium' id='minSodium' maxlength="5" value='<?php echo $default_minSodium; ?>'/>
    				<br />
    				<label for='maxFat' >Maximum Sodium:</label>
    				<input type='text' name='maxSodium' id='maxSodium' maxlength="5" value='<?php echo $default_maxSodium; ?>' />
    				<br />
    				<label for='minFat' >Minimum Protein:</label>
    				<input type='text' name='minFat' id='minFat' maxlength="5" value='<?php echo $default_minFat; ?>'/>
    				<br />
    				<label for='maxFat' >Maximum Protein:</label>
    				<input type='text' name='maxProtein' id='maxProtein' maxlength="5" value='<?php echo $default_maxProtein; ?>' />
    				<br />
    				<input type='submit' name='Submit' value='Update' />
     
    			</fieldset>
    		</form>
        </section>
        <!-- [/content] -->
        
    </div>
    
    <a href='index.php'>Cancel</a>

</body>
