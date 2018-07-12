<?PHP

include '../databaseFunctions.php';
include '../utilFunctions.php';
include '../validationFunctions.php';

$headers = apache_request_headers();

$data = array();
$errors = array();
$responseCode = 200;

// Request validation
try {
  validateRequestMethod();

  validateContentType();

  $decodedJSON = validateAndReturnJSONBody();
} catch(Exception $e) {
  $responseCode = 400;
  array_push($errors, buildError('Invalid Request', $e->getMessage()));
}

try {
  validateJSONObject($decodedJSON,
    ['username', 'password', 'os_version', 'device', 'uuid'], false);
} catch(Exception $e) {
  $responseCode = 400;
  array_push($errors, buildError('Missing Parameters', $e->getMessage()));
}

try {
  if ($responseCode == 200) {
    $dbConnection = getDBConnection();

    // If everything has been validated thus far, check if the login details are valid.
    $username = $decodedJSON['username'];
    $password = $decodedJSON['password'];
    if ($stmt = mysqli_prepare($dbConnection, 'SELECT id FROM organisation_user
        WHERE username = ? AND password = ?')) {
      mysqli_stmt_bind_param($stmt, "ss", $username, $password);

      mysqli_stmt_execute($stmt);

      /* bind variables to prepared statement */
      mysqli_stmt_bind_result($stmt, $userID);

      /* fetch values */
      if (mysqli_stmt_fetch($stmt)) {

          /* close statement */
          mysqli_stmt_close($stmt);

          // Create the user session
          if ($stmt = mysqli_prepare($dbConnection, 'INSERT INTO user_session
            (user_id, token, os_version, device, uuid) VALUES (?, ?, ?, ?, ?)')) {

              $token = getToken(100);
              $osVersion = $decodedJSON['os_version'];
              $device = $decodedJSON['device'];
              $uuid = $decodedJSON['uuid'];

              // Bind the insert values
              mysqli_stmt_bind_param($stmt, "issss", $userID,
                $token, $osVersion, $device, $uuid);

              if (mysqli_stmt_execute($stmt)) {
                $data["token"] = $token;
              } else {
                throw new Exception("Error initialising session.");
              }
          } else {
            throw new Exception("Error initialising session.");
          }
      } else {
        throw new Exception("Invalid user credentials.");
      }
    }

    $data["hello"] = "world";

    /* close connection */
    mysqli_close($dbConnection);

  }
} catch (Exception $e) {
  $responseCode = 400;
  array_push($errors, buildError('Authentication Error', $e->getMessage()));
} finally {
  /* close statement */
  mysqli_stmt_close($stmt);

  /* close connection */
  mysqli_close($dbConnection);
}

handleResponse($responseCode, $data, $errors);

?>
