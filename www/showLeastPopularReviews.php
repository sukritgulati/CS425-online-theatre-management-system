<?php
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};
//get review
$stid = oci_parse($conn, "select review.review_topic,review.review_contents from review ,(select origid ,
  count(*) as col from review rev group by origid) sum where review.review_id =sum.origid and sum.col=1");
	
if (!$stid) {
  $e = oci_error();
  echo $e; 	
} //if (!$stid) {
$r = oci_execute($stid);
if (!$r) {
  $e = oci_error();
  echo $e; 	
} //if (!$r) {
$display = "<html>
<head>
<title>Review Data</title>
</head>
<script language=JavaScript type=text/javascript>
  </script>
<body>
<form>
Least popular discussion thread: <br/>
<table>
<td>";

//////////fetch result into array
    $count = 1;
    while (($row = oci_fetch_array($stid))&&($count<=3)){
    
    $display .= "<tr> $count ) Topic: $row[0]</tr><br/><tr>---> $row[1]</tr></br><br/>";
    $count= $count +1 ;
    }
    $display .= "</td><br/>
        </table>
        
        </form><br/><a href=menu.html>Return to menu</a>
        </body></html>";


//if $_POST[reviewPoints] == "Silver Review" {

echo $display;


?>