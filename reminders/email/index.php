<?PHP

require_once '../../databaseFunctions.php';


// Set up parameters
$to = "jsincl4ir@gmail.com";
$subject = "Test Subject";
$message = "Test message.";
$from = "welfare@mailer.com";
$headers = "From: $from";

// Send email
if (mail($to,$subject,$message,$headers)) {
   echo("Message successfully sent!");
} else {
   echo("Message delivery failed...");
}

//echo "Thanks for registering! We have just sent you an email with your password.";

?>
