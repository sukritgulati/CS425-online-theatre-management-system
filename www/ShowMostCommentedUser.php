<?php
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};
//get review
$stid = oci_parse($conn, "select userid from (select count(userid) as sum,userid from review GROUP BY USERID order by sum desc ) where rownum <=1 ");
	
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
The user who has Commented the most: <br/>
<table>
<td>";

//////////fetch result into array
    $count = 1;
    while (($row = oci_fetch_array($stid))&&($count<=3)){
    
    $display .= "<tr> $row[0]</tr></br><br/>";
    $count= $count +1 ;
    }
    $display .= "</td><br/>
        </table>
        
        </form><br/><a href=menu.html>Return to menu</a>
        </body></html>";


//if $_POST[reviewPoints] == "Silver Review" {

echo $display;


?>