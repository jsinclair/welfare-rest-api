<?PHP

require_once '../../databaseFunctions.php';
require_once '../../utilFunctions.php';
require_once '../../validationFunctions.php';

$headers = apache_request_headers();

$data = array();
$errors = array();
$responseCode = 200;

// Request validation
try {
    validateRequestMethod();

    validateAuthorizationHeader();

    // This function doesn't validate the user token, as it's possible to get
    // this update after they have logged out.
    $sessionToken = validateAndReturnSessionToken(true);
    if ($sessionToken == null) {
        throw new Exception("No user session.");
    }

    $decodedJSON = validateAndReturnJSONBody();
} catch(Exception $e) {
    $responseCode = 400;
    array_push($errors, buildError('Invalid Request', $e->getMessage()));
}

if ($responseCode == 200) {
  try {
    validateJSONObject($decodedJSON,
      ['animal_id', 'reason', 'date'], true);
  } catch(Exception $e) {
    $responseCode = 400;
    array_push($errors, buildError('Missing Parameters', $e->getMessage()));
  }
}

if ($responseCode == 200) {
    try {
        // Set the params to vars
        $animalID = $decodedJSON['animal_id'];
        $reason = $decodedJSON['reason'];
        $date = $decodedJSON['date'];

        if ($reminderID = $decodedJSON['reminder_id']) {
          $query = "UPDATE reminder SET animal_id = ?,
              reason = ?,
              date = ?
              WHERE id = ?";
          $paramTypes = 'issi';
          $params = [$animalID, $reason, $date, $reminderID];
            $message = "Reminder Updated";
        } else {
            // Select the current user ID
            $query = "INSERT INTO reminder (animal_id, user_id, reason, date)
              SELECT ?, user_id, ?, ?
              FROM user_session
              WHERE token = ? ";
            $paramTypes = 'isss';
            $params = [$animalID, $reason, $date, $sessionToken];
            $message = "Reminder Added";
        }

        $dbConnection = getDBConnection();

        if ($stmt = mysqli_prepare($dbConnection, $query)) {

            mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);

            if (mysqli_stmt_execute($stmt)) {
                $data['message'] = $message;
            } else {
                throw new Exception("Database exception, contact an administrator.");
            }

            /* close statement */
            mysqli_stmt_close($stmt);
        } else {
            throw new Exception("Database exception, contact an administrator.");
        }

    } catch(Exception $e) {
        $responseCode = 400;
        array_push($errors, buildError('Error editing residences.', $e->getMessage()));
    } finally {
      /* close connection */
      mysqli_close($dbConnection);
    }
}

handleResponse($responseCode, $data, $errors);

?>
