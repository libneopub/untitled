<?php
// IndieAuth authorization endpoint.

// Based on Inklings-io/selfauth, which is
// dual-licensed CC0 and MIT.

require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../core.php";

if(!defined('CANONICAL') || !defined('MAIN_SITE') || !defined('ENCRYPTION_KEY') || !defined('HASHED_PASSWORD')) {
  header($_SERVER['SERVER_PROTOCOL'] . " 500 Internal Server Error");
  echo "One of the required configuration keys for operating the IndieAuth endpoint is unset. Aborting.";
  exit; 
}

// Signed codes always have an time-to-live, by default 1 year (31536000 seconds).
function create_signed_code($key, $message, $ttl = 31536000, $appended_data = "") {
  $expires = time() + $ttl;
  $body = $message . $expires . $appended_data;
  $signature = hash_hmac("sha256", $body, $key);
  return dechex($expires) . ":" . $signature . ":" . base64_url_encode($appended_data);
}

function verify_signed_code($key, $message, $code) {
  $code_parts = explode(":", $code, 3);
  if (count($code_parts) !== 3) {
      return false;
  }
  $expires = hexdec($code_parts[0]);
  if (time() > $expires) {
      return false;
  }
  $body = $message . $expires . base64_url_decode($code_parts[2]);
  $signature = hash_hmac("sha256", $body, $key);
  return hash_equals($signature, $code_parts[1]);
}

function verify_password($password) {
  $user_host = parse_url(MAIN_SITE, PHP_URL_HOST);
  $hash = md5($user_host . $password . ENCRYPTION_KEY);

  return hash_equals(PASSWORD_HASH, $hash);
}

function filter_input_regexp($type, $variable, $regexp, $flags = null) {
  $options = ['options' => ['regexp' => $regexp]];
  if ($flags !== null) $options['flags'] = $flags;
    
  return filter_input(
      $type,
      $variable,
      FILTER_VALIDATE_REGEXP,
      $options
  );
}

function get_q_value($mime, $accept) {
  $full_type = preg_replace('@^([^/]+\/).+$@', '$1*', $mime);
    
  $regex = implode(
    '',
    [
      '/(?<=^|,)\s*(\*\/\*|',
      preg_quote($full_type, '/'),
      '|',
      preg_quote($mime, '/'),
      ')\s*(?:[^,]*?;\s*q\s*=\s*([0-9.]+))?\s*(?:,|$)/'
    ]
  );

  $out = preg_match_all($regex, $accept, $matches);
  $types = array_combine($matches[1], $matches[2]);
    
  match(true) {
    array_key_exists($mime, $types) => $q = $types[$mime];
    array_key_exists($full_type, $types) => $q = $types[$full_type];
    array_key_exists('*/*', $types) => $q = $types['*/*'];
    default => return 0;
  }
    
  return $q === '' ? 1 : floatval($q);
}

// URL safe base64 encoding per https://tools.ietf.org/html/rfc7515#appendix-C
function base64_url_encode($string) {
  $string = base64_encode($string);
  $string = rtrim($string, '=');
  $string = strtr($string, '+/', '-_');
  return $string;
}

function base64_url_decode($string) {
  $string = strtr($string, '-_', '+/');
  $padding = strlen($string) % 4;
  if ($padding !== 0) {
    $string .= str_repeat('=', 4 - $padding);
  }
  $string = base64_decode($string);
  return $string;
}

// Okay, we're ready to rock and roll!!
// The authorization endpoint has three modes:
//
// - Verification, which I *think* verifies codes. Not sure tho.
// - Show an authentication form, and handle submitting it.

// We start with verifying codes. Check if there are any codes to be verified.
// Otherwise, we continue to processing the form below.

$code = filter_input_regexp(INPUT_POST, "code", '@^[0-9a-f]+:[0-9a-f]{64}:@');

if ($code !== null) {
  $redirect_uri = filter_input(INPUT_POST, "redirect_uri", FILTER_VALIDATE_URL);
  $client_id = filter_input(INPUT_POST, "client_id", FILTER_VALIDATE_URL);

  // Exit if there are errors in the client supplied data.
  if (!(is_string($code)
      && is_string($redirect_uri)
      && is_string($client_id)
      && verify_signed_code(APP_KEY, MAIN_SITE . $redirect_uri . $client_id, $code))
  ) {
    header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
    echo "Verification failed: given code was invalid.";
    exit;
  }

  $response = ["me" => MAIN_SITE)];
  $code_parts = explode(":", $code, 3);

  if ($code_parts[2] !== "") {
      $response['scope'] = base64_url_decode($code_parts[2]);
  }

  // Check what kind of response the client wants.
  $accept_header = '*/*';
  if (isset($_SERVER['HTTP_ACCEPT']) && strlen($_SERVER['HTTP_ACCEPT']) > 0) {
      $accept_header = $_SERVER['HTTP_ACCEPT'];
  }

  $json = get_q_value("application/json", $accept_header);
  $form = get_q_value("application/x-www-form-urlencoded", $accept_header);

  if ($json === 0 && $form === 0) {    
    header($_SERVER['SERVER_PROTOCOL'] . " 406 Not Acceptable");
    echo "The client accepts neither JSON nor form-encoded responses.";
    exit;
  } elseif ($json >= $form) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
  } else {
    header('Content-Type: application/x-www-form-urlencoded');
    echo http_build_query($response);
    exit;
  }
}

// Okay, the client apparently wasn't trying to verify a code. So,
// maybe the user has just submitted the form. Collect all data. If
// anything's missing, that means a malformed request, definitely not
// coming from our own form--throw an error.

