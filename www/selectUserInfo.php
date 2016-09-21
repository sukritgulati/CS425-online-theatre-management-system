<?php
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};

//Only the Owner or Web Admin can access other users info.
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
  if ($row['STAFF_ROLE'] == 'owner' or $row['STAFF_ROLE'] == 'website admin') {
    $auth = true;
  }
}

if(!$auth) {
  oci_free_statement($stid);
  oci_close($conn);
  echo "Not Authorized";
  exit;
}

//Get Users
$stid = oci_parse($conn, "SELECT * FROM login_user, customer
	WHERE user_ID = customer_ID");
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
  <form name=frmSelectUserInfo action=viewUpdateUserInfo.php method=POST>
  Select a User to view / update:<br/>
  <select id=cID name=cID size=10>";
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