<?php
// Core neopub APIs live here.

$PUBB_VERSION = "0.1a";
$BASE = __DIR__ . "../data/";

require_once __DIR__ . "/core/headers.php";
require_once __DIR__ . "/core/utils.php";
require_once __DIR__ . "/core/http.php";
require_once __DIR__ . "/core/dates.php";
require_once __DIR__ . "/core/urls.php";
require_once __DIR__ . "/core/posts.php";
require_once __DIR__ . "/core/webmentions.php";
require_once __DIR__ . "/core/pingbacks.php";

// Method to debug the micropub endpoint. It crashes the 
// endpoint and logs the request to log.json
function debug_endpoint() 
{
    header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
    echo "You sent:\n";
    print_r($_POST);

    $file = fopen(__DIR__."/log.json", "w+");
    fwrite($file, json_encode($_POST));
    fclose($file);

    exit;
}
