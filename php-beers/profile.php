<?php
	session_start(); 
?>

<html>

<head>
  <title>User Profile</title>
  <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1.0'>
  
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

    <h2>Your Information: </h2>
    <?php
      try {
        include("/etc/php5/pdo-mine.php");
        $dbh = dbconnect();
      } catch (PDOException $e) {
        print "Error connecting to the database: " . $e->getMessage() . "<br/>";
        die();
      }
      try {
      
      	$date = date('Y-m-d'); 
    	$firstname = $_SESSION['user_firstname']; 
    	$userid = $_SESSION['user_id']; 
    	
    	//print goal information
    	$goals_query = "SELECT * FROM Goals WHERE goals_userid=" . $userid; 
    	$g_query = $dbh->query($goals_query); 
    	$g_row = $g_query->fetch(); 
    	echo "Your calorie goal is between " . $g_row[7] . " and " . $g_row[2] . " calories. <br/>"; 
        
        //calculate number of calories consumed on current day
    	$cal_query = "SELECT COALESCE(SUM(calories),0) FROM Ate, Food WHERE Food.foodid = Ate.foodid and eatDate='$date' and ate_userid=" . $userid;
    	$c_query = $dbh->query($cal_query);
    	$c_row = $c_query->fetch(); 
        echo "You have consumed " . $c_row[0] ." calories today. <br/>"; 
        
        //Display foods that user has eaten
        $query = $dbh->query("SELECT * FROM Ate,Food WHERE Food.foodid = Ate.foodid and ate_userid=" . $userid . "ORDER BY eatDate DESC"); 
    	echo "You ate: <br/>"; 
        while($row = $query->fetch()) {
        	echo $row['name'] . " " . $row['calories'] . " cal on ". $row[3] . "<br/>";
        }
        echo "<br/>\n";

      } catch (PDOException $e) {
        print "Database error: " . $e->getMessage() . "<br/>";
        die();
      }
    ?>
    <button class="btn waves-effect waves-light" type="submit" name="action">Add Food!
      <i class="material-icons right">send</i>
    </button>
    <br/>
    Go <a href='index.php'>home</a>
  </div>

  <nav id='bottombar' />
  <!--  Scripts-->
  <script src='loggedInBars.js'></script>
  <!-- // <script src='./materialize/js/jquery-2.1.1.min.js'></script> -->
  <script src='https://code.jquery.com/jquery-2.1.1.min.js'></script>
  <script src='./materialize/js/materialize.js'></script>
  <script src='./materialize/js/materialize.min.js'></script>
  <script src='./materialize/js/init.js'></script>

</body>
</html>
