<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Your Recommendations:</title>
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
	<h2>Your Recommendations</h2>
	
	
<?php
	session_start(); 
	$userid = $_SESSION['user_id']; 
		
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
  	$current_date = date('Y-m-d'); 
	
	$goals_query = "SELECT maxCals,minCals FROM Goals WHERE goals_userid=" . $userid; 
	$g_query = $dbh->query($goals_query); 
	$g_row = $g_query->fetch(); 
	
	$myrow = null; 
	if(!empty($g_row)){
		echo "Your calorie goal is between " . $g_row[1] . " and " . $g_row[0] . " calories. <br/>"; 
		
		$rec_query = "SELECT Food.name, Restaurant.name, Food.calories
						CASE 
							WHEN (0 >= ((SELECT Goals.maxCals
	FROM Goals
	WHERE Goals.goals_userid ='" . $userid . "') - 
		COALESCE((SELECT SUM(Food.calories)
		FROM Ate, Food
		WHERE Ate.foodID = Food.foodID AND Ate.ate_userid='" . $userid . "' and Ate.eatDate >= '$current_date'),0)))

THEN (
SELECT Food.name, Restaurant.name, Food.calories
FROM Restaurant, Food, Serves
ORDER BY Food.calories DESC
WHERE Restaurant.restaurantID=Serves.restaurantID and Food.foodID=Serves.foodID and Food.calories <= 
	((SELECT Goals.maxCals
	FROM Goals
	WHERE Goals.goals_userid ='" . $userid . "') - 
		COALESCE((SELECT SUM(Food.calories)
		FROM Ate, Food
		WHERE Ate.foodID = Food.foodID AND Ate.ate_userid='" . $userid . "' and Ate.eatDate >= '$current_date'),0)))

ELSE (
	SELECT Food.name, Restaurant.name, Food.calories
	FROM Restaurant, Food, Serves
	ORDER BY RANDOM()
	LIMIT 5
	WHERE Restaurant.restaurantID=Serves.restaurantID and Food.foodID=Serves.foodID) 
END"; 
		
		
		
	}
	else{
		echo "You have not set a goal yet! Click <a href='edit_profile.php'>here</a> to set one. <br/>"; 
		echo "Until you set a goal, here are random 5 food suggestions :)";
		
		$rec_query = "SELECT Food.name, Restaurant.name, Food.calories
					FROM Restaurant, Food, Serves
					WHERE Restaurant.restaurantID=Serves.restaurantID and Food.foodID=Serves.foodID
						ORDER BY RANDOM()
						LIMIT 5"; 	
	}
		
	/*
	$myrow = null; 
	$rec_query = "SELECT Food.name, Restaurant.name, Food.calories
FROM Restaurant, Food, Serves
WHERE Restaurant.restaurantID=Serves.restaurantID and Food.foodID=Serves.foodID and Food.calories <= ((SELECT Goals.maxCals
	FROM Goals
	WHERE Goals.goals_userid ='" . $userid . "') - 
		COALESCE((SELECT SUM(Food.calories)
		FROM Ate, Food
		WHERE Ate.foodID = Food.foodID AND Ate.ate_userid='" . $userid . "' and Ate.eatDate >= '$current_date'),0))"; 
	*/
	echo "
	
	<table>
		<tr>
			<th>Restaurant</th>
			<th>Food</th>
			<th>Calories</th>
		</tr>
	";
	$rec_q = $dbh->query($rec_query); 
	do{	
		echo "
		<tr>
			<td> " . $myrow[1] . "</td>
			<td> " . $myrow[0] . "</td>
			<td> " . $myrow[2] . "</td>
		</tr>
		";
		
	}while ($myrow = $rec_q->fetch());
	echo "</table>";
	
  }
catch (PDOException $e) {
    print "Database error: " . $e->getMessage() . "<br/>";
    die();
  }
  
?>
	<script src="loggedInBars.js"></script>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="./materialize/js/materialize.js"></script>
    <script src="./materialize/js/materialize.min.js"></script>
    <script src="./materialize/js/init.js"></script>
</body>
</html>
