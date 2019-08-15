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

        $insert = false;

        if (isset($decodedJSON["residence_id"])) {
          $residenceID = $decodedJSON['residence_id'];

          // If we are updating a residence, clear any animals associated with it
          $query = "UPDATE animal SET residence_id = NULL
              WHERE residence_id = ?";
          $paramTypes = 'i';
          $params = [$residenceID];

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

            $insert = true;
        }

        $dbConnection = getDBConnection();

        if ($stmt = mysqli_prepare($dbConnection, $query)) {

            mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);

            if (mysqli_stmt_execute($stmt)) {
                $data['message'] = $message;

                if ($insert) {
                    $residenceID = mysqli_insert_id($dbConnection);
                }
            } else {
                throw new Exception("Database exception, contact an administrator.");
            }

            /* close statement */
            mysqli_stmt_close($stmt);
        } else {
            throw new Exception("Database exception, contact an administrator.");
        }

        $data['residence_id'] = $residenceID;

        // Update the animals assigned to this residence
        if (isset($decodedJSON["animals"])) {
            $animalIDs = $decodedJSON["animals"];
            $count = count($animalIDs);

            if ($count > 0) {
                $placeholders = implode(',', array_fill(0, $count, '?'));
                $bindStr = str_repeat('i', $count);
                $query = "UPDATE animal SET residence_id = ?
                    WHERE id IN ($placeholders)";
                $bindStr = $bindStr.'i';
                array_unshift($animalIDs, $residenceID);
                $dbConnection = getDBConnection();

                if ($stmt = mysqli_prepare($dbConnection, $query)) {

                    mysqli_stmt_bind_param($stmt, $bindStr, ...$animalIDs);

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
