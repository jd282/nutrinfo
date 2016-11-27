<?php
global $connection;

if ( isset( $connection ) )
    return;

$connection = pg_connect("host=localhost port=5432 dbname=nutrinfo");

/*if (mysqli_connect_errno()) {        
    die(sprintf("Connect failed: %s\n", mysqli_connect_error()));
} */
?>