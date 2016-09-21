<!-- Joshua D. LaBorde -->

<?php
include 'connectDB.php';

 
if ((isset($_POST['pEmail'])) && ($_POST['pEmail'] != "")) {

  $u = str_replace("'","''",$_POST['userID']);
  $un = str_replace("'","''",$_POST['name']);
  $s = str_replace("'","''",$_POST['addr']);
  $z = str_replace("'","''",$_POST['zip']);
  $p = str_replace("'","''",$_POST['phone']);
  $pw = str_replace("'","''",$_POST['password']);
  $pe = str_replace("'","''",$_POST['pEmail']);
  $sql = "UPDATE login_user SET user_ID='u', user_name='$un',
    street='$s', zip='$z', phone='$p', user_password='$pw'
    WHERE user_id='$pe'";
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

  $stid = oci_parse($conn, "SELECT amount FROM Rewards WHERE 'trans'='None Purchase'");
  if (!$stid) {
    $e = oci_error();
    echo "$e";  
  }
  $r = oci_execute($stid);
  if (!$r) {
    $e = oci_error();
    echo "$e";  
  }

  $amount = ocifetch($stid);
  $points = $_POST['dollars'] * $amount;

  $ct = str_replace("'","''",$_POST['ccType']);
  $ce = str_replace("'","''",$_POST['ccExp']);
  $cn = str_replace("'","''",$_POST['ccNumber']);
  $stid = oci_parse($conn, "UPDATE customer SET customer_id='$u',
    email='$e', creditcardtype='$ct', creditcardexp='$ce',
    creditcardno='$cn', points=$points WHERE customer_id='$pe'");
  if (!$stid) {
    $e = oci_error();
    echo "$e";  
  }
  $r = oci_execute($stid);
  if (!$r) {
    $e = oci_error();
    echo "$e";  
  }


} else {

  $u = str_replace("'","''",$_POST['userID']);
  $un = str_replace("'","''",$_POST['name']);
  $s = str_replace("'","''",$_POST['addr']);
  $z = str_replace("'","''",$_POST['zip']);
  $p = str_replace("'","''",$_POST['phone']);
  $pw = str_replace("'","''",$_POST['password']);
  $pe = str_replace("'","''",$_POST['pEmail']);
  $sql = "INSERT INTO login_user VALUES ('$u','$un', '$s', '$z', '$p', '$pw')";
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

  $e = str_replace("'","''",$_POST['email']);
  $ct = str_replace("'","''",$_POST['ccType']);
  $ce = str_replace("'","''",$_POST['ccExp']);
  $cn = str_replace("'","''",$_POST['ccNumber']);
  $sql = "INSERT INTO customer VALUES ('$u', '$e','$ct', '$ce', '$cn', 'none', 0)";
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


}


oci_free_statement($stid);
oci_close($conn);

$display = "<html><head/>
  <script language=JavaScript type=text/javascript>
  </script>
  <body>
  <form name=frmPurchase action=CustPurchase.php method=POST>
  <input type=text name=userID value='$_POST[userID]' hidden>
  Registation Successful<br/><br/>
  <input type=submit value='Purchase Tickets'>
  </form>
  <br/><br/><a href=menu.html>Return to menu</a>
  </body></html>";

echo $display;

?>