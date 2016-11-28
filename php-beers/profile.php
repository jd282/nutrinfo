<?php
	session_start(); 
?>

<html>
<head><title>Nutrinfo</title></head>
<body>

<h1>Your Information: </h1>
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
<a href='all-restaurants.php'>Add food!</a>
<br/>
Go <a href='index.php'>home</a>

</body>
</html>
