<!-- Joshua D. LaBorde -->

<?php
include 'connectDB.php';

/* CreditCardCo function for testing is implemented using the php rand function to
randomly generate a number between 0 & 9.  Numbers higher than 7 are used to indicate
an issue with the card.  It could be invalid information or could be insufficient funds.*/

$sql = "SELECT * FROM movieschedule, screen, theater, movie
  WHERE mschedule_id='$_POST[mSchID]' AND screenid=screen_id AND movieid=movie_id AND theaterid=theater_id";
$stid = oci_parse($conn, $sql);
if (!$stid) {
  $e = oci_error();
  echo "$e"; 	
} //if (!$stid) {
$r = oci_execute($stid);
if (!$r) {
  $e = oci_error();
  echo "$e"; 	
} //if (!$r) {

$row = oci_fetch_array($stid);

//Randomly decide if credit card information is valid and if there are sufficient funds.
$goodToBuy = rand(0, 9);
if (($goodToBuy < 7) and ($_POST['ccNumber'] != '')) {

  $un = str_replace("'","''",$_POST['name']);
  $p = str_replace("'","''",$_POST['phone']);
  $e = str_replace("'","''",$_POST['email']);
  $sql = "INSERT INTO login_user (user_id, user_name, zip, phone) 
    VALUES ('$e', '$un', '00000', '$p')";
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
  $r = oci_commit($conn);
  if (!$r) {
    $e = oci_error();
    echo "$e";  
  }

  $cn = str_replace("'","''",$_POST['ccNumber']);
  $sql = "INSERT INTO customer (customer_id, email, creditcardno, membershiplevel, points)
    VALUES ('$e', '$un', '$cn', 'none', 0 )";
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
  $r = oci_commit($conn);
  if (!$r) {
    $e = oci_error();
    echo "$e";  
  }

  $t = str_replace("'","''",$_POST['tickets']);
  $sql = "INSERT INTO purchase (scheduleid, customerid, ticketno)
    VALUES ('$_POST[mSchID]', '$e', '$t')";
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
  $r = oci_commit($conn);
  if (!$r) {
    $e = oci_error();
    echo "$e";  
  }


  $dollars = $_POST['tickets'] * $row['PRICE']; //Used to calculate points if they register
  $display = "
    <html><head/>
    <script language=JavaScript type=text/javascript>
    </script>
    <body>
    <form name=frmPurchaseConf action=Register.php method=POST>
    <input type=text name=name value='$_POST[name]' hidden>
    <input type=text name=ccNumber value=$_POST[ccNumber] hidden>
    <input type=text name=phone value=$_POST[phone] hidden>
    <input type=text name=email value=$_POST[email] hidden>
    <input type=text name=dollars value=$dollars hidden>
    Purchase successful.  Would you like to Register?<br/><br/>
    <input type=submit value='Register'>
    </form>
    <br/><br/><a href=menu.html>Return to menu</a>
    </body></html>";

} else {

  $display = "There was an issue with your credit card.
    Please use the back button to try again";

}//if (($goodToBuy > 6) and ($_POST[ccNumber] != '')) {

oci_free_statement($stid);
oci_close($conn);

echo $display;

?>