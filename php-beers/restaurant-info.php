<?php
  if (!isset($_POST['restaurantid'])) {
    echo "You need to specify a restaurant. Please <a href='all-restaurants.php'>try again</a>.";
    die();
  }

  $restaurantid = $_POST['restaurantid'];
?>

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
    $st = $dbh->query("SELECT * FROM Food, Serves,Restaurant WHERE Serves.restaurantID='$restaurantid' and Serves.foodID=Food.foodID and Restaurant.restaurantID=Serves.restaurantID");
    if (($myrow = $st->fetch())) {
?>

<!DOCTYPE html>
<!-- saved from url=(0065)http://materializecss.com/templates/starter-template/preview.html -->
<html lang='en'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
  
  <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1.0'>
  <title>All Foods at  <?= $myrow['name'] ?></title>

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
    <h1>All Foods at  <?= $myrow['name'] ?></h1>

    <form method="post" action="student-info.php">
    Select the food you ate below:<br/>
    <br />
    <?php
    	  session_start(); 
    	  $foods = array(); 
          do {
            // echo produces output HTML:
            $quantity = 10; //people can select quantity of 1-10 for each food
            echo "<div class='input-field col s2'>" 
            echo "<select name='" . $myrow[0] . "' >";
            for($i=0;$i<=$quantity;$i++){
            	echo "<option value='" . $i . "'>" . $i . "</option>";
            }
            echo "</select></div";
            echo $myrow[1] . "<br/>";

          } while ($myrow = $st->fetch());

    ?>
    <?= $st->rowCount() ?> food(s) found in the database.<br/>

    <input type="submit" value="Add!"/>
    </form>
    <?php
        } else {
          echo "There is no food in the database.";
        }
      } catch (PDOException $e) {
        print "Database error: " . $e->getMessage() . "<br/>";
        die();
      }
    ?>

  </div>

  <!--  Scripts-->
  <script src='loggedInBars.js'></script>
  <!-- // <script src='./materialize/js/jquery-2.1.1.min.js'></script> -->
  <script src='https://code.jquery.com/jquery-2.1.1.min.js'></script>
  <script src='./materialize/js/materialize.js'></script>
  <script src='./materialize/js/materialize.min.js'></script>
  <script src='./materialize/js/init.js'></script>

</body>
</html>
