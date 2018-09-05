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

    validateAuthorizationHeader();

    $decodedJSON = validateAndReturnJSONBody();
} catch(Exception $e) {
    $responseCode = 400;
    array_push($errors, buildError('Invalid Request', $e->getMessage()));
}

if ($responseCode == 200) {
    try {
        // This function doesn't validate the user token, as it's possible to get
        // this update after they have logged out.
        $sessionToken = getBearerToken();
        if ($sessionToken == null) {
            throw new Exception("No user session.");
        }

        validateJSONObject($decodedJSON,
            ['firebase_token'], false);
    } catch(Exception $e) {
        $responseCode = 400;
        array_push($errors, buildError('Missing Parameters', $e->getMessage()));
    }
}

try {
    if ($responseCode == 200) {
        $dbConnection = getDBConnection();

        // If everything has been validated thus far, check if the user session exists.
        if ($stmt = mysqli_prepare($dbConnection, 'SELECT us.user_id
            FROM user_session us
            WHERE us.token = ?')) {

            mysqli_stmt_bind_param($stmt, "s", $sessionToken);

            mysqli_stmt_execute($stmt);

            /* bind variables to prepared statement */
            mysqli_stmt_bind_result($stmt, $userID);

            /* fetch values */
            if (mysqli_stmt_fetch($stmt)) {

                /* close statement */
                mysqli_stmt_close($stmt);

                // Update the user with the new firebase token.
                if ($stmt = mysqli_prepare($dbConnection, 'UPDATE organisation_user ou
                    SET ou.firebase_token = ?
                    WHERE ou.id = ?')) {

                    $firebaseToken = $decodedJSON['firebase_token'];

                    // Bind the insert values
                    mysqli_stmt_bind_param($stmt, "si", $firebaseToken,
                        $userID);

                    if (mysqli_stmt_execute($stmt)) {

                        // Update the respose with a success
                        $data["message"] = 'Firebase token updated.';

                        /* close statement */
                        mysqli_stmt_close($stmt);

                    } else {
                        throw new Exception("Error updating Firebase token.");
                    }
                } else {
                    throw new Exception("Error updating Firebase token.");
                }
            } else {
                throw new Exception("Invalid user session.");
            }
        }

        /* close connection */
        mysqli_close($dbConnection);

    }
} catch (Exception $e) {
    $responseCode = 400;
    array_push($errors, buildError('Firebase token error', $e->getMessage()));
} finally {
    /* close statement */
    mysqli_stmt_close($stmt);

    /* close connection */
    mysqli_close($dbConnection);
}

handleResponse($responseCode, $data, $errors);

?>
