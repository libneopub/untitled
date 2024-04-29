<?php
// Handles OAuth/IndieAuth authentication
// for the micropub and media endpoints.

$_HEADERS = [];
foreach (getallheaders() as $name => $value) {
    $_HEADERS[$name] = $value;
}

if (!isset($_HEADERS['Authorization']) && !isset($_POST['access_token'])) {
    header($_SERVER['SERVER_PROTOCOL'] . " 401 Unauthorized");
    echo "Missing 'Authorization' header.";
    echo "Missing 'access_token' value.";
    
    echo "Please provide an access token.";
    exit;
}
if (!isset($_POST["h"])) {
    header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
    echo "Missing 'h' value.";
    exit;
}

$response = \http\get(TOKEN_ENDPOINT, [
    "Content-Type" => "application/x-www-form-urlencoded",
    "Authorization" => $_HEADERS['Authorization']
]);

parse_str($response['body'], $values);

if (!isset($values['me'])) {
    header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
    echo "Missing 'me' value in authentication token.";
    exit;
}
if (!isset($values['scope'])) {
    header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
    echo "Missing 'scope' value in authentication token.";
    exit;
}

$their_site = normalize_url($values['me']);
$our_site = normalize_url($site_domain);

if ($their_site !== $our_site) {
    header($_SERVER['SERVER_PROTOCOL'] . " 403 Forbidden");
    echo "Mismatching 'me' value in authentication token.";
    
    echo "Expected: " . strtolower($values['me']);
    echo "Got: " . strtolower($site_domain);
    exit;
}

if (!stristr($values['scope'], "create")) {
    header($_SERVER['SERVER_PROTOCOL'] . " 403 Forbidden");
    echo "Missing 'create' value in 'scope'.";
    exit;
}

// Everything's cool. Do something with the $_POST variables
// (such as $_POST["content"], $_POST["category"], $_POST["location"], etc.)
// e.g. create a new entry, store it in a database, whatever.

// debug_endpoint();
