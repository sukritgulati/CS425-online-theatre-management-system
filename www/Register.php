<!-- Joshua D. LaBorde -->

<?php
include 'connectDB.php';

if (isset($_POST['email'])) {

/*$stid = oci_parse($conn, "SELECT * FROM login_user, customer
  WHERE user_id=customer_id AND email='$_POST[email]'");
if (!$stid) {
  $e = oci_error();
  echo "$e";  
}
$r = oci_execute($stid);
if (!$r) {
  $e = oci_error();
  echo "$e";  
}
*/

  $email = $_POST['email'];
  $dollars = $_POST['dollars'];
  $name = $_POST['name'];
  $ccNumber = $_POST['ccNumber'];
  $phone = $_POST['phone'];
} else {
  $email = "";
  $dollars = "";
  $name = "";
  $ccNumber = "";
  $phone = "";
}

$display = "
  <html><head/>
  <script language=JavaScript type=text/javascript>
  </script>
  <body>
  <form name=frmPurchase action=ProcessRegistration.php method=POST>
  <input type=text name=pEmail value='$email' hidden>
  <input type=text name=dollars value='$dollars' hidden>
  Name:<br/><input type=text name=name value='$name'><br/><br/>
  User ID:<br/><input type=text name=userID value='$email'><br/><br/>
  Password:<br/><input type=text name=password><br/><br/>
  email:<br/><input type=text name=email value='$email'><br/><br/>
  Credit Card Number:<br/><input type=text name=ccNumber value='$ccNumber'><br/><br/>
  Credit Card Type:<br/><input type=text name=ccType><br/><br/>
  Credit Card Expiration:<br/><input type=text name=ccExp><br/><br/>
  Phone Number:<br/><input type=text name=phone value='$phone'><br/><br/>
  Street Address:<br/><input type=text name=addr><br/><br/>
  Zip Code:<br/><input type=text name=zip><br/><br/>
  <input type=submit>
  </form>
  <br/><br/><a href=menu.html>Return to menu</a>
  </body></html>";
 

//oci_free_statement($stid);
//oci_close($conn);

echo $display;

?>