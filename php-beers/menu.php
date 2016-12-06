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