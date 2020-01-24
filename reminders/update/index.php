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
      ['animals', 'note', 'date'], true);
  } catch(Exception $e) {
    $responseCode = 400;
    array_push($errors, buildError('Missing Parameters', $e->getMessage()));
  }
}

if ($responseCode == 200) {
    try {
        // Set the params to vars
        $animals = $decodedJSON['animals'];
        $note = $decodedJSON['note'];
        $date = $decodedJSON['date'];

        $insert = false;

        if ($reminderID = $decodedJSON['reminder_id']) {
          $query = "UPDATE reminder SET note = ?,
              date = ?
              WHERE id = ?";
          $paramTypes = 'ssi';
          $params = [$note, $date, $reminderID];
            $message = "Reminder Updated";
        } else {
            // Select the current user ID
            $query = "INSERT INTO reminder (organisation_id, note, date)
              SELECT u.organisation_id, ?, ?
              FROM user_session us
              JOIN organisation_user u
              ON u.id = us.user_id
              WHERE token = ? ";
            $paramTypes = 'sss';
            $params = [$note, $date, $sessionToken];
            $message = "Reminder Added";
            $insert = true;
        }

        $dbConnection = getDBConnection();

        if ($stmt = mysqli_prepare($dbConnection, $query)) {

            mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);

            if (mysqli_stmt_execute($stmt)) {
                $data['message'] = $message;

                if ($insert) {
                    $reminderID = mysqli_insert_id($dbConnection);
                }
            } else {
                throw new Exception("Database exception, contact an administrator.");
            }

            /* close statement */
            mysqli_stmt_close($stmt);
        } else {
            throw new Exception("Database exception, contact an administrator.");
        }

        // If it was an update, clear any animals currently assigned to the reminder
        if ($insert == false) {
            $query = "DELETE FROM reminder_animal
                WHERE reminder_id = ?";
            $paramTypes = 'i';
            $params = [$reminderID];

            $dbConnection = getDBConnection();

            if ($stmt = mysqli_prepare($dbConnection, $query)) {

                mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);

                if (mysqli_stmt_execute($stmt)) {

                } else {
                    throw new Exception("Database exception, contact an administrator 1.");
                }

                /* close statement */
                mysqli_stmt_close($stmt);
            } else {
                throw new Exception("Database exception, contact an administrator 2.");
            }
        }

        // Then insert the given animal list
        $count = count($animals);
        if ($count > 0) {
            $placeholders = implode(',', array_fill(0, $count, '?'));
            $bindStr = str_repeat('i', $count);
            $query = "INSERT INTO reminder_animal (reminder_id, animal_id)
                SELECT ?, id
                FROM animal
                WHERE id IN ($placeholders)";
            $bindStr = $bindStr.'i';
            array_unshift($animals, $reminderID);
            $dbConnection = getDBConnection();

            if ($stmt = mysqli_prepare($dbConnection, $query)) {

                mysqli_stmt_bind_param($stmt, $bindStr, ...$animals);

                if (mysqli_stmt_execute($stmt)) {
                    //$data['message'] = $message;
                } else {
                    throw new Exception("Database exception, contact an administrator.");
                }

                mysqli_stmt_close($stmt);
            } else {
                throw new Exception("Database exception, contact an administrator.");
            }
        }

        $data['reminder_id'] = $reminderID;

    } catch(Exception $e) {
        $responseCode = 400;
        array_push($errors, buildError('Error editing reminders.', $e->getMessage()));
    } finally {
      /* close connection */
      mysqli_close($dbConnection);
    }
}

handleResponse($responseCode, $data, $errors);

?>
