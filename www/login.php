<?php
include 'connectDB.php';

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Text to send if user hits Cancel button';
};

$uID = $_SERVER['PHP_AUTH_USER'];

$stid = oci_parse($conn, "SELECT * FROM login_user WHERE user_id='$uID'");
if (!$stid) {
  $e = oci_error();
  echo "$e";  
} //if (!$stid) {
$r = oci_execute($stid);
if (!$r) {
  $e = oci_error();
  echo "$e";  
} //if (!$r) {

$row = oci_fetch_array($stid);
if ($row['USER_PASSWORD'] == $_SERVER['PHP_AUTH_PW']) { $auth = true; }
else { $auth = false; };

oci_free_statement($stid);
oci_close($conn);

?>