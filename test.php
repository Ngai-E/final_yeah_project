<!DOCTYPE html>
<html>

<body>
	
	<?php
$s = date("l",strtotime("today"));   //takes the date of today and get the day in a string.
$d=strtotime("last $s");  //converts the human readable string to date format e.g if today if friday, it will convert
$s1 = date("Y-m-d H:i:s", $d); //'last friday' to date in the format specified 'Y-m-d H:i:s'
echo  $s1. "<br>";

$d=strtotime("last week");
$d1 = date("Y-m-d 00:00:00",$d);//converting lastweek to datetime format so that it can be used for comparing
echo $d1 . "<br>";

$d=strtotime("+3 Months");
echo date("Y-m-d h:i:sa", $d) . "<br>";

$startdate = strtotime("Saturday");
$enddate = strtotime("+6 weeks",$startdate);

while ($startdate < $enddate) {
  echo date("l, d M Y", $startdate),"<br>";
  $startdate = strtotime("+1 week", $startdate);
}


$a=array();
array_push($a,"blue","yellow");
print_r($a[0]);

//SELECT * FROM `temp_logs` WHERE time > '2018-10-03 15:00:00' ;
?>

</body>

</html

