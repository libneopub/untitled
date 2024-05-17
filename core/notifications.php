<?php
// Handles sending email notifications.

namespace notifications;

function new_webmention($source, $target) {
    $subject = "New webmention from $source";
    $message = "Your page $target was mentioned on $source!";

    send_to_webmaster($subject, $message);
}

function send_to_webmaster($subject, $message) {
    $message .= "\r\n\r\n";
    $message .= "This is an automated notification. ";
    $message .= "If you don't want to receive these anymore, you can disable them in the CMS".

    send_email(NOTIFICATIONS_ADMIN, "[" . HOST . "] $subject", $message);
}

function send_email($to, $subject, $message) {
  $headers = [
    "From" => NOTIFICATIONS_SENDER,
    //"Bcc" => NOTIFICATIONS_ADMIN,
    "Content-Type" => "text/plain; charset=utf-8",
    "X-Powered-By" => "Pubb (v" . PUBB_VERSION . ")"
  ];

  mail($to, $subject, $message, $headers);
}
