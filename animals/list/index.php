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

      $query = 'SELECT a.id, a.animal_type_id, at.description, a.name,
        a.approximate_dob, a.notes, a.welfare_number, a.treatments
        FROM animal a
        JOIN animal_type at ON at.id = a.animal_type_id';
      $hasFilters = false;
      $paramTypes = '';
      $params = [];

      if (isset($_GET["animal_type_id"])) {
          $animalType = $_GET['animal_type_id'];
          $query = $query.' WHERE at.id = ?';

          $paramTypes = $paramTypes.'i';
          $hasFilters = true;
          array_push($params, $animalType);
      }

      if (isset($_GET["name"])) {
          $name = $_GET['name'];
          if ($hasFilters) {
              $query = $query.' AND a.name LIKE ?';
          } else {
              $query = $query.' WHERE a.name LIKE ?';
          }

          $paramTypes = $paramTypes.'s';
          $hasFilters = true;
          array_push($params, '%'.$name.'%');
      }

      $dbConnection = getDBConnection();

      $query = $query.' LIMIT 25';

      // If everything has been validated thus far, check if the user session exists.
      if ($stmt = mysqli_prepare($dbConnection, $query)) {

          if (count($params) > 0) {
              mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
          }

          mysqli_stmt_execute($stmt);

          /* bind variables to prepared statement */
          mysqli_stmt_bind_result($stmt, $animalID, $animalTypeID,
              $description, $name, $approximateDOB, $notes,
              $welfareNumber, $treatments);

          $animals = [];
          /* fetch values */
          while (mysqli_stmt_fetch($stmt)) {

              /* build up the residence list */
              array_push($animals, [
                  "id"=>$animalID,
                  "animal_type_id"=>$animalTypeID,
                  "description"=>$description,
                  "name"=>$name,
                  "approximate_dob"=>$approximateDOB,
                  "notes"=>$notes,
                  "welfare_number"=>$welfareNumber,
                  "treatments"=>$treatments
              ]);
          }

          $data['animals'] = $animals;

          /* close statement */
          mysqli_stmt_close($stmt);
      } else {
          throw new Exception("Database exception, contact an administrator.");
      }
    } catch(Exception $e) {
        $responseCode = 400;
        array_push($errors, buildError('Error selecting animals.', $e->getMessage()));
    }
}

handleResponse($responseCode, $data, $errors);

?>
