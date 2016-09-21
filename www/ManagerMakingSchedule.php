<?php
include 'connectDB.php';



// get theater name

$stid=oci_parse($conn, "select * from staff,login_user where staff_id=user_id");
if (!$stid) {
  $e = oci_error();
  echo "$e";  
}
$r = oci_execute($stid);
if (!$r) {
  $e = oci_error();
  echo "$e";  
}


$display = "
  <html><head/>
  <script language=JavaScript type=text/javascript>
  </script>
  <body>
 
  <form name=staffname action=qg6.php method=POST>
  Select a staff whose schedule has to be inserted:<br/>
  <select id=stfid name=stfid size=10>";
  while ($row = oci_fetch_array($stid))
  {
	    $display .= "<option value='$row[STAFF_ID]'>$row[STAFF_ID] - $row[USER_NAME]</option>";
  }
  
$display .= "</select><br/><br/>
  <input type=submit>
  </form>
  </body></html>";
echo $display;



// running main query


?>
