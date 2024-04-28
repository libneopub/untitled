<?php
// Core neopub APIs live here.

$PUBB_VERSION = "0.1a";
$STORE = __DIR__ . "/../data/";

require_once __DIR__ . "/init.php";
require_once __DIR__ . "/core/core.php";
require_once __DIR__ . "/core/store.php";
require_once __DIR__ . "/core/utils.php";
require_once __DIR__ . "/core/http.php";
require_once __DIR__ . "/core/dates.php";
require_once __DIR__ . "/core/urls.php";
require_once __DIR__ . "/core/notifications.php";
require_once __DIR__ . "/core/webmentions.php";
require_once __DIR__ . "/core/pingbacks.php";
require_once __DIR__ . "/core/renderer.php";
require_once __DIR__ . "/core/headers.php";

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

// Enable error reporting for development
// TODO(robin): turn this off before deploying!!
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

