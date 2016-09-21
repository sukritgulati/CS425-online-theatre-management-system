<?php
include 'connectDB.php';

$stid = oci_parse($conn, "with m1 as (
select tname,count(distinct mid) as numberofmovies
from (
select theater.THEATER_ID as tid,theater.THEATERNAME as tname,screen.SCREEN_ID as scid, movieschedule.MOVIEID as mid 
from movieschedule,screen,theater
where movieschedule.SCREENID=screen.SCREEN_ID and screen.THEATERID=theater.THEATER_ID
)
group by tname
order by tname
)
select tname, numberofmovies
from m1
where numberofmovies>= all(select numberofmovies from m1)");

if (!$stid) {
  $e = oci_error();
  echo "$e";  
}

$r = oci_execute($stid);
if (!$r) {
  $e = oci_error();
  echo "$e";  
}

$display = "";
While ($row = oci_fetch_array($stid))  {
  $display .=  "<br/> Theater $row[0] is showing $row[1] movies";
}

 echo $display;

echo "<br/><br/><a href=menu.html>Return to menu</a>";

?>