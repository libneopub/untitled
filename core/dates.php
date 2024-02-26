<?php
// Functions for interacting with string dates in
// format YYYY-MM-DDTHH:ii:ss

function year($date) {
  return date("Y", strtotime($date));
}
