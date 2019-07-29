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
      ['animal_type_id', 'name', 'notes',
        'welfare_number', 'treatments'], true);
  } catch(Exception $e) {
    $responseCode = 400;
    array_push($errors, buildError('Missing Parameters', $e->getMessage()));
  }
}

if ($responseCode == 200) {
    try {
        // Set the params to vars
        $animalTypeID = $decodedJSON['animal_type_id'];
        $residenceID = $decodedJSON['residence_id'];
        $name = $decodedJSON['name'];
        $approximateDOB = $decodedJSON['approximate_dob'];
        $notes = $decodedJSON['notes'];
        $welfareNumber = $decodedJSON['welfare_number'];
        $treatments = $decodedJSON['treatments'];

        // Validate the DOB, set to null if invalid
        if ($approximateDOB != null) {
            $d = DateTime::createFromFormat('Y-m-d', $approximateDOB);
            if ($d == null || !($d->format('Y-m-d') === $approximateDOB)) {
                $approximateDOB = null;
            }
        }

        if (isset($decodedJSON["animal_id"])) {
          $animalID = $decodedJSON['animal_id'];
          $query = "UPDATE animal SET animal_type_id = ?,
              residence_id = ?,
              name = ?,
              approximate_dob = ?,
              notes = ?,
              welfare_number = ?,
              treatments = ?
              WHERE id = ?";
          $paramTypes = 'iisssssi';
          $params = [$animalTypeID, $residenceID, $name, $approximateDOB,
            $notes, $welfareNumber, $treatments, $animalID];
          $message = $name." Updated";
        } else {
            $query = "INSERT INTO animal (animal_type_id, residence_id, name,
              approximate_dob, notes, welfare_number, treatments)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
            $paramTypes = 'iisssss';
            $params = [$animalTypeID, $residenceID, $name, $approximateDOB,
              $notes, $welfareNumber, $treatments];
            $message = $name." Added";
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
        array_push($errors, buildError('Error editing animals.', $e->getMessage()));
    } finally {
      /* close connection */
      mysqli_close($dbConnection);
    }
}

handleResponse($responseCode, $data, $errors);

?>
