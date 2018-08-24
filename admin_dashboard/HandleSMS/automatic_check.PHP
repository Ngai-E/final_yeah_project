<?php
require('config.php');//file for database connections
require('smsClass.php'); //class file for sms commands

$phoneNumber = "+237650931636" //set the phone number of the site expecting

//creating an sms object
	$gsm_sms = new gsm_sms();
	$gsm_sms->debug = false;
	$gsm_sms->port = 'COM7';
	$gsm_sms->baud = 9600;
	$gsm_sms->init();

while(1) {
	# code...
	$read = $gsm_sms->read();      //protocol to check send and receive sms
	if(count($read)>0){   //check if there is an sms
		for($i = 0; $i < count($read); $i++){
			if($read[$i]['sender'] == $phoneNumber){    //check if the number is authorized to send 
				$timeReceived = $read[$i]['date'];      //time the message was received

				$arr = explode(",", $read[$i]['text']);  //text string to array in form [param=value,param=value]
				//$datesent = explode("=", $arr[0]);  //converting the individual strings to array in form [param,value]
				$temperature = explode("=", $arr[1]);
				$fuel_level = explode("=", $arr[2]);
				$motion = explode("=", $arr[3]);
				$battery = explode("=", $arr[4]);
				$voltages = explode("=", $arr[5]);
				$generator = explode("=", $arr[6]);
				$smoke = explode("=", $arr[7]);
				$auth = explode("=", $arr[8]);

				/***************************************
				inserting into database table logs begin
				****************************************/
				$sql = "INSERT INTO `logs`(`parameter_name`, `value`, `time`)";
				 $sql .=	"VALUES ('temperature','$temperature[1]','$timeReceived')";//statement to be executed

			      $result = mysqli_query($conn, $sql); //execute query
			      	
			      if (!$result) {
			          //if data was not inserted succesfully
			      	die('Could not enter data: ' . mysqli_error($conn));
			           
			      }

			      } else {
			          echo "New record created successfully";
			      }


				/***************************************
				inserting into database table logs ends here
				****************************************/


			}else {  //if not from pemitted number
				; //ignore the message we dont need it
			}

			mysqli_close($conn);
			
		}


		//delay after check
		usleep(1000000);  //sleep for 1s 

		if ($send != 1) {
		# code... to send
		if($send == 1)
			$status = $gsm_sms->send($mobile_number,"GET"); //get data
		if($send == 2)
			$status = $gsm_sms->send($mobile_number,"threshold"); //get the threshold

		if ($status) {
			echo "data sent successfully";
		} else {
			echo "status not sent: try again";
		}

	}                //check is there is an incoming request to send message


	//delay after check
	usleep(1000000);  //sleep for 1s before checking again
		
	}
	
?>