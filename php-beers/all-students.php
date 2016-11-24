<html>
<head><title>All Students</title></head>
<body>
<h1>All Students</h1>

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
    $st = $dbh->query('SELECT * FROM Student');
    if (($myrow = $st->fetch())) {
?>
<form method="post" action="all-restaurants.php">
Select your name below:<br/>
<?php
      do {
        // echo produces output HTML:
        echo "<input type='radio' name='student' value='" . $myrow[1] . "'/>";
        echo $myrow[1] . "<br/>";
      } while ($myrow = $st->fetch());
      // Below we will see the use of a "short open tag" that is equivalent
      // to echoing the enclosed expression.
?>
<?= $st->rowCount() ?> students(s) found in the database.<br/>
<input type="submit" value="NEXT!"/>
</form>
<?php
    } else {
      echo "There is no student in the database.";
    }
  } catch (PDOException $e) {
    print "Database error: " . $e->getMessage() . "<br/>";
    die();
  }
?>
</body>
</html>
