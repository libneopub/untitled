<?php
// Basic statistics: logs which URLs where hit at which time.

namespace stats;

function record_view($path) {
  $year = date("Y");
  $month = date("m");
  
  \store\put_view($year, $month, [
    "datetime" => date("Y-m-d H:i:s"),
    "url" => $path
  ]);
}
