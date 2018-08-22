<?php
exec('mode COM7: baud=9600 data=8 stop=1 parity=n xon=on');
		if ($ser=fopen("COM7:","r+")) 
		{
		 $ret="";

		 fputs( $ser, "AT+CMGF=1" . "\r");     // set sms to text mode
		 usleep(500000);

		 // fputs( $ser, "AT+CMNI" . "\r");     // set sms to text mode
		 // usleep(500000);


		 fputs( $ser, 'AT+CMGL="ALL"'. "\r"); //1    
		 usleep(500000);
		 
		  // fputs( $ser, 'AT+CMGD=1,4' . "\r");     //delay after deleting all messages
		  // usleep(500000);

		 while(!feof($ser)){
		  $ret .= fgets( $ser);
					// $ret = "";
						
							$ret .= fgets( $ser); // lecture du sms a utilise
							$ret .= fgets( $ser);
			echo $ret;	 	 
		 }
		fclose($ser); 
		return $ret;
		}
	else
//no_sms:	   fclose($ser);
			return 0;

echo date('y-m-d h:i:s', strtotime('18/08/16 11:57:03'));
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

</body>
</html>