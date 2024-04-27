<?php
// Simple API that makes HTTP requests using cURL.

namespace http;

function request($uri, $headers = [], $options = []) {
  global $CANONICAL;

  $ch = curl_init();

  curl_setopt_array($ch, [
    CURLOPT_URL => $uri,
    CURLOPT_USERAGENT => $CANONICAL,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_TIMEOUT => 5,

    // Request headers
    CURLOPT_HTTPHEADER => flatten(": ", $headers);
  ])
  
  curl_setopt_array($ch, $options);

  // Response headers
  $headers = [];
  curl_setopt($ch, CURLOPT_HEADERFUNCTION,
    function ($ch, $header) use (&$headers) {
      [$name, $value] = explode(":", $header, 2);
      $name = strtolower(trim($name));
      $headers[$name][] = trim($value);

      return strlen($header);
    }
  );

  $response = curl_exec($ch);
  $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  return [
    "status" => $status,
    "headers" => $headers,
    "body" => $response
  ];
}

function get($uri, $headers = []) {
  $options = [
    CURLOPT_HTTPGET => true,
    CURLOPT_RETURNTRANSFER => true
  ];

  return request($uri, $headers, $options);
}

function post($uri, $data = [], $headers = []) {
  $options = [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_RETURNTRANSFER => true
  ];

  return request($uri, $headers, $options);
}

function head($uri, $headers = []) {
  $options = [
    CURLOPT_NOBODY => true,
    CURLOPT_RETURNTRANSFER => true
  ];

  return request($uri, $headers, $options);
}

function download($uri, $destination, $headers) {
  $fp = fopen($destination, "w+");

  $options = [
    CURLOPT_HTTPGET => true,
    CURLOPT_FILE => $fp,
    CURLOPT_RETURNTRANSFER => false
  ];

  $response = request($uri, $headers, $options);
  fclose($fp);

  return $response;
}
