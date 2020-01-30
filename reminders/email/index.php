<?PHP

require_once __DIR__ . '/../../databaseFunctions.php';

// Check for reminders for today
$query = 'SELECT r.id, r.organisation_id, r.note
    FROM reminder r
    WHERE r.date = CURDATE()
    AND disabled = 0';

$dbConnection = getDBConnection();

if ($stmt = mysqli_prepare($dbConnection, $query)) {

    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);

    /* bind variables to prepared statement */
    mysqli_stmt_bind_result($stmt, $reminderID, $organisationID, $note);

    /* fetch values */
    while (mysqli_stmt_fetch($stmt)) {
        // For each reminder, create the mail parameters
        $to = "jsincl4ir@gmail.com";
        $subject = "Welfare Reminder";
        $from = "noreply@houseval.co.za";
        $headers[] = 'From: Welfare Mailer <'.$from.'>';

        // Select the animals for the reminder
        $query = 'SELECT a.name, r.street_address, r.shack_id
            FROM reminder_animal ra
            JOIN animal a on a.id = ra.animal_id
            LEFT JOIN residence r on r.id = a.residence_id
            WHERE ra.reminder_id = ?';
        $paramTypes = 'i';
        $params = [$reminderID];

        $animalMessage = '';
        $hasAnimals = false;

        if ($secondaryStmt = mysqli_prepare($dbConnection, $query)) {

            mysqli_stmt_bind_param($secondaryStmt, $paramTypes, ...$params);
            mysqli_stmt_execute($secondaryStmt);
            mysqli_stmt_bind_result($secondaryStmt, $animalName, $streetAddress, $shackID);

            while (mysqli_stmt_fetch($secondaryStmt)) {
                $hasAnimals = true;

                // Build the address string
                $addressString = trim($streetAddress);
                if (strlen(trim($shackID)) != 0) {

                    if (strlen($addressString) > 0) {
                        $addressString = $addressString.', ';
                    }
                    $addressString = $addressString.'Shack ID: '.trim($shackID);
                }

                $addressString = strlen($addressString) > 0 ? $addressString : 'Unspecified Address';

                $animalMessage .= $animalName.", ".$addressString."\r\n";
            }

            mysqli_stmt_close($secondaryStmt);
        } else {
            throw new Exception("Database exception, contact an administrator.");
        }
        $animalMessage .= '';

        // Select the users for the company
        $bccString = '';
        $query = 'SELECT GROUP_CONCAT(ou.username ORDER BY ou.username ASC SEPARATOR \', \') addresses
            FROM reminder r
            JOIN organisation o on o.id = r.organisation_id
            JOIN organisation_user ou on ou.organisation_id = o.id
            WHERE r.id = ?
            GROUP BY r.id';
        $paramTypes = 'i';
        $params = [$reminderID];

        if ($secondaryStmt = mysqli_prepare($dbConnection, $query)) {

            mysqli_stmt_bind_param($secondaryStmt, $paramTypes, ...$params);
            mysqli_stmt_execute($secondaryStmt);
            mysqli_stmt_bind_result($secondaryStmt, $addresses);

            if (mysqli_stmt_fetch($secondaryStmt)) {
                $bccString = $addresses;
            }

            mysqli_stmt_close($secondaryStmt);
        } else {
            throw new Exception("Database exception, contact an administrator.");
        }

        // Add Bcc header
        $headers[] = 'Bcc:'.$bccString;

        // Create the message and headers
        $message = "Dear Welfare System User,\r\n
This is an automated email for a reminder scheduled in the app. Below are the details of the reminder\r\n
Reminder Note: ".$note."\r\n
Reminder Animals:\n".$animalMessage."\r\n
Have a great day!";

        // Send the mail
        if (mail($to,$subject,$message,implode("\n", $headers))) {
           echo("Message successfully sent!");
        } else {
           echo("Message delivery failed...");
        }
    }

    /* close statement */
    mysqli_stmt_close($stmt);
} else {
    throw new Exception("Database exception, contact an administrator. ".$stmt->error);
}

// Then disable older reminders

// admin@gaws.org.za
?>
