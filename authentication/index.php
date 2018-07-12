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
} catch(Exception $e) {
  $responseCode = 400;
  array_push($errors, buildError('Invalid Request', $e->getMessage()));
}

if ($responseCode == 200) {

  $data["hello"] = "world";

  $entityBody = json_decode(file_get_contents('php://input'), true);
  //print_r($entityBody["token"]);

}

handleResponse($responseCode, $data, $errors);

?>
