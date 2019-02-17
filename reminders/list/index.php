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
    validateRequestMethod('GET');

    validateAuthorizationHeader();

    // This function doesn't validate the user token, as it's possible to get
    // this update after they have logged out.
    $sessionToken = validateAndReturnSessionToken(true);
    if ($sessionToken == null) {
        throw new Exception("No user session.");
    }
} catch(Exception $e) {
    $responseCode = 400;
    array_push($errors, buildError('Invalid Request', $e->getMessage()));
}

if ($responseCode == 200) {
    try {

      $query = 'SELECT r.id, a.name, r.reason, r.date
        FROM reminder r
        JOIN animal a ON a.id = r.animal_id
        JOIN user_session us ON us.user_id = r.user_id
        WHERE us.token LIKE ?
        AND r.disabled = 0
        ORDER BY r.DATE ASC';
      $paramTypes = 's';
      $params = [$sessionToken];

      $dbConnection = getDBConnection();

      $query = $query.' LIMIT 25';

      // If everything has been validated thus far, check if the user session exists.
      if ($stmt = mysqli_prepare($dbConnection, $query)) {

          mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);

          mysqli_stmt_execute($stmt);

          /* bind variables to prepared statement */
          mysqli_stmt_bind_result($stmt, $reminderID, $name,
              $reason, $date);

          $reminders = [];
          /* fetch values */
          while (mysqli_stmt_fetch($stmt)) {

              /* build up the residence list */
              array_push($reminders, [
                  "id"=>$reminderID,
                  "name"=>$name,
                  "reason"=>$reason,
                  "date"=>$date
              ]);
          }

          $data['reminders'] = $reminders;

          /* close statement */
          mysqli_stmt_close($stmt);
      } else {
          throw new Exception("Database exception, contact an administrator.");
      }
    } catch(Exception $e) {
        $responseCode = 400;
        array_push($errors, buildError('Error selecting reminders.', $e->getMessage()));
    }
}

handleResponse($responseCode, $data, $errors);

?>
