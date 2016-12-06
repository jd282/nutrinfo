<?php
	session_start(); 
	$userid = $_SESSION['user_id']; 
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
}else{
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
        	<form id='register' method='post' accept-charset='UTF-8'>
    			<fieldset>
    				<input type='hidden' name='submitted' id='submitted' value='1'/>
    				<label for='name' >First Name: </label>
    				<input type='text' name='firstname' id='firstname' maxlength="50" />
    				<br />
    				<label for='name' >Last Name: </label>
    				<input type='text' name='lastname' id='lastname' maxlength="50" />
    				<br />
    				<label for='email' >Email Address:</label>
    				<input type='text' name='email' id='email' maxlength="50" />
     				<br />
     				<h2>Goals </h2>
    				<label for='minCals' >Minimum Calories:</label>
    				<input type='text' name='minCals' id='minCals' maxlength="5" />
    				<br />
    				<label for='maxCals' >Maximum Calories:</label>
    				<input type='text' name='maxCals' id='maxCals' maxlength="5" />
    				<br />
    				<label for='minFat' >Minimum Fat:</label>
    				<input type='text' name='minFat' id='minFat' maxlength="5" />
    				<br />
    				<label for='maxFat' >Maximum Fat:</label>
    				<input type='text' name='maxFat' id='maxFat' maxlength="5" />
    				<br />
    				<input type='submit' name='Submit' value='Update' />
     
    			</fieldset>
    		</form>
        </section>
        <!-- [/content] -->
        
    </div>

</body>

<?php } ?>