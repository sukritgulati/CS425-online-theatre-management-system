<?php
error_reporting(E_ERROR | E_PARSE);
include 'login.php';
include 'connectDB.php';

if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};

if (isset ($_POST['comments'])) {
  $count;
    //Update info
  $qmID;
  $qtID;
  $qmID=$_POST['mID'];
  $qtID=$_POST['tID'];
 
      $sql = "select MAX(review_id) from review";
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

While ($row = oci_fetch_array($stid))  {

  $count= $row[0];
  $count = $count+1;
}





    ////////get a movieid/theatreid/general
   if (!empty($qmID)) {
  # code...

  $sql = "insert into Review (review_id,review_topic,review_rating,review_contents,theaterid,movieid,userid,origid) values 
  ($count,'$_POST[topic]',4,'$_POST[comments]',null,'$qmID','$uID',$count)";
}elseif (!empty($qtID)) {
  # code...
  $sql = "insert into Review (review_id,review_topic,review_rating,review_contents,theaterid,movieid,userid,origid) values 
  ($count,'$_POST[topic]',4,'$_POST[comments]','$qtID',null,'$uID',$count)";
}else{
  $sql = "insert into Review (review_id,review_topic,review_rating,review_contents,theaterid,movieid,userid,origid) values 
  ($count,'$_POST[topic]',4,'$_POST[comments]',null,null,'$uID',$count)";
}
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

    echo "success";
 

}
 ////////query
    $stid = oci_parse($conn,"Select movie_id,title from movie");
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
  <form name=frmSelectUserInfo action=startThread.php method=POST>
  Create a new Thread:<br/><br/>
  Movies <br/>
  <select id=mID name=mID size=5 selectedIndex='-1' onchange='javascript:myFunction(this)'>";

While ($row = oci_fetch_array($stid))  {


  $display .= "<option value='$row[0]'>$row[1]</option>";
}


$display .="</select><br/><br/>
Theatres <br/>
<select id = tID name = tID size=5  selectedIndex='-1' onchange='javascript:myFunction(this)'>"; 


/////query2

 $stid = oci_parse($conn,"Select theater_id,theatername from theater");
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


  $display .= "<option value='$row[0]'>$row[1]</option>";
}




$display .= "</select><br/><br/>
<input type='checkbox' name='general' id='general' value='general' checked='checked' onchange='javascript:myFunction(this)'>General <br/><br/> 
  <br/><br/>Topic Heading:&nbsp;<input type=text name='topic' required><br/><br/>Content:&nbsp<textarea name='comments' id='comments' style='font-family:sans-serif;font-size:1.0em;width: 600px; height: 100px;' required></textarea>
 </br> <input type=submit value='Post'>
 <a href='http://localhost/selectReviewCategory.php'>
   <input type='button' value='See all comments'/>
</a>
  </form><a href=menu.html>Return to menu</a>";


oci_free_statement($stid);
oci_close($conn);

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