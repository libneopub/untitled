<?php
// Basic statistics: logs which URLs where hit at which time.

namespace stats;

function record_view($path) {
  \store\put_view(date("Y"), date("m"), $path);
}
