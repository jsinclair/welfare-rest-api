<?PHP

include '../databaseFunctions.php';
include '../utilFunctions.php';
include '../validationFunctions.php';

$headers = apache_request_headers();

$data = array();
$errors = array();
$responseCode = 200;

// Request validation
//error_log(json_encode($headers)."\n", 3, "./error.log");
//error_log($_SERVER['REQUEST_METHOD']."\n", 3, "./error.log");
try {
  validateRequestMethod();

  validateContentType();

  $decodedJSON = validateAndReturnJSONBody();
} catch(Exception $e) {
  $responseCode = 400;
  array_push($errors, buildError('Invalid Request', $e->getMessage()));
}

if ($responseCode == 200) {
  try {
    validateJSONObject($decodedJSON,
      ['username', 'password', 'os_version', 'device', 'uuid'], false);
  } catch(Exception $e) {
    $responseCode = 400;
    array_push($errors, buildError('Missing Parameters', $e->getMessage()));
  }
}

try {
  if ($responseCode == 200) {
    $dbConnection = getDBConnection();

    // If everything has been validated thus far, check if the login details are valid.
    $username = $decodedJSON['username'];
    $password = $decodedJSON['password'];
    if ($stmt = mysqli_prepare($dbConnection, 'SELECT ou.id AS user_id, ou.fullname, o.name AS organisation_name
        FROM organisation_user ou
        JOIN organisation o
        ON o.id = ou.organisation_id
        WHERE username = ? AND password = ?')) {
      mysqli_stmt_bind_param($stmt, "ss", $username, $password);

      mysqli_stmt_execute($stmt);

      /* bind variables to prepared statement */
      mysqli_stmt_bind_result($stmt, $userID, $userFullName, $organisationName);

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

                // Load up the user info we want to return
                $data["token"] = $token;
                $data["user_full_name"] = $userFullName;
                $data["organisation_name"] = $organisationName;

                /* close statement */
                mysqli_stmt_close($stmt);

                // Load Animal Types
                if ($stmt = mysqli_prepare($dbConnection, 'SELECT id, description
                    FROM animal_type
                    ORDER BY description ASC')) {

                    mysqli_stmt_execute($stmt);

                    /* bind variables to prepared statement */
                    mysqli_stmt_bind_result($stmt, $id, $description);

                    $animalTypes = [];
                    /* fetch values, build up animal type list */
                    while (mysqli_stmt_fetch($stmt)) {
                        array_push($animalTypes, ["id"=>$id, "description"=>$description]);
                    }

                    /* close statement */
                    mysqli_stmt_close($stmt);

                    $data["animal_types"] = $animalTypes;
                }

                // Load Treatment Types
                if ($stmt = mysqli_prepare($dbConnection, 'SELECT id, description
                    FROM treatment
                    ORDER BY description ASC')) {

                    mysqli_stmt_execute($stmt);

                    /* bind variables to prepared statement */
                    mysqli_stmt_bind_result($stmt, $id, $description);

                    $treatments = [];
                    /* fetch values, build up animal type list */
                    while (mysqli_stmt_fetch($stmt)) {
                        array_push($treatments, ["id"=>$id, "description"=>$description]);
                    }

                    /* close statement */
                    mysqli_stmt_close($stmt);

                    $data["treatments"] = $treatments;
                }

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
