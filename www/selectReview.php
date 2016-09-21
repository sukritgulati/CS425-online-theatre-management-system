
<?php
    ///////sukrit Gulati

    
    error_reporting(E_ALL);
    ini_set('display_errors', True);
    
    
  /*  //////connect to server
    $db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = fourier.cs.iit.edu)(PORT = 1521)))(CONNECT_DATA=(SID=orcl)))" ;
    $conn = OCILogon("asharm36", "cs425", $db);
    if (!$conn) {
        $e = oci_error();
        echo $e;
    }*/
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};
    
    ////////query
    $stid = oci_parse($conn,"Select review_topic from review where review.review_id = review.origid");
    if(!$stid){
        $e = oci_error();
        echo $e;
    }
    $r = oci_execute($stid);
    if(!$r){
        $e = oci_error();
        echo $e;
    }



    //////
    $display = "
  <html><head/>
  <script language=JavaScript type=text/javascript>
  </script>
  <body>
  <form name=frmReview action=showReview.php method=POST>
  Current review topics <br/>
  Select a review topic to see details:
  <select id=reviewTopic name=reviewTopic MULTIPLE SIZE=5 required>";


    //////////fetch result into array
    while ($row = oci_fetch_array($stid)) {
    
    $display .= "<option value ='$row[0]'>$row[0]</option>";
    }
    $display .= "</select><br/>
        <input type = submit>
        </form><br/><a href=menu.html>Return to menu</a>
        </body></html>";

    echo $display;

 
    ?>