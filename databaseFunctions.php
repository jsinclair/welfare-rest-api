<?php

function getDBConnection() { //function parameters, two variables.
  $con = mysqli_connect("localhost", "root", "root", "animal_welfare");

  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    return null;
  } else {
    return $con;
  }
}

?>
