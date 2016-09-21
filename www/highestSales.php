<?php
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};

$sql = "SELECT theatername, tsales
  FROM (SELECT theatername, SUM(ticketno) AS tsales
    FROM purchase, movieschedule, screen, theater
    WHERE theaterID = theater_ID AND screenID = screen_ID AND scheduleID = mschedule_ID
    GROUP BY theatername) A
  WHERE tsales = (SELECT MAX(sales) 
    FROM (SELECT theatername, SUM(ticketno) AS sales 
      FROM purchase, movieschedule, screen, theater
      WHERE theaterID = theater_ID AND screenID = screen_ID AND scheduleID = mschedule_ID
      GROUP BY theatername) B)";
$stid = oci_parse($conn, $sql);
if (!$stid) {
  $e = oci_error();
  echo "$e"; 	
}
$r = oci_execute($stid);
if (!$r) {
  $e = oci_error();
  echo "$e"; 	
}

$row = oci_fetch_array($stid);

echo "Theater $row[THEATERNAME] has the most online ticket sales with $row[TSALES]
  <br/><br/><a href=menu.html>Return to menu</a>";

oci_free_statement($stid);
oci_close($conn);

?>