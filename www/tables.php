<?php
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};

$stid = oci_parse($conn, "SELECT * FROM staff WHERE staff_id='$uID'");
if (!$stid) {
  $e = oci_error();
  echo "$e";  
}
$r = oci_execute($stid);
if (!$r) {
  $e = oci_error();
  echo "$e";  
}

$auth = false;
While ($row = oci_fetch_array($stid))  {
  if ($row['STAFF_ROLE'] == 'owner') {
    $auth = true;
  }
}
if(!$auth) {
  oci_free_statement($stid);
  oci_close($conn);
  echo "Not Authorized";
  exit;
}

//Get Tables
$stid = oci_parse($conn, "SELECT * FROM user_tables");
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
  <form name=frmSelectTable action=modifyTable.php method=POST>
  Select a Table to modify:<br/>
  <select id=tableName name=tableName size=10>";
While ($row = oci_fetch_array($stid))  {
  $display .= "<option value='$row[TABLE_NAME]'>$row[TABLE_NAME]</option>";
}
$display .= "</select><br/><br/>
  <input type=radio name=action value=insert>Insert Row<br/>
  <input type=radio name=action value=update>Update Data<br/>
  <input type=radio name=action value=delete>Delete Row<br/><br/>
  Input Values to Insert:<br/>
  <input type=textarea name=values size=100><br/><br/>
  <input type=submit>
  <br/><br/><a href=menu.html>Return to menu</a>
  </form>
  </body></html>";

oci_free_statement($stid);
oci_close($conn);

echo $display;
?>