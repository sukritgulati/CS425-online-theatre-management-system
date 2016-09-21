<?php
error_reporting(E_ERROR | E_PARSE);
include 'login.php';
include 'connectDB.php';


if(!$auth) {
  oci_close($conn);
  echo "Incorect Login";
  exit;
};


$qtID;
  $qmID;
  $count=0;
  $tname;
  $mname;
  $points='';
  $level='';
  if($uID){
$points = $_POST['points'];
$level = $_POST['level'];
  }
///////check if commeneted
if (isset ($_POST['comments'])) {
  //Update info

 
  if (isset ($_POST['topic'])) {
  	$newID=$_POST['topic'];

  	////////get a movieid/theatreid/general
  	$sql = "select theaterid,movieid from review where review_id = '$newID'";
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
/*	
		
	}elseif (empty($row[0])) {
		# code...
		$qmID=$row[1];
	}else{
		$qtID=$row[1];
	}
	*/
	$qmID=$row[1];
	$qtID=$row[0];

}
////////get theater or movie name

$flag =0;
if(!empty($qmID)){
	$sql = "select title from movie where movie_id = '$qmID'";
}elseif (!empty($qtID)) {
	$sql = "select theatername from theater where theater_id = '$qtID'";
}else{
$flag=1;

}
if ($flag==0) {
	# code...

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

	if (!empty($qmID)) {
		# code...
		$mname=$row[0];
	}else{
		$tname=$row[0];
	}
	

}
}
///////////get review count
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
  }
///////inserting a comment
if (!empty($qmID)) {
	# code...

	$sql = "insert into Review (review_id,review_topic,review_rating,review_contents,theaterid,movieid,userid,origid) values 
	($count,null,4,'$_POST[comments]',null,'$qmID','$uID','$newID')";
}elseif (!empty($qtID)) {
	# code...
	$sql = "insert into Review (review_id,review_topic,review_rating,review_contents,theaterid,movieid,userid,origid) values 
	($count,null,4,'$_POST[comments]','$qtID',null,'$uID','$newID')";
}else{
	$sql = "insert into Review (review_id,review_topic,review_rating,review_contents,theaterid,movieid,userid,origid) values 
	($count,null,4,'$_POST[comments]',null,null,'$uID','$newID')";
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
  } else{
  	echo "Updated";
  }
/////////updating credit points

  $lev=ucfirst($level);
  $lev .=" Review"; 
$sql = "select trans,amount from rewards where trans = '$lev'";
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

  //$level= $row[0];
  $points = $points + $row[1];
}

if($points>=500&&$points<1000){
$level="gold";
}elseif ($points>=1000) {
  $level = "platinum";
}elseif ($points>0&&$points<100) {
  # code...
  $level= "silver";
}

$sql = "update customer set points= $points , membershiplevel = '$level' where customer_id = '$uID'";

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
  }


 $display = "
  <html><head/>
  <script language=JavaScript type=text/javascript>
  </script>
  <body>
  <form name=showcomment action=addComment.php method=POST>";

if (isset ($_POST['mID'])||isset ($mname)) {
	$var;
	if (isset ($_POST['mID'])) {
		# code...
$var =$_POST['mID']; 
	}else{
		$var =$mname; 
	}
	
	
	$display .= "Reviews for the Movie $var: ";
	# code...
	$sql = "select review_topic,review_contents,review_id from review where ORIGID IN (Select review_id from review where movieid IN 
		(select movie_id from movie where title = '$var')) ORDER BY ORIGID,REVIEW_ID";
}elseif(isset ($_POST['tID'])||isset ($tname)){
	# code...
	$var; 
	if (isset ($_POST['tID'])) {
		# code...
	$var =$_POST['tID']; 
	}else{
		$var =$tname; 
	}
	
	$display .= "Reviews for the Theatre $var: ";
	
	$sql = "select review_topic,review_contents,review_id from review where ORIGID IN (Select review_id from review where theaterid IN 
		(select theater_id from theater where theatername = '$var')) ORDER BY ORIGID,REVIEW_ID";
}else{

	$display .= "General reviews: ";
	
	$sql = "select review_topic,review_contents,review_id from review where ORIGID IN (Select review_id from review where theaterid IS
	NULL AND movieid IS NULL) ORDER BY ORIGID,REVIEW_ID";
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
While ($row = oci_fetch_array($stid))  {

	if(empty($row[0])){
	
		$display .= "<br/>&nbsp;&nbsp;&nbsp;Comment: $row[1]";
	}else{
  $display .= " <br/><br/><input type='radio' name='topic' value=$row[2] required>$row[0]<br>
  &nbsp;&nbsp;$row[1]";
}
}
if(!$uID){
	$display .="<br/><br/>
	<a href='http://localhost/selectReviewCategory.php'>
   <input type='button' value='back'/>
</a>
  </form><a href=menu.html>Return to menu</a>
  </body></html>";
}else{
$display .= "<br/><br/>
<textarea name='comments' id='comments' style='font-family:sans-serif;font-size:1.0em;width: 600px; height: 100px;' required></textarea>
  <br/><input type=submit><a href='http://localhost/selectReviewCategory.php'>
   <input type='button' value='Start a new Thread'/>
</a> your points: $points
<input type='hidden' name='points' id = 'points' value=$points />
<input type='hidden' name='level' id = 'points' value=$level />
  </form><a href=menu.html>Return to menu</a>
  </body></html>";
  }

echo $display;

?>