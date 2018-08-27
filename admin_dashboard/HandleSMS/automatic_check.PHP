
<?php
session_start();
?>
<?php
require('../config.php');//file for database connections
require('smsClass.php'); //class file for sms commands

$phoneNumber = "+237670200603" ;//set the phone number of the site expecting
//creating an sms object
	$gsm_send_sms = new gsm_sms();
	$gsm_send_sms->debug = false;
	$gsm_send_sms->port = 'COM7';
	$gsm_send_sms->baud = 9600;
	$gsm_send_sms->init();

	# code...
	$read = $gsm_send_sms->read();      //protocol to check send and receive sms
	if(count($read)>0){   //check if there is an sms
		for($i = 0; $i < count($read); $i++){
			if($read[$i]['sender'] == $phoneNumber){    //check if the number is authorized to send 
				$timeReceived = $read[$i]['date'];      //time the message was received
				$timeReceived = str_replace('18', '2018', $timeReceived);
				$timeReceived = str_replace('/', '-', $timeReceived);
				$timeReceived = str_replace('+04', null, $timeReceived);
				echo "$timeReceived";
				$arr = explode(",", $read[$i]['text']);  //text string to array in form [param=value,param=value]
				//$datesent = explode("=", $arr[0]);  //converting the individual strings to array in form [param,value]
				$temperature = explode("=", $arr[0]);
				$fuel_level = explode("=", $arr[1]);
				$motion = explode("=", $arr[2]);
				$battery = explode("=", $arr[3]);
				$voltages = explode("=", $arr[4]);
				$generator = explode("=", $arr[5]);
				$smoke = explode("=", $arr[6]);
				$auth = 1;

				// /***************************************
				// inserting into database table logs begin
				// ****************************************/

				//command to insert parsed data into database
				$sql = "INSERT INTO logs1(`time`, temperature,fuel_level,motion,battery,generator,smoke,voltages,auth) values('$timeReceived','$temperature[1]','$fuel_level[1]', '$motion[1]','$battery[1]','$generator[1]','$smoke[1]','$voltages[1]','$auth')";


			      $result = mysqli_query($conn, $sql); //execute query
			      	
				      if (!$result) {
				          //if data was not inserted succesfully
				      	die('Could not enter data: ' . mysqli_error($conn));

				           
				      	}

				      	else echo "data saved succesfully";
				   

				      	mysqli_close($conn);

			}

			else 
				echo "not authorised to send"; 
		}


				/***************************************
				inserting into database table logs ends here
				****************************************/


	}
			
		
		else {  //if not from pemitted number
				echo "no new messages"; //ignore the message we dont need it
			}


		//delay after check
		usleep(1000000);  //sleep for 1s 

		if ($_SESSION["send"] == "0") {
		# code... to send
		if($_SESSION["send"] == "1")
			$status = $gsm_send_sms->send($mobile_number,"GET"); //get data
		if($_SESSION["send"] == "2")
			$status = $gsm_send_sms->send($mobile_number,"threshold"); //send the threshold

		if ($status) {
			echo "message sent successfully";
			$_SESSION['send'] = "0";
		} else {
			echo "status not sent: try again";
			$_SESSION['send'] = "0";
		}

	}                //check if there is an incoming request to send message

	$gsm_send_sms->close();
	//delay after check
	usleep(1000000);  //sleep for 1s before checking again
		
	
	
?>