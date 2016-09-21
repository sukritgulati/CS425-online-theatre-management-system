<?php
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};
//get review
$stid = oci_parse($conn, "select review_topic,review_contents from review where origid = review_id AND rownum <=3 order by review_id desc");
	
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
3 Mosts recent reviews: <br/>
<table>
<td>";

//////////fetch result into array
    $count = 1;
    while (($row = oci_fetch_array($stid))&&($count<=3)){
    
    $display .= "<tr> $count ) Topic: $row[0]</tr><tr>---> $row[1]</tr></br><br/>";
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

?>