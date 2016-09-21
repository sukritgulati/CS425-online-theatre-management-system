<?php
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};


if (isset ($_POST['mSchID'])) {
 
  $mSchID = $_POST['mSchID'];
  $sql = "SELECT * FROM movieschedule, screen, theater, movie
    WHERE mschedule_id=$mSchID AND screenid=screen_id AND movieid=movie_id AND theaterid=theater_id";
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

  $sql = "SELECT * FROM login_user, customer
  WHERE user_id='$uID' AND customer_id='$uID'";
  $stid2 = oci_parse($conn, $sql);
  if  (!$stid) {
    $e = oci_error();
    echo "$e";  
  }
  $r = oci_execute($stid2);
  if (!$r) {
    $e = oci_error();
    echo "$e";  
  }
  
  $row = oci_fetch_array($stid);
  $row2 = oci_fetch_array($stid2);

  $display = "
    <html><head/>
    <script language=JavaScript type=text/javascript>
    </script>
    <body>
    <form name=frmPurchase action=CustProcessPurchase.php method=POST>
    <input type=number name=mSchID value=$mSchID hidden>
    <input type=number name=points value=$row2[POINTS] hidden>
    <input type=text name=memLevel value=$row2[MEMBERSHIPLEVEL] hidden>
    $row[TITLE] playing on screen $row[SCREENID] at $row[THEATERNAME]
      on $row[MOVIE_DATE] at $row[MOVIE_TIME] for $row[PRICE] per ticket<br/><br/>
    How many tickets?<br/><input type=text name=tickets><br/><br/>
    Name:<br/><input type=text name=name value=$row2[USER_NAME]><br/><br/>
    Credit Card Number:<br/><input type=text name=ccNumber value=$row2[CREDITCARDNO]><br/><br/>
    Credit Card Type:<br/><input type=text name=ccType value='$row2[CREDITCARDTYPE]'><br/><br/>
    Credit Card Expiration:<br/><input type=text name=ccExp value='$row2[CREDITCARDEXP]''><br/><br/>
    Phone Number:<br/><input type=text name=phone value=$row2[PHONE]><br/><br/>
    email:<br/><input type=text name=email value=$row2[EMAIL]><br/><br/>
    <input type=submit>
    </form>
    <br/><br/><a href=menu.html>Return to menu</a>
    </body></html>";

} else {

  $stid = oci_parse($conn, "SELECT * FROM movieschedule, screen, theater, movie
    WHERE screenid=screen_id AND movieid=movie_id AND theaterid=theater_id
    ORDER BY title, theatername, movie_date, movie_time");
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
    <form name=frmSelectMovie action=CustPurchase.php method=POST>
    Select a movie to purchase tickets to:<br/>
    <select id=mSchID name=mSchID size=10>";
  While ($row = oci_fetch_array($stid))  {
    $display .= "<option value='$row[MSCHEDULE_ID]'>$row[TITLE] playing at $row[THEATERNAME]
      on $row[MOVIE_DATE] at $row[MOVIE_TIME]</option>";
  }
  $display .= "</select><br/><br/>
    <input type=submit>
    </form>
    <br/><br/><a href=menu.html>Return to menu</a>
    </body></html>";

}


oci_free_statement($stid);
oci_close($conn);

echo $display;
?>