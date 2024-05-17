<?php
// Functions for sending JSON-encoded payloads.

namespace resp;

function json_error($message) {
  json_data(["error" => $message]);
}

function json_message($message) {
  json_data(["message" => $message]);
}

function json_data($data) { 
  header("Content-Type: application/json; charset=UTF-8");
  echo json_encode($data);
}