$me = filter_input(INPUT_GET, "me", FILTER_VALIDATE_URL);
$client_id = filter_input(INPUT_GET, "client_id", FILTER_VALIDATE_URL);
$redirect_uri = filter_input(INPUT_GET, "redirect_uri", FILTER_VALIDATE_URL);
$state = filter_input_regexp(INPUT_GET, "state", '@^[\x20-\x7E]*$@');
$response_type = filter_input_regexp(INPUT_GET, "response_type", '@^(id|code)?$@');
$scope = filter_input_regexp(INPUT_GET, "scope", '@^([\x21\x23-\x5B\x5D-\x7E]+( [\x21\x23-\x5B\x5D-\x7E]+)*)?$@');

if(!is_string($client_id)) {
  header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
  echo "The 'client_id' was either omitted or not a valid URL.";
  exit;
}

if(!is_string($redirect_uri)) [  
  header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
  echo "The 'redirect_uri' was either omitted or not a valid URL.";
  exit;
]

if($state === false) {
  header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
  echo "The 'state' contains illegal characters.";
  exit;
}

if($response_type === false) {
  header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
  echo "The 'response_type' must be either 'id' or 'code'.";
  exit;
}

if($scope === false) {
  header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
  echo "The 'scope' contains illegal characters.";
  exit;
}

// Treat empty scope as omitted.
if($scope === "") $scope = null;

// Okay, everything looks gooooood :D
// If the user submitted their password, it's time to try to
// redirect to the callback.

$submitted_password = filter_input(INPUT_POST, "password", FILTER_UNSAFE_RAW);

if($submitted_password !== null) {
  $csrf_token = filter_input(INPUT_POST, "_csrf", FILTER_UNSAFE_RAW);

  if ($csrf_token === null || !verify_signed_code(ENCRYPTION_KEY, $client_id . $redirect_uri . $state, $csrf_token)) {
    header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
    echo "The CSRF token was invalid. Usually this means you took too long to log in. Please try again.";
    exit;
  }

  if (!verify_password($submitted_password)) {
    syslog(LOG_CRIT, "IndieAuth: login failure from " . $_SERVER['REMOTE_ADDR'] . " to $me");

    header($_SERVER['SERVER_PROTOCOL'] . " 403 Forbidden");
    echo "The password was wrong.";
    exit;
  }

  $scope = filter_input_regexp(INPUT_POST, "scopes", '@^[\x21\x23-\x5B\x5D-\x7E]+$@', FILTER_REQUIRE_ARRAY);

  if ($scope !== null) {
    // Exit if the scopes ended up with illegal characters or were not supplied as array.
    if ($scope === false || in_array(false, $scope, true)) {
      header($_SERVER['SERVER_PROTOCOL'] . " 400 Bad Request");
      echo "The scopes provided contained illegal characters.";
      exit;
    }

    // Turn scopes into a single string again.
    $scope = implode(' ', $scope);
  }

  $code = create_signed_code(ENCRYPTION_KEY, MAIN_SITE . $redirect_uri . $client_id, 5 * 60, $scope);

  $final_uri = $redirect_uri;
  if (strpos($redirect_uri, '?') === false) $final_uri .= '?';
  else $final_uri .= '&';

  $parameters = [
    "code" => $code,
    "me" => MAIN_SITE
  ];

  if ($state !== null) $parameters['state'] = $state;

  $final_uri .= http_build_query($parameters);
  header("Location: $final_uri", response_code: 302);

  syslog(LOG_INFO, "IndieAuth: login from " . _SERVER['REMOTE_ADDR'] . " for $me");
  exit;
}

$csrf_token = create_signed_code(ENCRYPTION_KEY, $client_id . $redirect_uri . $state, 2 * 60);
$year = date("Y"); // For loading the current stylesheet.

?><!DOCTYPE html>
<html>
  <head>
    <?php include __DIR__ . "/../partials/head.php" ?>
    <title>Sign in</title>
  </head>
  <body>
    <main>
      <h1>Sign in</h1>

      <?php if(normalize_url($client_id) !== CANONICAL) { ?>
        <p>
          You're logging in to
          <a href="<?= htmlspecialchars($client_id) ?>">
            <strong><?= htmlspecialchars($client_id) ?></strong>
          </a>
        </p>
      <?php } ?>
      
      <form action="" method="post">

        <?php if (strlen($scope) > 0) { ?>
          <?php if(normalize_url($client_id) === CANONICAL) { ?>
            <input type="hidden" name="scopes" value="<?= $scope ?>" />
          <?php } else { ?>
            <p>It is requesting the following permissions. Uncheck any you do not wish to grant:</p>

            <fieldset>
              <legend>Scopes</legend>
              <?php foreach (explode(" ", $scope) as $n => $checkbox) { ?>
                <div>
                  <input 
                    id="scope_<?php echo $n; ?>" 
                    type="checkbox" 
                    name="scopes[]" 
                    value="<?php echo htmlspecialchars($checkbox); ?>" 
                    checked
                  >
                  <label for="scope_<?php echo $n; ?>">
                    <?php echo $checkbox; ?>
                  </label>
                </div>
              </fieldset>
            <?php } ?>
        <?php } ?>
        
        <input type="hidden" name="_csrf" value="<?php echo $csrf_token; ?>" />

        <p>
          Logging in as <a href="<?= MAIN_SITE ?>">
            <strong><?= parse_url(MAIN_SITE, PHP_URL_HOST); ?></strong>
          </a>
        </p>

        <label>
            Password<br />
            <input type="password" name="password" id="password">
        </label>

        <input type="submit" name="submit" value="Sign in" />
        
        <p><small>After loggin in, you will be redirected to <?= htmlspecialchars($redirect_uri) ?></small></p>
      </form>
  </body>
</html>