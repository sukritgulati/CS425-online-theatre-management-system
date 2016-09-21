

<?php

//echo "$_POST[reviewPoints] -- $_POST[newValue] -- $_POST[Gold]";

//Do not allow more points for Silver than Gold
error_reporting(E_ALL);
ini_set('display_errors', True);

/*if (($_POST[reviewTopic] == "Silver Review") and
    ($_POST[newValue] > $_POST[Gold]))  {
$display = "Can not set points higher for a Silver member review than a Gold member review.
  Please use back button to try again.";
} else {
  */


$reviewTopic =  $_POST['reviewTopic'];
//Connect to the DB
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};
//get review
$stid = oci_parse($conn, "select review_contents from review where origid = (select review_id from review where review_topic = '$reviewTopic')");
	
if (!$stid) {
  $e = oci_error();
  echo "$e"; 	
} //if (!$stid) {
$r = oci_execute($stid);
if (!$r) {
  $e = oci_error();
  echo "$e"; 	
} //if (!$r) {

$display = "<html>
<head>
<title>Review Data</title>
</head>
<script language=JavaScript type=text/javascript>
  </script>
<body>
<form>
Review for <br/>
$reviewTopic :<br/>
<table>
<td>";

//////////fetch result into array
    $count = 1;
    while (($row = oci_fetch_array($stid))&&($count<=3)){
    
    $display .= "<tr> $count ) $row[0]</tr></br>";
    $count= $count +1 ;
    }
    $display .= "</td><br/>
        </table>
        <a href='javascript: history.go(-1)'>Go Back</a>
        </form><br/><a href=menu.html>Return to menu</a>
        </body></html>";


//if $_POST[reviewPoints] == "Silver Review" {

echo $display;

?>