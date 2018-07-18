<?php

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

?>
