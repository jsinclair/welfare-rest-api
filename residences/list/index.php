<?PHP

include '../../databaseFunctions.php';
include '../../utilFunctions.php';
include '../../validationFunctions.php';

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
        $query = 'SELECT r.id, r.shack_id, r.street_address, r.latitude, r.longitude
            FROM residence r';
        $hasFilters = false;
        $paramTypes = '';
        $params = [];

        if ($shackId = $_GET['shack_id']) {
            $query = $query.' WHERE r.shack_id = ?';

            $paramTypes = $paramTypes.'s';
            $hasFilters = true;
            array_push($params, $shackId);
        }

        if ($streetAddress = $_GET['street_address']) {
            if ($hasFilters) {
                $query = $query.' AND r.street_address LIKE ?';
            } else {
                $query = $query.' WHERE r.street_address LIKE ?';
            }

            $paramTypes = $paramTypes.'s';
            $hasFilters = true;
            array_push($params, '%'.$streetAddress.'%');
        }

        $dbConnection = getDBConnection();

        $query = $query.' LIMIT 25';

        // If everything has been validated thus far, check if the user session exists.
        if ($stmt = mysqli_prepare($dbConnection, $query)) {

            mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);

            mysqli_stmt_execute($stmt);

            /* bind variables to prepared statement */
            mysqli_stmt_bind_result($stmt, $residenceID, $shackID,
                $streetAddress, $latitude, $longitude);

            $residences = [];
            /* fetch values */
            while (mysqli_stmt_fetch($stmt)) {

                /* build up the residence list */
                array_push($residences, [
                    "id"=>$residenceID,
                    "shack_id"=>$shackID,
                    "street_address"=>$streetAddress,
                    "latitude"=>$latitude,
                    "longitude"=>$longitude
                ]);
            }

            $data['residences'] = $residences;

            /* close statement */
            mysqli_stmt_close($stmt);
        } else {
            throw new Exception("Database exception, contact an administrator.");
        }

    } catch(Exception $e) {
        $responseCode = 400;
        array_push($errors, buildError('Error selecting residences.', $e->getMessage()));
    }
}

handleResponse($responseCode, $data, $errors);

?>
