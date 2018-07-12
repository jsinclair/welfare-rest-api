<?PHP

include '../databaseFunctions.php';
include '../utilFunctions.php';
include '../validationFunctions.php';

$headers = apache_request_headers();

$data = array();
$errors = array();
$responseCode = 200;

// Request validation
try {
  validateRequestMethod();

  validateContentType();

  $decodedJSON = validateAndReturnJSONBody();
} catch(Exception $e) {
  $responseCode = 400;
  array_push($errors, buildError('Invalid Request', $e->getMessage()));
}

try {
  validateJSONObject($decodedJSON,
    ['username', 'password', 'os_version', 'device', 'uuid'], false);
} catch(Exception $e) {
  $responseCode = 400;
  array_push($errors, buildError('Missing Parameters', $e->getMessage()));
}

if ($responseCode == 200) {

  // If everything has been validated thus far, check if the login details are valid.


  $data["hello"] = "world";

  //print_r($decodedJSON["password"]);

}

handleResponse($responseCode, $data, $errors);

?>
