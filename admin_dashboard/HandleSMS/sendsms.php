<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

require_once('smsClass.php');
//header('refresh:10; url=sendsms.php');

//Example here my gsm modem in assigned in com3 in my device manager


	// $mobile_number=$_POST['mobile_number'];
	// $messages=$_POST['messages'];

	$gsm_send_sms = new gsm_sms();
	$gsm_send_sms->debug = false;
	$gsm_send_sms->port = 'COM5';
	$gsm_send_sms->baud = 9600;
	$gsm_send_sms->init();

	$status = $gsm_send_sms->send($mobile_number,$messages);

	if ($status) {
		echo $status;
	} else {
		echo $status;
	}

	$read = $gsm_send_sms->read();
	if(count($read)> 0){
		echo "there is a message\n" ;
		$time = $read[0]['date'];
		$time = str_replace('+04', null, $time);
		$time = str_replace('/', '-', $time);
		echo $read[0]['text'] . "\n";
		echo $time . "\n";

		$read = null;
	}
	else{
		echo "there is no new message";
	}

	$gsm_send_sms->close();



?>
