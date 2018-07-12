<?php

function validateRequestMethod($validMethod = 'POST') {
  if (strcasecmp($_SERVER['REQUEST_METHOD'], $validMethod) != 0) {
    throw new Exception('Request method must be '.$validMethod.'!');
  }
}

function validateContentType($validType = 'application/json') {
  //Make sure that the content type of the POST request has been set to application/json
  $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
  if(strcasecmp($contentType, $validType) != 0) {
      throw new Exception('Content type must be: '.$validType);
  }
}

?>
