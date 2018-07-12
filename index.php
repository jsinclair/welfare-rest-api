<?PHP
include 'databaseFunctions.php';

echo "Hello, world.";

$dbConnection = getDBConnection();

if ($result = mysqli_query($dbConnection, "SELECT * FROM organisation")) {
  echo "Organisations: ".$result->num_rows;

  while ($row = mysqli_fetch_assoc($result))
  {
    echo $row['name'];
  }

  $result.close();
}

$dbConnection.close();

?>
