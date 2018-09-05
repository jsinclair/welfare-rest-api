<?php

include './databaseFunctions.php';

function validateRequestMethod($validMethod = 'POST') {
  if (strcasecmp($_SERVER['REQUEST_METHOD'], $validMethod) != 0) {
    throw new Exception('Request method must be '.$validMethod.'!');
  }
}

function validateContentType($validType = 'application/json') {
  //Make sure that the content type of the POST request has been set to application/json
  $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
  error_log(strpos(strtolower($contentType), strtolower($validType))."\n", 3, "./error.log");
  if (strpos(strtolower($contentType), strtolower($validType)) === false) {
    throw new Exception('Content type must be: '.$validType);
  }
}

function validateAndReturnJSONBody() {
  //Receive the RAW post data.
  $content = trim(file_get_contents("php://input"));

  //Attempt to decode the incoming RAW post data from JSON.
  $decoded = json_decode($content, true);

  //If json_decode failed, the JSON is invalid.
  if(!is_array($decoded)){
      throw new Exception('Received content contained invalid JSON!');
  }

  return $decoded;
}

function validateJSONObject($json, $requiredKeys, $allowNull = true) {
  $missingKeys = false;
  $missingKeysString = 'Missing parameters:';
  foreach ($requiredKeys as $key) {
    if (!array_key_exists ($key, $json)
      || (!$allowNull && $json[$key] == null)) {

      if ($missingKeys) {
        $missingKeysString .= ',';
      } else {
        $missingKeys = true;
      }
      $missingKeysString .= ' '.$key;

    }
  }

  if ($missingKeys) {
    $missingKeysString .= '.';
    throw new Exception($missingKeysString);
  }
}

function validateAuthorizationHeader() {
    if (getAuthorizationHeader() == null) {
        throw new Exception("Missing authorization header.");
    }
}

/**
 * Get header Authorization
 * */
function getAuthorizationHeader() {
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    }
    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
        // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
        //print_r($requestHeaders);
        if (isset($requestHeaders['Authorization'])) {
            $headers = trim($requestHeaders['Authorization']);
        }
    }
    return $headers;
}

/**
 * get access token from header
 * */
function getBearerToken() {
    $headers = getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }
    return null;
}

/**
 * Gets, validates and returns the user session from the request.
 * If there isn't one, or if it's invalid, an exception will be thrown.
 **/
function validateAndReturnSessionToken() {
    // Check if there is a bearer token
    if ($token = getBearerToken()) {

    } else {
        throw new Exception("No user token found.");
    }
}

?>
