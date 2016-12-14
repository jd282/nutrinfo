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
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
  
  <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1.0'>
  <title>Nutri-info</title>
  <title>All Restaurants on Campus</title>
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
    <h2>All Restaurants on Campus</h2>

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
        $st = $dbh->query('SELECT * FROM Restaurant ORDER BY name');
        if (($myrow = $st->fetch())) {
    ?>
    <form method="post" action="restaurant-info.php">
    Select where you ate:<br/>
    <?php
          do {
            // echo produces output HTML:
            echo "<input type='radio' name='restaurantid' value='" . $myrow[0] . "' id='" . $myrow[0] . "'required/>";
            echo "<label for='" . $myrow[0] . "'>". $myrow[2] . "</label><br/>";
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
  </div>
  <nav id='bottombar'></nav>
  <!--  Scripts-->
  <script src='loggedInBars.js'></script>
  <!-- // <script src='./materialize/js/jquery-2.1.1.min.js'></script> -->
  <script src='https://code.jquery.com/jquery-2.1.1.min.js'></script>
  <script src='./materialize/js/materialize.js'></script>
  <script src='./materialize/js/materialize.min.js'></script>
  <script src='./materialize/js/init.js'></script>
</body>
</html>
