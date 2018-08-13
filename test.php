<?php
exec("MODE COM3: BAUD=1200 PARITY=N DATA=8 STOP=1", $output, $retval);
           $fp=fopen("COM3","r+");
           fputs($fp, "AT+CMGF=1\r");
           fputs($fp, "AT+CMGS=\"650931636\"\r");
           fputs($fp, "message here");
           fputs($fp, chr(26));
           fclose($fp);
?>