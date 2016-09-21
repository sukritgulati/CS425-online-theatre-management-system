<?php
error_reporting(E_ERROR | E_PARSE);
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};
$level;
$points='';
if(isset($uID)){

    $stid = oci_parse($conn,"Select membershiplevel,points from customer where customer_id='$uID'");

    if(!$stid){
        $e = oci_error();
        echo $e;
    }
    $r = oci_execute($stid);
    if(!$r){
        $e = oci_error();
        echo $e;
    }

While ($row = oci_fetch_array($stid))  {
 
  $level=$row[0];
  $points=$row[1];

}    

}

 ////////query
    $stid = oci_parse($conn,"Select distinct title from movie where movie_id IN 
    	(Select movieid from review where review_id = origid AND movieid IS NOT NULL)");
    if(!$stid){
        $e = oci_error();
        echo $e;
    }
    $r = oci_execute($stid);
    if(!$r){
        $e = oci_error();
        echo $e;
    }

    $display = "
  <html><head/>
  <script language=JavaScript type=text/javascript>
  </script>
  <body>
  <form name=frmSelectUserInfo action=addComment.php  method=POST onsubmit= return vfunction()>
  Select a review to create/see existing:<br/><br/>
  Movies <br/>
  <select id=mID name=mID size=5 selectedIndex='-1' onchange='javascript:myFunction(this)'>";

While ($row = oci_fetch_array($stid))  {


  $display .= "<option value='$row[0]'>$row[0]</option>";
}


$display .="</select><br/><br/>
Theatres <br/>
<select id = tID name = tID size=5  selectedIndex='-1' onchange='javascript:myFunction(this)'>"; 


/////query2

 $stid = oci_parse($conn,"Select theatername from theater where theater_id IN (Select theaterid from review where review_id = origid AND theaterid IS NOT NULL)");
 if(!$stid){
        $e = oci_error();
        echo $e;
    }
    $r = oci_execute($stid);
    if(!$r){
        $e = oci_error();
        echo $e;
    }

    While ($row = oci_fetch_array($stid))  {


  $display .= "<option value='$row[0]'>$row[0]</option>";
}


if(isset($_POST['submit'])) {

}


if (!$uID) {
	$display .= "</select><br/><br/>
<input type='checkbox' name='general' id='general' value='general' checked='checked' onchange='javascript:myFunction(this)'>General <br/><br/> 
  <input type=submit value='Show Thread'> 
  </form>";
}else{
	$display .= "</select><br/><br/>
<input type='checkbox' name='general' id='general' value='general' checked='checked'  onchange='javascript:myFunction(this)'>General <br/><br/> 
  <input type=submit value='Comment on thread'>
 <a href='http://localhost/startThread.php'>
   <input type='button' value='Start a new Thread'/>
</a>&nbsp;&nbsp; your points $points
<input type='hidden' name='points' id = 'points' value=$points />
<input type='hidden' name='level' id = 'points' value=$level />
  </form><br/><br/><a href=menu.html>Return to menu</a>";
}

oci_free_statement($stid);
oci_close($conn);

$display .= "<script type='text/javascript'>function vfunction(){

alert('hello');
retun false;
  
}</script>";

$display .= "<script type='text/javascript'>function myFunction(obj){


   if(obj.id =='mID')
   {
   	document.getElementById('tID').selectedIndex = -1;
   	document.getElementById('general').checked = false;
   }
   else if(obj.id =='tID'){
   	document.getElementById('mID').selectedIndex = -1;
   	document.getElementById('general').checked = false;

   }
   else{
   	document.getElementById('mID').selectedIndex = -1;
   	document.getElementById('tID').selectedIndex = -1;
   }

   
   	
   	  
  
}</script>
</body></html>";

echo $display;

?>