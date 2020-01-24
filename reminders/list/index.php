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

      $query = 'SELECT r.id, r.date,
        IFNULL(GROUP_CONCAT(a.name ORDER BY a.name ASC SEPARATOR \', \'), \'No animals selected.\') animals
        FROM reminder r
        JOIN organisation_user ou ON ou.organisation_id = r.organisation_id
        JOIN user_session us ON us.user_id = ou.id
        LEFT JOIN reminder_animal ra ON ra.reminder_id = r.id
        LEFT JOIN animal a ON a.id = ra.animal_id
        WHERE us.token LIKE ?
        AND r.disabled = 0
        GROUP BY r.id
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
          mysqli_stmt_bind_result($stmt, $reminderID, $date, $animals);

          $reminders = [];
          /* fetch values */
          while (mysqli_stmt_fetch($stmt)) {

              /* build up the residence list */
              array_push($reminders, [
                  "id"=>$reminderID,
                  "date"=>$date,
                  "animals"=>$animals
              ]);
          }

          $data['reminders'] = $reminders;

          /* close statement */
          mysqli_stmt_close($stmt);
      } else {
          throw new Exception("Database exception, contact an administrator. ".$stmt->error);
      }
    } catch(Exception $e) {
        $responseCode = 400;
        array_push($errors, buildError('Error selecting reminders.', $e->getMessage()));
    }
}

handleResponse($responseCode, $data, $errors);

?>
