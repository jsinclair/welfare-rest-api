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

        $query = 'SELECT r.id, IFNULL(r.shack_id, \'\') as shack_id,
            IFNULL(r.street_address, \'\') as street_address,
            r.latitude, r.longitude, IFNULL(r.resident_name, \'\') as resident_name,
            IFNULL(r.tel_no, \'\') as tel_no, IFNULL(r.id_no, \'\') as id_no,
            IFNULL(GROUP_CONCAT(a.NAME ORDER BY a.NAME ASC SEPARATOR \', \'), \'\') as animals
            FROM residence r
            LEFT JOIN animal a ON a.residence_id = r.id
            WHERE r.deleted = 0
            AND (a.deleted = 0 OR a.deleted IS NULL)';
        $paramTypes = '';
        $params = [];
        if (isset($_GET["shack_id"])) {
            $shackId = $_GET['shack_id'];
            $query = $query.' AND r.shack_id = ?';

            $paramTypes = $paramTypes.'s';
            array_push($params, $shackId);
        }

        if (isset($_GET["street_address"])) {
            $streetAddress = $_GET['street_address'];
            $query = $query.' AND r.street_address LIKE ?';

            $paramTypes = $paramTypes.'s';
            array_push($params, '%'.$streetAddress.'%');
        }

        if (isset($_GET["resident_name"])) {
            $residentName = $_GET['resident_name'];
            $query = $query.' AND r.resident_name LIKE ?';

            $paramTypes = $paramTypes.'s';
            array_push($params, '%'.$residentName.'%');
        }

        if (isset($_GET["tel_no"])) {
            $telNo = $_GET['tel_no'];
            $query = $query.' AND r.tel_no LIKE ?';

            $paramTypes = $paramTypes.'s';
            array_push($params, '%'.$telNo.'%');
        }

        if (isset($_GET["id_no"])) {
            $idNo = $_GET['id_no'];
            $query = $query.' AND r.id_no LIKE ?';

            $paramTypes = $paramTypes.'s';
            array_push($params, '%'.$idNo.'%');
        }

        $dbConnection = getDBConnection();

        $query = $query.' GROUP BY r.id';
        $query = $query.' LIMIT 25';

        // If everything has been validated thus far, check if the user session exists.
        if ($stmt = mysqli_prepare($dbConnection, $query)) {

            if (count($params) > 0) {
                mysqli_stmt_bind_param($stmt, $paramTypes, ...$params);
            }

            mysqli_stmt_execute($stmt);

            /* bind variables to prepared statement */
            mysqli_stmt_bind_result($stmt, $residenceID, $shackID,
                $streetAddress, $latitude, $longitude, $residentName, $telNo,
                $idNo, $animals);

            $residences = [];
            /* fetch values */
            while (mysqli_stmt_fetch($stmt)) {

                /* build up the residence list */
                array_push($residences, [
                    "id"=>$residenceID,
                    "shack_id"=>$shackID,
                    "street_address"=>$streetAddress,
                    "latitude"=>$latitude,
                    "longitude"=>$longitude,
                    "resident_name"=>$residentName,
                    "tel_no"=>$telNo,
                    "id_no"=>$idNo,
                    "animals"=>$animals
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
