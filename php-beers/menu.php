
<!DOCTYPE html>
<!-- saved from url=(0065)http://materializecss.com/templates/starter-template/preview.html -->
<html lang='en'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
  
  <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1.0'>
  <title>Menu</title>

  <link href='' rel='icon'>

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

    <ul class="collapsible" data-collapsible="accordion">

    <?php
          try {
            include("/etc/php5/pdo-mine.php");
            $dbh = dbconnect();
          } catch (PDOException $e) {
            print "Error connecting to the database: " . $e->getMessage() . "<br/>";
            die();
          }
          try {
            $st = $dbh->query('SELECT restaurantID,name FROM Restaurant ');
            if (($myrow = $st->fetch())) {
            
              do {
                print $myrow['name'];
                $r_id = $myrow[0]; 
                print $r_id; 
                $foods_q = $dbh->query('SELECT Food.name FROM Serves, Food WHERE Serves.restaurantID=' . $r_id . ' AND Food.foodID=Serves.foodID'); 
                if(($food = $foods_q->fetch())){
                	do{
                		print $food['name'];

                	}while($food = $foods_q->fetch()); 		
                } 
                
                echo "<br/>";
                
              } while ($myrow = $st->fetch());
              // Below we will see the use of a "short open tag" that is equivalent
              // to echoing the enclosed expression.
       
            } 
          } catch (PDOException $e) {
            print "Database error: " . $e->getMessage() . "<br/>";
            die();
          }
    ?>

    </ul>
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

