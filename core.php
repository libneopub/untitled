<?php
// Core neopub APIs live here.

require_once __DIR__ . "/init.php";
require_once __DIR__ . "/core/core.php";
require_once __DIR__ . "/core/store.php";
require_once __DIR__ . "/core/utils.php";
require_once __DIR__ . "/core/http.php";
require_once __DIR__ . "/core/dates.php";
require_once __DIR__ . "/core/urls.php";
require_once __DIR__ . "/core/stats.php";
require_once __DIR__ . "/core/notifications.php";
require_once __DIR__ . "/core/webmentions.php";
require_once __DIR__ . "/core/pingbacks.php";
require_once __DIR__ . "/core/renderer.php";
require_once __DIR__ . "/core/headers.php";

// Vendorerd
require_once __DIR__ . "/vendor/parsedown.php";

// Method to debug the micropub endpoint. It crashes the 
// endpoint and logs the request to log.json
function debug_endpoint() 
{
    header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
    echo "You sent:\n";
    print_r($_POST);

    file_put_contents(__DIR__ . "/log.json", json_encode($_POST));

    exit;
}
