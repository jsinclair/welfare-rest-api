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

// Check that an animal ID was sent
if ($responseCode == 200) {
  try {
    if (isset($_GET["reminder_id"])) {
      $reminderID = $_GET['reminder_id'];
    } else {
      throw new Exception("reminder_id");
    }
  } catch(Exception $e) {
    $responseCode = 400;
    array_push($errors, buildError('Missing Parameters', $e->getMessage()));
  }
}

if ($responseCode == 200) {
    try {

      $query = 'SELECT IFNULL(r.organisation_id, \'\') as organisation_id,
          IFNULL(r.note, \'\') as note, r.date
        FROM reminder r
        WHERE r.id = ?';
      $paramTypes = 'i';
      $params = [$reminderID];

      $dbConnection = getDBConnection();

      // If everything has been validated thus far, prepare and run the query
      if ($stmt = mysqli_prepare($dbConnection, $query)) {

          if (count($params) > 0) {
              mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
          }

          mysqli_stmt_execute($stmt);

          mysqli_stmt_bind_result($stmt, $organisationID, $note, $date);

          $details = [];
          if (mysqli_stmt_fetch($stmt)) {

              $details = [
                  "id"=>$reminderID,
                  "organisation_id"=>$organisationID,
                  "note"=>$note,
                  "date"=>$date
              ];

              mysqli_stmt_close($stmt);

              // Select the animals for the residence
              if ($stmt = mysqli_prepare($dbConnection, 'SELECT a.id, a.name, a.sterilised
                    FROM animal a
                    JOIN reminder_animal ra ON ra.animal_id = a.id
                    WHERE ra.reminder_id = ?
                    AND a.deleted = 0')) {

                  // Bind the insert values
                  mysqli_stmt_bind_param($stmt, "i", $reminderID);

                  mysqli_stmt_execute($stmt);

                  /* bind variables to prepared statement */
                  mysqli_stmt_bind_result($stmt, $animalID, $animalName, $animalSterilised);

                  $animals = [];
                  /* fetch values, build up animal type list */
                  while (mysqli_stmt_fetch($stmt)) {
                      array_push($animals, [
                          "id"=>$animalID,
                          "name"=>$animalName,
                          "sterilised"=>$animalSterilised
                      ]);
                  }

                  /* close statement */
                  mysqli_stmt_close($stmt);

                  $details["animals"] = $animals;
              }
          }

          $data['reminder_details'] = $details;

          mysqli_stmt_close($stmt);
      } else {
          throw new Exception("Database exception, contact an administrator.");
      }
    } catch(Exception $e) {
        $responseCode = 400;
        array_push($errors, buildError('Error selecting reminder.', $e->getMessage()));
    }
}

handleResponse($responseCode, $data, $errors);

?>
