<?php
  if (!isset($_POST['restaurantid'])) {
    echo "You need to specify a restaurant. Please <a href='all-restaurants.php'>try again</a>.";
    die();
  }

  $restaurantid = $_POST['restaurantid'];

  // In production code, you might want to "cleanse" the $drinker string
  // to remove potential hacks before doing something with it (e.g.,
  // passing it to the DBMS).  That said, using prepared statements
  // (see below for details) can prevent SQL injection attack even if
  // $drinker contains potentially malicious character sequences.
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

<html>
<head><title>All Foods at  <?= $myrow['name'] ?></title></head>
<body>
<h1>All Foods at  <?= $myrow['name'] ?></h1>

<form method="post" action="student-info.php">
Select the food you ate below:<br/>
<?php
      do {
        // echo produces output HTML:
        echo "<input type='radio' name='food' value='" . $myrow['foodID'] . "'required/>";
        echo $myrow[1] . "<br/>";
      } while ($myrow = $st->fetch());
      // Below we will see the use of a "short open tag" that is equivalent
      // to echoing the enclosed expression.
?>
<?= $st->rowCount() ?> food(s) found in the database.<br/>
<input type="submit" value="GO!"/>
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
</body>
</html>
