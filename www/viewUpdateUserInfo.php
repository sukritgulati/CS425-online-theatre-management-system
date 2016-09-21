<?php
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};

$msg = "";

if (isset ($_POST['name'])) {
  //Update info
  $uID = $_POST['userID'];
  $sql = "UPDATE LOGIN_USER SET USER_NAME='$_POST[name]', STREET='$_POST[addr]',
  	ZIP='$_POST[zip]', PHONE='$_POST[phone]', USER_PASSWORD='$_POST[password]'
  	WHERE USER_ID='$uID'";
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
    $t1 = true;
  }

  $sql = "UPDATE customer SET eMAIL='$_POST[email]', CREDITCARDNO='$_POST[ccNumber]',
  	CREDITCARDTYPE='$_POST[ccType]', CREDITCARDEXP='$_POST[ccExp]'
  	WHERE CUSTOMER_ID='$uID'";
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
    $t2 = true;
  }

  if ($t1 AND $t2) { $msg = "Update Sucessful<br/><br/>"; }

} else if (isset($_POST['cID'])) { $uID = $_POST['cID']; }

$sql = "SELECT * FROM login_user, customer WHERE user_id='$uID' AND customer_id='$uID'";
$stid = oci_parse($conn, $sql);
if (!$stid) {
  $e = oci_error();
  echo "$e";  
}
$r = oci_execute($stid);
if (!$r) {
  $e = oci_error();
  echo "$e";  
}

$row = oci_fetch_array($stid);

$display = "
  <html><head/>
  <script language=JavaScript type=text/javascript>
  </script>
  <body>
  $msg
  <form name=frmViewUpdateUserInfo action=viewUpdateUserInfo.php method=POST>
  Name:<br/><input type=text name=name value='$row[USER_NAME]'><br/><br/>
  User ID:<br/><input type=text name=userID value='$row[USER_ID]' readonly><br/><br/>
  Password:<br/><input type=text name=password value='$row[USER_PASSWORD]'><br/><br/>
  email:<br/><input type=text name=email value='$row[EMAIL]'><br/><br/>
  Credit Card Number:<br/><input type=text name=ccNumber value='$row[CREDITCARDNO]'><br/><br/>
  Credit Card Type:<br/><input type=text name=ccType value='$row[CREDITCARDTYPE]'><br/><br/>
  Credit Card Expiration:<br/><input type=text name=ccExp value='$row[CREDITCARDEXP]''><br/><br/>
  Phone Number:<br/><input type=text name=phone value='$row[PHONE]'><br/><br/>
  Street Address:<br/><input type=text name=addr value='$row[STREET]'><br/><br/>
  Zip Code:<br/><input type=text name=zip value='$row[ZIP]'><br/><br/>
  Membership Level:<br/><input type=text name=memlevel value='$row[MEMBERSHIPLEVEL]' readonly><br/><br/>
  Points:<br/><input type=text name=points value='$row[POINTS]' readonly><br/><br/>
  <input type=submit>
  <br/><br/><a href=menu.html>Return to menu</a>
  </body></html>";


oci_free_statement($stid);
oci_close($conn);

echo $display;
?>