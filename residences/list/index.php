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
        $locationStuff = [];
        $dist = 10;
        $myLat = $_GET['lat'];
        $myLon = $_GET['lon'];
        $locationStuff['myLat'] = $myLat;
        $locationStuff['myLon'] = $myLon;
        $locationStuff['dist'] = $dist;
        // calculate lon and lat for the rectangle:
        $lon1 = $myLon - $dist / abs(cos(deg2rad($myLat)) * 111);
        $lon2 = $myLon + $dist / abs(cos(deg2rad($myLat)) * 111);
        $lat1 = $myLat - ($dist / 111);
        $lat2 = $myLat + ($dist / 111);
        $locationStuff['lon1'] = $lon1;
        $locationStuff['lon2'] = $lon2;
        $locationStuff['lat1'] = $lat1;
        $locationStuff['lat2'] = $lat2;

        //$data['locationStuff'] = $locationStuff;

        $query = 'SELECT r.id, r.shack_id, r.street_address, r.latitude, r.longitude,
            6366.564864 * 2 * ASIN(SQRT( POWER(SIN(('.$myLat.' -r.latitude) * pi()/180 / 2), 2) +COS('.$myLat.' * pi()/180) * COS(r.latitude * pi()/180) *POWER(SIN(('.$myLon.' -r.longitude) * pi()/180 / 2), 2) )) as distance
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
                $streetAddress, $latitude, $longitude, $distance);

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
                    "distance"=>$distance
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
