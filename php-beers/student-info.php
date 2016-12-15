<?php
  session_start();
  $userid = $_SESSION['user_id'];  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Student Information</title>
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
  
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  		try{
    		//delete entry -- this part doesnt work yet 
   			if(isset($_POST['deleteitem']))
			{
  				$delete = $_POST['deleteitem']; 
  				$delArray = explode(',', $delete);
  				$foodid = $delArray[0];
  				$timestamp = $delArray[1]; 
  				//echo $delete; 
  		
  				$q = "DELETE FROM Ate WHERE ate_userid='$userid' AND foodid='$foodid' AND eatdat='$timestamp'"; 
  				$query = $dbh->query($q); 

			}	
		}catch (PDOException $e) {
    	print "Database error: " . $e->getMessage() . "<br/>";
   	 	die();
  		}
    }
  
  try {
  
  	$date = (string) date('Y-m-d H:i:s'); 
  	$date2 = (string) date('Y-m-d'); 
 
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
    
    //insert new food into Ate table in database
    foreach($_POST as $food_id => $quantity){ 
    	//for loop because user can input multiple quantities of food
    	for($i=0;$i<$quantity;$i++){
    		$insert_query = "INSERT INTO Ate(ate_userid, foodID, eatDate) VALUES('" . $userid . "', '" . $food_id . "', '" . $date . "')";
    		$insert_result = $dbh->query($insert_query); 
    	}
  	}
  	

    //calculate number of calories consumed on current day
	$cal_query = "SELECT COALESCE(SUM(calories),0) FROM Ate, Food WHERE Food.foodid = Ate.foodid and Ate.eatDate >= '$date2' and ate_userid=" . $userid;
	$c_query = $dbh->query($cal_query);
	$c_row = $c_query->fetch(); 
    echo "You have consumed " . $c_row[0] ." calories today. <br/>"; 
    
    //Display foods that user has eaten that day
    $query = $dbh->query("SELECT * FROM Ate,Food WHERE Food.foodid = Ate.foodid and Ate.eatDate >= '$date2' and ate_userid=" . $userid . "ORDER BY eatDate DESC"); 
	echo "<br/>You ate: <br/>"; 
	echo "<table>
	<tr style='border: 1px solid black;'>
		<th>Food</th>
		<th>Calories</th>
		<th>Time</th>
		<th>Delete</th>
	</tr>";
    while($row = $query->fetch()) {
    	//echo $row['name'] . " " . $row['calories'] . " cal on ". $row[3] . "<br/>";
    	echo "
    	<tr style='border: 1px solid black;'>
    		<td>" . $row['name'] . "</td>
    		<td>" . $row['calories'] . "</td>
    		<td>" . $row[2] . "</td>
    		<form action='student-info.php' method='post'>
    			<td><a class='btn-floating btn-large waves-effect waves-light red'><i class='material-icons'>delete</i><input type='submit' id='deleteitem' name='deleteitem' value='".$row[0].",".$row[2] . "'/></a> </td>
    		</form>
    	</tr>
    	";
    }
    
    echo "</table>";

    echo "<br/>\n";

  } catch (PDOException $e) {
    print "Database error: " . $e->getMessage() . "<br/>";
    die();
  }
  /*
  or <a href='edit-drinker.php?drinker=<?= $drinker ?>'>edit</a> the information.
  */
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
	<script src="loggedInBars.js"></script>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="./materialize/js/materialize.js"></script>
    <script src="./materialize/js/materialize.min.js"></script>
    <script src="./materialize/js/init.js"></script>
</body>
</html>
