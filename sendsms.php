<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

require_once('class.php');

//Example here my gsm modem in assigned in com3 in my device manager

if(1){
	// $mobile_number=$_POST['mobile_number'];
	// $messages=$_POST['messages'];

	$gsm_send_sms = new gsm_send_sms();
	$gsm_send_sms->debug = false;
	$gsm_send_sms->port = 'COM7';
	$gsm_send_sms->baud = 9600;
	$gsm_send_sms->init();

	// $status = $gsm_send_sms->send($mobile_number,$messages);

	// if ($status) {
	// 	echo $status;
	// } else {
	// 	echo $status;
	// }

	$read = $gsm_send_sms->read();
	//echo $read;

	$gsm_send_sms->close();
}





?>
<html>
<title> SMS Sender 1.0 </title>
<body bgcolor="lightgreen">
<font face="arial" size="3">
<h3> SMS Sender 1.0 </h3>
<form action="" method="post">
Enter Mobile Number <br><input type="text" name="mobile_number" size="20">
<br><br>
Message<br><textarea cols = 25 rows = 5 name="messages"></textarea><br />
<br><input type="submit" name="action" value="Send SMS" title="Click here to send SMS.">
</form>
</font>
</body>
</html>
