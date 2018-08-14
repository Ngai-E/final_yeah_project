$this->debugmsg("Reading inbox");
		//Start sending of message
		fputs($this->fp, "AT+CMGL=\"ALL\"\n\r");
		//Wait for confirmation
		$status = $this->wait_reply("OK\r\n", 5);
		//print_r($this->buffer);die;
		$arr = explode("+CMGL:", $this->buffer);
		$inbox = null;	
		for ($i = 1; $i < count($arr); $i++) {
			$arrItem = explode("\n", $arr[$i], 2);
			// Header
			$headArr = explode(",", $arrItem[0]);
			$fromTlfn = str_replace('"', null, $headArr[2]);
			$id = $headArr[0];
			$date = str_replace('"', null, $headArr[4])." ".str_replace('"', null, $headArr[5]);
			//$hour = $headArr[5];
			// txt
			$txt = str_replace("'", null, $arrItem[1]);
			$txt = str_replace("ERROR", null, $txt);
			$inbox[] = array('id' => $id, 'sender' => $fromTlfn, 'text' => $txt, 'date' => $date);
		}
		return $inbox;
