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
    if (isset($_GET["animal_id"])) {
      $animalID = $_GET['animal_id'];
    } else {
      throw new Exception("animal_id");
    }
  } catch(Exception $e) {
    $responseCode = 400;
    array_push($errors, buildError('Missing Parameters', $e->getMessage()));
  }
}

if ($responseCode == 200) {
    try {

      $query = 'SELECT a.animal_type_id, a.residence_id, a.name,
        IFNULL(a.approximate_dob, \'\') as approximate_dob, a.notes, a.treatments,
        r.street_address, r.shack_id, IFNULL(a.description, \'\') as description,
        a.sterilised, IFNULL(a.gender, \'\') as gender
        FROM animal a
        LEFT JOIN residence r on r.id = a.residence_id
        WHERE a.id = ?';
      $paramTypes = 'i';
      $params = [$animalID];

      $dbConnection = getDBConnection();

      // If everything has been validated thus far, prepare and run the query
      if ($stmt = mysqli_prepare($dbConnection, $query)) {

          if (count($params) > 0) {
              mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
          }

          mysqli_stmt_execute($stmt);

          mysqli_stmt_bind_result($stmt, $animalTypeID, $residenceID,
              $name, $approximateDOB, $notes, $treatments,
              $streetAddress, $shackID, $description, $sterilised, $gender);

          $details = [];
          if (mysqli_stmt_fetch($stmt)) {

              $addressString = trim($streetAddress);
              if (strlen(trim($shackID)) != 0) {

                  if (strlen($addressString) > 0) {
                      $addressString = $addressString.', ';
                  }
                  $addressString = $addressString.'Shack ID: '.trim($shackID);
              }

              $addressString = strlen($addressString) > 0 ? $addressString : 'Unspecified Address';

              $details = [
                  "id"=>$animalID,
                  "animal_type_id"=>$animalTypeID,
                  "residence_id"=>$residenceID,
                  "display_address"=>$addressString,
                  "name"=>$name,
                  "approximate_dob"=>$approximateDOB,
                  "notes"=>$notes,
                  "treatments"=>$treatments,
                  "description"=>$description,
                  "sterilised"=>$sterilised,
                  "gender"=>$gender
              ];
          }

          $data['animal_details'] = $details;

          mysqli_stmt_close($stmt);
      } else {
          throw new Exception("Database exception, contact an administrator.");
      }
    } catch(Exception $e) {
        $responseCode = 400;
        array_push($errors, buildError('Error selecting animal.', $e->getMessage()));
    }
}

handleResponse($responseCode, $data, $errors);

?>
