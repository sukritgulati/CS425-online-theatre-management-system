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

if ($_POST['action'] == "insert") { 
  $sql = "INSERT INTO $_POST[tableName] VALUES($_POST[values])";
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
    $msg =  "Insert Seccessful<br/><br/>";
  }
} else {

  $sql = "SELECT * FROM $_POST[tableName]";
  $stid = oci_parse($conn, $sql);
  echo $sql;
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
    <form name=frmSelectRow action=updateRow.php method=POST>
    <input type=hidden name=tableName value='$_POST[tableName]'>
    Select a Row to Update or Delete:<br/>
    <select id=rowID name=rowID size=10>";
  if($_POST['tableName'] != "PURCHASE") {  
    While ($row = oci_fetch_array($stid))  {
      $display .= "<option value='$row[0]'>";
      foreach ($row as $value) { $display .= "- $value - "; }
      $display .= "</option>";
    }
  } else {
    While ($row = oci_fetch_array($stid))  {
      $display .= "<option value='$row[0]-$row[1]'>";
      foreach ($row as $value) { $display .= "- $value - "; }
      $display .= "</option>";
    }
  }
  $display .= "</select><br/><br/>
    <input type=radio name=action value=update>Update Data<br/>
    <input type=radio name=action value=delete>Delete Row<br/><br/>
    <input type=submit>
    <br/><br/><a href=menu.html>Return to menu</a>
    </form>
    </body></html>";

} 

oci_free_statement($stid);
oci_close($conn);

echo $display;
?>
