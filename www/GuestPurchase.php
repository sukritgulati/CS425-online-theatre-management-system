<!-- Joshua D. LaBorde -->
<?php
include 'connectDB.php';


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

  $row = oci_fetch_array($stid);

  $display = "
    <html><head/>
    <script language=JavaScript type=text/javascript>
    </script>
    <body>
    <form name=frmPurchase action=ProcessPurchase.php method=POST>
    <input type=number name=mSchID value=$mSchID hidden>
    $row[TITLE] playing on screen $row[SCREENID] at $row[THEATERNAME]
      on $row[MOVIE_DATE] at $row[MOVIE_TIME] for $$row[PRICE] per ticket<br/><br/>
    How many tickets?<br/><input type=text name=tickets><br/><br/>
    Name:<br/><input type=text name=name><br/><br/>
    Credit Card Number:<br/><input type=text name=ccNumber><br/><br/>
    Phone Number:<br/><input type=text name=phone><br/><br/>
    email:<br/><input type=text name=email><br/><br/>
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
    <form name=frmSelectMovie action=GuestPurchase.php method=POST>
    Select a Movie to Purchase tickets for:<br/>
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