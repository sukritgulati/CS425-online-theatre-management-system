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


if (isset ($_POST['colID'])) {
  $sql = "UPDATE $_POST[tableName] SET $_POST[colID]='$_POST[nValue]'
    WHERE";
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
    echo "Update Successful
    <br/><br/><a href=menu.html>Return to menu</a>";
    exit;
  }
}

$sql = "SELECT COLUMN_NAME FROM user_tab_columns WHERE TABLE_NAME='$_POST[tableName]'";
$stid = oci_parse($conn, $sql);
if (!$stid) {
  $e = oci_error();
  echo "$e";  
}
$r = oci_execute($stid);
if (!$r) {
  $e = oci_error();
  echo "$e";  
} else { $row = oci_fetch_array($stid); }

if ((isset ($_POST['action'])) && ($_POST['action'] == "delete")) {

  if ($_POST['tableName'] != "PURCHASE") {
    $sql = "DELETE FROM $_POST[tableName] WHERE $row[0] = '$_POST[rowID]'"; 
  } else {
    $IDs = split ("-", $_POST['rowID']);
    $sql = "DELETE FROM '$_POST[tableName]' WHERE '$row[0]' = '$IDs[0]' AND '$row[1]'' = '$IDs[1]'"; 
  }
  $stid = oci_parse($conn, $sql);
  if (!$stid) {
    $e = oci_error();
    echo "$e";  
  }
  $r = oci_execute($stid);
  if (!$r) {
    $e = oci_error();
    echo "$e";  
  } else { echo "Delete Seccessful"; }

} else {

  $display = "
    <html><head/>
    <script language=JavaScript type=text/javascript>
    </script>
    <body>
    <form name=frmSelectCol action=updateRow.php method=POST>
    <input type=hidden name=tableName value='$_POST[tableName]'>
    Select a Column to Update:<br/>
    <select id=colID name=colID size=10>
    <option value='$row[0]'>$row[0]</option>";
  While ($row = oci_fetch_array($stid)) { $display .= "<option value='$row[0]'>$row[0]</option>"; }
  $display .= "</select><br/><br/>
    New Value:<br/><input type=text name=nValue><br/><br/>
    <input type=submit>
    <br/><br/><a href=menu.html>Return to menu</a>
    </form>
    </body></html>";
  echo $display;

} 

oci_free_statement($stid);
oci_close($conn);

?>
