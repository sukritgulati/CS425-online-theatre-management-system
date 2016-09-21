<?php
include 'connectDB.php';

if(isset($_POST['tid']))
{
$sql="select login_user.USER_NAME as useer, schedule.SHIFTIMING as shift,staff.JOBDESCRIPTION as joob
from schedule,staff,login_user
where  schedule.STAFFID=staff.STAFF_ID and staff.STAFF_ID=login_user.USER_ID and staff.THEATERID='$_POST[tid]'";

$stid = oci_parse($conn, $sql);

$r = oci_execute($stid);

}

$display = "";
While ($row = oci_fetch_array($stid))  {
  $display .=  " <br/> user $row[0] is scheduled in shift $row[1] who's job description is $row[2] <br/>";
}

 echo $display;
 
 echo "<br/><br/><a href=menu.html>Return to menu</a>";

 ?>