<?php
  session_start(); 
  if (!isset($_POST['food'])) {
    echo "You need to specify a food. Please <a href='all-restaurants.php'>try again</a>.";
    die();
  }
  $food = $_POST['food'];
?>

<html>
<head><title>Nutrinfo</title></head>
<body>

<h1>Your Information: </h1>
<?php
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
  try {
  
  	$date = date('Y-m-d H:i:s'); 
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
		echo "You have not set a goal yet! Click <a href='#'>here</a> to set one. <br/>"; 
	}
    
    //insert new food into Ate table in database
    $insert_query = "INSERT INTO Ate(ate_userid, studentNetID, foodID, eatDate) VALUES('" . $userid . "', 'jd282', '" . $food . "', '" . $date . "')";
    $insert_result = $dbh->query($insert_query); 
    
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
  /*
  or <a href='edit-drinker.php?drinker=<?= $drinker ?>'>edit</a> the information.
  */
?>
<a href='all-restaurants.php'>Add more food!</a>
<br/>
Go <a href='index.php'>home</a>

</body>
</html>
