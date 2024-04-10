<?php
// Simple API that makes HTTP requests using cURL.

namespace http;

function request($target_url, $options = []) {
  global $CANONICAL;

  $headers = [];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $target_url);
  curl_setopt($ch, CURLOPT_USERAGENT, $CANONICAL);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 5);

  curl_setopt($ch, CURLOPT_HEADERFUNCTION,
    function ($curl, $header) use (&$headers) {
      $len = strlen($header);
      $header = explode(':', $header, 2);
      if (count($header) < 2) // ignore invalid headers
        return $len;

      $headers[strtolower(trim($header[0]))][] = trim($header[1]);

      return $len;
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
