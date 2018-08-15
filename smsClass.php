<html>
<title> SMS Sender 1.0 </title>

<?php
//Requires that PHP has permission to access "COM" system device, and system "mode" command


//Send SMS via serial SMS modem
class gsm_send_sms{

    public $port = 'COM7';
    public $baud = 9600;

    public $debug = false;

    private $fp;
    private $buffer;

    //Setup COM port
    public function init() {

        $this->debugmsg("Setting up port: \"{$this->port} @ \"{$this->baud}\" baud");

        exec("MODE {$this->port}: BAUD={$this->baud} PARITY=N DATA=8 STOP=1", $output, $retval);

        if ($retval != 0) {
            throw new Exception('Unable to setup COM port, check it is correct');
        }

        $this->debugmsg(implode("\n", $output));

        $this->debugmsg("Opening port");

        //Open COM port
        $this->fp = fopen($this->port . ':', 'r+');

        //Check port opened
        if (!$this->fp) {
            throw new Exception("Unable to open port \"{$this->port}\"");
        }

        $this->debugmsg("Port opened");
        $this->debugmsg("Checking for response from modem");

        //Check modem connected
        fputs($this->fp, "AT\r");

        //Wait for ok
        $status = $this->wait_reply("OK\r\n", 5);

        if (!$status) {
            throw new Exception('Did not receive responce from modem');
        }

        $this->debugmsg('Modem connected');

        //Set modem to SMS text mode
        $this->debugmsg('Setting text mode');
        fputs($this->fp, "AT+CMGF=1\r");

        $status = $this->wait_reply("OK\r\n", 5);

        if (!$status) {
            throw new Exception('Unable to set text mode');
        }

        $this->debugmsg('Text mode set');

    }

    //Wait for reply from modem
    private function wait_reply($expected_result, $timeout,$test="") {

        $this->debugmsg("Waiting {$timeout} seconds for expected result");

        //Clear buffer
        $this->buffer = '';

        //Set timeout
        $timeoutat = time() + $timeout;

        //Loop until timeout reached (or expected result found)
        do {

            $this->debugmsg('Now: ' . time() . ", Timeout at: {$timeoutat}");

            $buffer = fread($this->fp, 1024);
            $this->buffer .= $buffer;

            usleep(200000);//0.2 sec

            $this->debugmsg("Received: {$buffer}");

            //Check if received expected responce
            if (preg_match('/'.preg_quote($expected_result, '/').'$/', $this->buffer)) {
                $this->debugmsg('Found match');
                return true;
                //break;
            } else if (preg_match('/\+CMS ERROR\:\ \d{1,3}\r\n$/', $this->buffer)) {
                return false;
            }

        } while ($timeoutat > time());

        $this->debugmsg('Timed out');

        return false;

    }

    //Print debug messages
    private function debugmsg($message) {

        if ($this->debug == true) {
            $message = preg_replace("%[^\040-\176\n\t]%", '', $message);
            echo $message . "\n";
        }

    }

    //Close port
    public function close() {

        $this->debugmsg('Closing port');

        fclose($this->fp);

    }

    //Send message
    public function send($tel, $message) {

        //Filter tel
        $tel = preg_replace("%[^0-9\+]%", '', $tel);

        //Filter message text
        $message = preg_replace("%[^\040-\176\r\n\t]%", '', $message);

        $this->debugmsg("Sending message \"{$message}\" to \"{$tel}\"");

        //Start sending of message
        fputs($this->fp, "AT+CMGS=\"{$tel}\"\r");

        //Wait for confirmation
        $status = $this->wait_reply("\r\n> ", 5);

        if (!$status) {
            //throw new Exception('Did not receive confirmation from modem');
            $this->debugmsg('Did not receive confirmation from modem');
            return false;
        }

        //Send message text
        fputs($this->fp, $message);

        //Send message finished indicator
        fputs($this->fp, chr(26));

        //Wait for confirmation
        $status = $this->wait_reply("OK\r\n", 180);

        if (!$status) {
            //throw new Exception('Did not receive confirmation of message sent');
            $this->debugmsg('Did not receive confirmation of message sent');
            return false;
        }

        $this->debugmsg("Message Send Successfully...");

        return $status;
    }

    public function read(){
        
        $this->debugmsg("Reading inbox");

        fputs($this->fp, 'AT+CMGL="REC UNREAD"'. "\r"); //read any unread messages in sim
        //Wait for confirmation
        $status = $this->wait_reply("OK\r\n", 5); 

        $arr = explode("+CMGL:", $this->buffer);   //converting string to array separated separated by +CMGL in the form
                                                    //arr = ['index,status,number,date,message']

        fputs($this->fp, 'AT+CMGD=1,4' . "\r"); //delete all messages after reading
         //Wait for confirmation
        $status = $this->wait_reply("OK\r\n", 5); 
        
        $inbox=null;   //variable used to store message
        for ($i = 1; $i < count($arr); $i++) {   //because the first is OK
            $arrItem = explode("\n", $arr[$i], 3);  // converts arr[i] string to array in the form 
                                                    //arritem = ['index,status,number,date','message', 'OK']
            // Header
            $headArr = explode(",", $arrItem[0]);   //converts string arrItem[0] into and array separated by ','
            $fromTlfn = str_replace('"', null, $headArr[2]);   //returns the number which sent the message eg +237650931636

            $id = $headArr[0]; //the index of the received message

            $date = str_replace('"', null, $headArr[4])." ".str_replace('"', null, $headArr[5]); //returns the date eg 
                                                                                                // 18/08/15 23:22:17+04
            // txt
            $txt = str_replace("'", null, $arrItem[1]);  //returns the message eg hello without quotes

           // $txt = str_replace("ERROR", null, $txt);   //do not show errors

            $inbox[] = array('id' => $id, 'sender' => $fromTlfn, 'text' => $txt, 'date' => $date); //put sms in array

        }
        
        return $inbox;  // returned a multidimensional array

    }

 }

?>
</body>
</html>
