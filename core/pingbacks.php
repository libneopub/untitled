<?php
// Handles sending and receiving pingbacks.

namespace pinkbacks;

function send_pingback($source_url, $target_url) {
  $endpoint = discover_endpoint($target_url);
    
  if (!$endpoint) return false;

  $payload = xmlrpc_encode_request("pingback.ping", array(
    $source_url, 
    $target_url
  ));

  $options = array(
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => array(
        "application/xml"
    )
  );

  $response = \http\request($endpoint, $options);

  // Collapse whitespace just to be safe
  $body = strtolower(preg_replace('/\s+/', '', $response["body"]));

  // Check if request was successful
  if ($response["status"] !== 200 || empty($body)) return false;
  if (strpos($body, '<fault>') || !strpos($body, '<string>')) return false;

  return $response;
}

function discover_endpoint($target_url) {
  $response = \http\request($target_url, array(CURLOPT_HTTPGET => true));
  $header = $response['headers']['X-Pingback'];
  $body = strip_comments($response["body"]);
  
  if($header) return $header;

  $rel_href = '/<(?:link|a)[ ]+href="([^"]*)"[ ]+rel="pingback"[ ]*\/?>/i';
  $href_rel = '/<(?:link|a)[ ]+rel="pingback"[ ]+href="([^"]*)"[ ]*\/?>/i';

  if(preg_match($rel_href, $body, $match) || preg_match($href_rel, $body, $match)) {
    return $match[1];
  }

  return false;
}

// Stolen from indieweb/mention-client-php, licensed 
function xmlrpc_encode_request($method, $params) {
  $xml  = '<?xml version="1.0"?>';
  $xml .= '<methodCall>';
  $xml .= '<methodName>'.htmlspecialchars($method).'</methodName>';
  $xml .= '<params>';
  foreach ($params as $param) {
    $xml .= '<param><value><string>'.htmlspecialchars($param).'</string></value></param>';
  }
  $xml .= '</params></methodCall>';

  return $xml;
}