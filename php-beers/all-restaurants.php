<?php
  session_start(); 
 /*  
if (!isset($_POST['student'])) {
    echo "You need to specify a student. Please <a href='all-students.php'>try again</a>.";
    die();
  }
  $student = $_POST['student'];
*/
  // In production code, you might want to "cleanse" the $drinker string
  // to remove potential hacks before doing something with it (e.g.,
  // passing it to the DBMS).  That said, using prepared statements
  // (see below for details) can prevent SQL injection attack even if
  // $drinker contains potentially malicious character sequences.
?>

<html>
<head><title>All Restaurants on Campus</title></head>
<body>
<h1>All Restaurants on Campus</h1>

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
    $st = $dbh->query('SELECT * FROM Restaurant ');
    if (($myrow = $st->fetch())) {
?>
<form method="post" action="restaurant-info.php">
Select where you ate:<br/>
<?php
      do {
        // echo produces output HTML:
        echo "<input type='radio' name='restaurantid' value='" . $myrow[0] . "'required/>";
        echo $myrow[2] . "<br/>";
      } while ($myrow = $st->fetch());
      // Below we will see the use of a "short open tag" that is equivalent
      // to echoing the enclosed expression.
?>
<?= $st->rowCount() ?> restaurants(s) found in the database.<br/>
<input type="submit" value="NEXT!"/>
</form>
<?php
    } else {
      echo "There is no restaurants in the database.";
    }
  } catch (PDOException $e) {
    print "Database error: " . $e->getMessage() . "<br/>";
    die();
  }
?>
</body>
</html>
