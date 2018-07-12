<?php

function buildError($title, $detail) {
  return array('title' => $title, 'details' => $detail);
}

function handleResponse($responseCode, $data, $errors) {
  http_response_code($responseCode);
  header('Content-type: application/json');

  // Build the result depending on the response code
  $result = array();
  if (400 <= $responseCode && $responseCode <= 599) {
    $result["errors"] = $errors;
  } else {
    $result["data"] = $data;
  }

  echo json_encode($result);
}

?>
