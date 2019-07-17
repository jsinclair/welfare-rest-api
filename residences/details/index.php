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
    if (isset($_GET["residence_id"])) {
      $residenceID = $_GET['residence_id'];
    } else {
      throw new Exception("residence_id");
    }
  } catch(Exception $e) {
    $responseCode = 400;
    array_push($errors, buildError('Missing Parameters', $e->getMessage()));
  }
}

if ($responseCode == 200) {
    try {

      $query = 'SELECT r.shack_id, r.street_address, r.latitude, r.longitude, r.notes
        FROM residence r
        WHERE r.id = ?';
      $paramTypes = 'i';
      $params = [$residenceID];

      $dbConnection = getDBConnection();

      // If everything has been validated thus far, prepare and run the query
      if ($stmt = mysqli_prepare($dbConnection, $query)) {

          if (count($params) > 0) {
              mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
          }

          mysqli_stmt_execute($stmt);

          mysqli_stmt_bind_result($stmt, $shackID, $streetAddress,
              $latitude, $longitude, $notes);

          $details = [];
          if (mysqli_stmt_fetch($stmt)) {

              $details = [
                  "id"=>$residenceID,
                  "shack_id"=>$shackID,
                  "street_address"=>$streetAddress,
                  "latitude"=>$latitude,
                  "longitude"=>$longitude,
                  "notes"=>$notes
              ];
          }

          $data['residence_details'] = $details;

          mysqli_stmt_close($stmt);
      } else {
          throw new Exception("Database exception, contact an administrator.");
      }
    } catch(Exception $e) {
        $responseCode = 400;
        array_push($errors, buildError('Error selecting residence.', $e->getMessage()));
    }
}

handleResponse($responseCode, $data, $errors);

?>
