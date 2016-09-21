<?php
include 'connectDB.php';



// get theater name

$stid=oci_parse($conn, "select * from theater");
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
 
  <form name=selecttheatername action=EmployeeOnTheater2.php method=POST>
  Select a theater where employee information is required:<br/>
  <select id=tid name=tid size=10>";
  while ($row = oci_fetch_array($stid))
  {
	    $display .= "<option value='$row[THEATER_ID]'>$row[THEATER_ID] - $row[THEATERNAME]</option>";
  }
  
$display .= "</select><br/><br/>
  <input type=submit>
  </form>
  </body></html>";
echo $display;



// running main query


?>



