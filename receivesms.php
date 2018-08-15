<?php
exec('mode COM7: baud=19200 data=8 stop=1 parity=n xon=on');
        if ($ser=fopen("COM7:","r+"))
        {
         $ret="";
 
         fputs( $ser, "AT+CMGF=1" . "\r");    
         usleep(500000);
         fputs( $ser, "AT+CNMI" . "\r"); //tranfert sms de sim->modem directement    
         usleep(500000);
 
         fputs( $ser, 'AT+CMGL="REC UNREAD"'. "\r"); //1   
         usleep(500000);
         // fputs( $ser, 'AT+CMGD=1,4'. "\r"); //deletes all messages from storage
         // usleep(500000);     
          
         // fputs( $ser, 'AT+CMGL="ALL"' . "\r");     //parametrage , stockage et nombre max, on stocke ds le telephone
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
//no_sms:      fclose($ser);
            return 0;
?>

<<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

</body>
</html>