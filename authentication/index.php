<?PHP

include '../databaseFunctions.php';
include '../utilFunctions.php';
include '../validationFunctions.php';

$headers = apache_request_headers();

$data = array();
$errors = array();
$responseCode = 200;

// Request validation
$decodedJSON;
try {
  validateRequestMethod();

  validateContentType();

  $decodedJSON = validateAndReturnJSONBody();
} catch(Exception $e) {
  $responseCode = 400;
  array_push($errors, buildError('Invalid Request', $e->getMessage()));
}

if ($responseCode == 200) {

  $data["hello"] = "world";

  //print_r($decodedJSON["token"]);

}

handleResponse($responseCode, $data, $errors);

?>
