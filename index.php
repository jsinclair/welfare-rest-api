<?PHP
require_once 'databaseFunctions.php';

$dbConnection = getDBConnection();

$username = "jsincl4ir@gmail.com";
$password = "password";
if ($stmt = mysqli_prepare($dbConnection, 'SELECT * FROM organisation_user WHERE username = ? AND password = ?')) {
  mysqli_stmt_bind_param($stmt, "ss", $username, $password);

  mysqli_stmt_execute($stmt);

  $result = mysqli_stmt_get_result($stmt);

  /* fetch value */
  echo "Organisation Users: ".$result->num_rows;
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      echo $row['username'];
  }
  /* close statement */
  mysqli_stmt_close($stmt);
}

$username = "jsincl4ir@gmail.com";
$password = "password";
if ($stmt = mysqli_prepare($dbConnection, 'SELECT username, password FROM organisation_user WHERE username = ? AND password = ?')) {
  mysqli_stmt_bind_param($stmt, "ss", $username, $password);

  mysqli_stmt_execute($stmt);

  /* bind variables to prepared statement */
  mysqli_stmt_bind_result($stmt, $col1, $col2);

  /* fetch values */
  while (mysqli_stmt_fetch($stmt)) {
      printf("%s %s\n", $col1, $col2);
  }

  /* close statement */
  mysqli_stmt_close($stmt);
}

/* close connection */
mysqli_close($dbConnection);

?>
