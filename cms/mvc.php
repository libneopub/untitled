<?php
// Shitty infrastructure that is only loosely based 
// on the MVC-pattern.

function fail($message, $why = 500) {
  global $view;
  http_response_code($why);
  \renderer\render_error($message);
  include $view;
  exit;
}
