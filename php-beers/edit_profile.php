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
    	
    	//update user table
    	$update = "UPDATE Users SET user_email=?, user_firstname=?, user_lastname=? WHERE user_id=?" ;
    	$update_result = $dbh->prepare($update);
    	$update_result->execute(array($email, $firstname, $lastname, $userid));
    	
    	//update goals table
    	$goal_update = "UPDATE Goals SET minCals=?, maxCals=?, minFat=?, maxfat=? WHERE goals_userid=?";
    	$goal_result = $dbh->prepare($goal_update); 
    	$goal_result->execute(array($minCals, $maxCals, $minFat, $maxFat, $userid));

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
	
	
	$query = "SELECT minCals,maxCals, minFat, maxFat FROM Goals WHERE goals_userid=" . $userid; 
	$q = $dbh->query($query); 
	$goal_info = $q->fetch(); 
  	
  	$default_minCals = $goal_info[0]; 
	$default_maxCals = $goal_info[1]; ; 
	$default_minFat = $goal_info[2]; ; 
	$default_maxFat = $goal_info[3]; ; 

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
    				<input type='submit' name='Submit' value='Update' />
     
    			</fieldset>
    		</form>
        </section>
        <!-- [/content] -->
        
    </div>
    
    <a href='index.php'>Cancel</a>

</body>
