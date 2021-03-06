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

      $query = 'SELECT IFNULL(r.shack_id, \'\') as shack_id,
          IFNULL(r.street_address, \'\') as street_address, r.latitude, r.longitude,
          r.notes, IFNULL(r.resident_name, \'\') as resident_name,
          IFNULL(r.tel_no, \'\') as tel_no, IFNULL(r.id_no, \'\') as id_no
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
              $latitude, $longitude, $notes, $residentName, $telNo, $idNo);

          $details = [];
          if (mysqli_stmt_fetch($stmt)) {

              $details = [
                  "id"=>$residenceID,
                  "shack_id"=>$shackID,
                  "street_address"=>$streetAddress,
                  "latitude"=>$latitude,
                  "longitude"=>$longitude,
                  "notes"=>$notes,
                  "resident_name"=>$residentName,
                  "tel_no"=>$telNo,
                  "id_no"=>$idNo
              ];

              mysqli_stmt_close($stmt);

              // Select the animals for the residence
              if ($stmt = mysqli_prepare($dbConnection, 'SELECT a.id, a.name, a.sterilised
                    FROM animal a
                    WHERE a.residence_id = ?
                    AND a.deleted = 0')) {

                  // Bind the insert values
                  mysqli_stmt_bind_param($stmt, "i", $residenceID);

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
