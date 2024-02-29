<?php
// Functions for interacting with string dates in
// format YYYY-MM-DDTHH:ii:ss

namespace dates;

function year($date) {
  return date("Y", strtotime($date));
}
