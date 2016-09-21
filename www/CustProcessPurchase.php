<!-- Joshua D. LaBorde -->

<?php
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};

/* CreditCardCo function for testing is implemented using the php rand function to
randomly generate a number between 0 & 9.  Numbers 8 or higher are used to indicate
an issue with the card.  It could be invalid information or could be insufficient funds.*/

$m = str_replace("'","''",$_POST['mSchID']);
$sql = "SELECT * FROM movieschedule, screen, theater, movie
  WHERE mschedule_id='$m' AND screenid=screen_id AND movieid=movie_id AND theaterid=theater_id";
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
if (($goodToBuy < 8) and ($_POST['ccNumber'] != '')) {

  $n = str_replace("'","''",$_POST['name']);
  $p = str_replace("'","''",$_POST['phone']);
  $sql = "UPDATE login_user SET user_name='$n', phone='$p' WHERE user_id='$uID'";
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

  
  $dollars = $_POST['tickets'] * $row['PRICE'];
  $stid = oci_parse($conn, "SELECT * FROM REWARDS");
  if (!$stid) {
    $e = oci_error();
    echo "$e";  
  } //if (!$stid) {
  $r = oci_execute($stid);
  if (!$r) {
    $e = oci_error();
    echo "$e";  
  } //if (!$r) {

  
  if ($_POST['memLevel'] == "none") {
    While ($row = oci_fetch_array($stid))  {
      if ($row['TRANS'] == "None Purchase") { $amount = $row['AMOUNT']; }
      elseif ($row['TRANS'] == "Silver Membership") { $level = $row['AMOUNT']; }
      $newMem = "silver";
    }
  } elseif ($_POST['memLevel'] == "silver") {
    While ($row = oci_fetch_array($stid))  {
      if ($row['TRANS'] == "Silver Purchase") { $amount = $row['AMOUNT']; }
      elseif ($row['TRANS'] == "Gold Membership") { $level = $row['AMOUNT']; }
      $newMem = "gold";
    }
  } elseif ($_POST['memLevel'] == "gold") {
    While ($row = oci_fetch_array($stid))  {
      if ($row['TRANS'] == "Gold Purchase") { $amount = $row['AMOUNT']; }
      elseif ($row['TRANS'] == "Platinum Membership") { $level = $row['AMOUNT']; }
      $newMem = "platinum";
    }
  } else {
    While ($row = oci_fetch_array($stid))  {
      if ($row['TRANS'] == "Platinum Purchase") { $amount = $row['AMOUNT']; }
       $level = "";
       $newMem = "Nope";
    }
  }

  $display = "";
  $newPoints = $_POST['points'];
  $newPoints += $dollars * $amount;
  if (($newPoints >= $level) && ($newMem != "Nope")) {
    $display = "Congrats! You're new level is $newMem.<br/><br/>";
    $mem = $newMem;
  } else { $mem = $_POST['memLevel']; }

  $e = str_replace("'","''",$_POST['email']);
  $ct = str_replace("'","''",$_POST['ccType']);
  $ce = str_replace("'","''",$_POST['ccExp']);
  $cn = str_replace("'","''",$_POST['ccNumber']);
  $sql = "UPDATE customer SET email='$e', creditcardtype = '$ct',
    creditcardexp='$ce', creditcardno='$cn', membershiplevel='$mem', points='$newPoints'
    WHERE customer_id='$uID'";
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
  $stid = oci_parse($conn, "INSERT INTO purchase (scheduleid, customerid, ticketno)
    VALUES ('$_POST[mSchID]', '$uID', '$t')");
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

  $display .= "Purchase successful.";

} else { 
  $display = "There was an issue with your credit card.  Please use the back button to try again.";
}

oci_free_statement($stid);
oci_close($conn);

$display .= "<br/><br/><a href=menu.html>Return to menu</a>";
echo $display;

?>