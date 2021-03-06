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
    	$goals_query = "SELECT maxCals,minCals FROM Goals WHERE goals_userid=" . $userid; 
    	$g_query = $dbh->query($goals_query); 
    	$g_row = $g_query->fetch(); 
    	if(!empty($g_row)){
			echo "Your calorie goal is between " . $g_row[1] . " and " . $g_row[0] . " calories. <br/>"; 
		}
		else{
			echo "You have not set a goal yet! Click <a href='edit_profile.php'>here</a> to set one. <br/>"; 
		}
    	//echo "Your calorie goal is between " . $g_row[7] . " and " . $g_row[2] . " calories. <br/>"; 
        
        //calculate number of calories consumed on current day
    	$cal_query = "SELECT COALESCE(SUM(calories),0) FROM Ate, Food WHERE Food.foodid = Ate.foodid and Ate.eatDate >= '$date' and ate_userid=" . $userid;
    	$c_query = $dbh->query($cal_query);
    	$c_row = $c_query->fetch(); 
        echo "You have consumed " . $c_row[0] ." calories today. <br/><br/>"; 
        
        //Display foods that user has eaten
        $query = $dbh->query("SELECT * FROM Ate,Food WHERE Food.foodid = Ate.foodid and Ate.eatDate >= '$date' and ate_userid=" . $userid . "ORDER BY eatDate DESC"); 
		echo "You ate: <br/>"; 
		$row = $query->fetch(); 

		if(empty($row)){
    			echo "Nothing for today! <br/><br/>"; 
    	}else{
        echo "<table class='striped'>";
          echo "<thead>";
            echo "<tr>";
              echo "<th>";
                echo "Food";
              echo "</th>";
              echo "<th>";
                echo "Calories";
              echo "</th>";
              echo "<th>";
                echo "Date and time";
              echo "</th>";
            echo "</tr>";
          echo "</thead>";
          echo "<tbody>";

          echo "<tr>";
            echo "<td>";
              echo $row['name'];
            echo "</td>";
            echo "<td>"; 
              echo $row['calories'];
            echo "</td>";
            echo "<td>";
              echo $row[2];
            echo "</td>";
          echo "</tr>";
          //the part above is solely for the first one and the others are below

    		// echo $row['name'] . " " . $row['calories'] . " cal on ". $row[2] . "<br/>";
    		while($row = $query->fetch()) {
          echo "<tr>";
            echo "<td>";
              echo $row['name'];
            echo "</td>";
            echo "<td>";
              echo $row['calories'];
            echo "</td>";
            echo "<td>";
              echo $row[2];
            echo "</td>";
          echo "</tr>";
    			// echo $row['name'] . " " . $row['calories'] . " cal on ". $row[2] . "<br/>";
    		}
		}
        echo "</tbody></table><br/><br/>";

      } catch (PDOException $e) {
        print "Database error: " . $e->getMessage() . "<br/>";
        die();
      }
    ?>
    <a href='edit_profile.php' style='color:white'>
    <button class="btn waves-effect waves-light" type="submit" name="action">Edit Profile
      <i class="material-icons right">send</i>
    </button></a>
    <a href='all-restaurants.php' style='color:white'>
    <button class="btn waves-effect waves-light" type="submit" name="action">Add Food!
      <i class="material-icons right">send</i>
    </button></a>
    <a href='index.php' style='color:white'>
    <button class="btn waves-effect waves-light" type="submit" name="action">Go Home
      <i class="material-icons right">send</i>
    </button></a>
    <br/>
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
