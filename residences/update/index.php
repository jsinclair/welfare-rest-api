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
      ['shack_id', 'street_address', 'latitude', 'longitude', 'notes'], true);
  } catch(Exception $e) {
    $responseCode = 400;
    array_push($errors, buildError('Missing Parameters', $e->getMessage()));
  }
}

if ($responseCode == 200) {
    try {
        // Set the params to vars
        $shackID = $decodedJSON['shack_id'];
        $streetAddress = $decodedJSON['street_address'];
        $latitude = $decodedJSON['latitude'];
        $longitude = $decodedJSON['longitude'];
        $notes = $decodedJSON['notes'];

        if (isset($decodedJSON["residence_id"])) {
          $residenceID = $decodedJSON['residence_id'];
          $query = "UPDATE residence SET shack_id = ?,
              street_address = ?,
              latitude = ?,
              longitude = ?,
              notes = ?
              WHERE id = ?";
          $paramTypes = 'ssddsi';
          $params = [$shackID, $streetAddress, $latitude,
            $longitude, $notes, $residenceID];
            $message = "Residence Updated";
        } else {
            $query = "INSERT INTO residence (shack_id, street_address, latitude, longitude, notes)
              VALUES (?, ?, ?, ?, ?)";
            $paramTypes = 'ssdds';
            $params = [$shackID, $streetAddress, $latitude,
              $longitude, $notes];
            $message = "Residence Added";
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
