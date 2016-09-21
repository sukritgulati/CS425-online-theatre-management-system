<?php
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};

$msg = "";
if (isset ($_POST['sID'])) {
  $sql = "UPDATE staff SET staff_role='delegate' WHERE staff_ID='$_POST[sID]'";
  $stid = oci_parse($conn, $sql);
  if (!$stid) {
    $e = oci_error();
    echo "$e";  
  }
  $r = oci_execute($stid);
  if (!$r) {
    $e = oci_error();
    echo "$e";  
  } else {
    $msg = "Update Sucessful<br/><br/>"; 
  }
}

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

//Get Staff
$stid = oci_parse($conn, "SELECT * FROM login_user, staff
	WHERE user_ID = staff_ID");
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
  $msg
  <form name=frmSelectStaff action=delegateSchedule.php method=POST>
  Select a Staff member to delegate scheduling privileges to:<br/>
  <select id=sID name=sID size=10>";
While ($row = oci_fetch_array($stid))  {
  $display .= "<option value='$row[USER_ID]'>$row[USER_ID] - $row[USER_NAME]</option>";
}
$display .= "</select><br/><br/>
  <input type=submit>
  </form>
  <br/><br/><a href=menu.html>Return to menu</a>
  </body></html>";

oci_free_statement($stid);
oci_close($conn);

echo $display;
?>