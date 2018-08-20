
// Example 5 - Receive with start- and end-markers combined with parsing

#include <SoftwareSerial.h>

//configure software serial port

SoftwareSerial Serial1(7,8);

const byte numChars = 180;
char receivedChars[numChars];
char tempChars[numChars];        // temporary array for use when parsing

// variables to hold the parsed data
char phoneNumber[22] = {0};
char empty[3] = {0};
char date[25] = {0};
char controlMessage[numChars] = {0};
int threshold[31] = {6};
//char authorised[] = {'"','+','2','3','7','6','5','0','9','3','1','6','3','6','"'};
//char authorised2[] = {'"','+','2','3','7','6','5','0','9','3','1','6','3','6', '"'};

boolean newData = false;

//============

void setup() {
    //arduino communicates with Serial1 GSM shield at a baud rate of 9600
 //make sure that corresponds to the baud rate of your module
 Serial1.begin(9600);

 //for serial monitor
 Serial.begin(9600);

 //give time to your GSM shield to log on to network
  delay(100);

  //AT command to set Serial1 to SMS mode
    Serial1.print("AT+CMGF=1\r");
    delay(100);

  //set the module to send sms to serial output upon receipt
  Serial1.print("AT+CNMI=2,2,0,0,0\r");
  delay(100);
    Serial.println("This demo expects 3 pieces of data - text, an integer and a floating point value");
    Serial.println("Enter data in this style <HelloWorld, 12, 24.7>  ");
    Serial.println();
}

//============

void loop() {
    recvWithStartEndMarkers();
    if (newData == true) {
        strcpy(tempChars, receivedChars);
            // this temporary copy is necessary to protect the original data
            //   because strtok() used in parseData() replaces the commas with \0
        parseData();
        showParsedData();
        dataProcessing();
        newData = false;
        delay(100); //give the microcontroller time to process next line
    }
}

//============

void recvWithStartEndMarkers() {
    static boolean recvInProgress = false;
    static byte ndx = 0;
    char startMarker = ' ';
    char endMarker = '!';
    char rc;

    while (Serial1.available() > 0 && newData == false) {
        rc = Serial1.read();

        if (recvInProgress == true) {
            if (rc != endMarker) {
                receivedChars[ndx] = rc;
                ndx++;
                if (ndx >= numChars) {
                    ndx = numChars - 1;
                }
            }
            else {
                receivedChars[ndx] = '\0'; // terminate the string
                recvInProgress = false;
                ndx = 0;
                newData = true;
            }
        }

        else if (rc == startMarker) {
            recvInProgress = true;
        }
    }
}

//============

void parseData() {      // split the data into its parts

    char * strtokIndx; // this is used by strtok() as an index

    strtokIndx = strtok(tempChars,",");      // get the first part - the string
    strcpy(phoneNumber, strtokIndx); // copy it to messageFromPC
 
    strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
    strcpy(empty, strtokIndx);     //

    strtokIndx = strtok(NULL, ",");
    strcpy(date, strtokIndx);     // gets the date 

    strtokIndx = strtok(NULL, "\n");
    strcat(date, ",");
     strcat(date, strtokIndx);

    strtokIndx = strtok(NULL, "\n");
    strcpy(controlMessage, strtokIndx);  

}

//============

void showParsedData() {
    Serial.print("number ");
    Serial.println(phoneNumber);
    Serial.print("date is ... ");
    Serial.println(date);
     Serial.print("content ");
    Serial.println(controlMessage);
}
int validateNumber(){    //this function will check if the sender is authorised to communicate with the system
  if(strcmp(phoneNumber, "\"+237650931636\"")==0||strcmp(phoneNumber,"\"+237650931636\"")==0)
    return 1;
   else return 0;
 // return 1:strcmp(phoneNumber, authorised)==0||strcmp(phoneNumber,authorised2)==0?0;    //return 1 if valid and 0 otherwise
  }
  
 void parseMessage(){    //this function splits the messages to remove various info and will only be called if message is a request to store thresholds
    int i;
    char * strtokIndx; // this is used by strtok() as an index

    strtokIndx = strtok(controlMessage,",");      // get the first part 0 for update and  1 for init
    Serial.println(strtokIndx);
    threshold[0] = atoi(strtokIndx); // copy it to array of threshold
    for(i = 1 ; i < 31 ; i++){
      strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
      threshold[i] = atoi(strtokIndx);     // convert this part to an integer
      }
  }//close parse Message

int writeToEEProm(){  //this function writes to eeprom incase message is a request to store threshold
    if(threshold[0] == 1){return 1;}   //initiallise the eeprom starting from index 1 of the array and return 1 when done
    if(threshold[0] == 0){return 2;} //update the eeprom starting from index 1 of the array and return two when done
    else{
      return 0;  //could not understand request
     }
  }

 int checkRequest(){  //request to the BTS can be of two types 1-> setting thresholds 2-> getting live data
    if(strcmp(controlMessage, "GET") ==0){
      return 1;   //it is a command to get data
      }
     else
      return 0;     //it is a command to set threshold
  }

void dataProcessing(){
  Serial.println("Processing data");
  if(validateNumber()){   //check if the number is valid before proceeding
    Serial.println("Number is valid");
      if(checkRequest()){  //check the types of request issued
        Serial.println("Request is get data");
        //send and sms contain all data in the appropriate format
        
        }//close request type get data
        
      else{
        Serial.println("request is threshold");
        parseMessage();  //sttip the threshold and store in array
        delay(100);

        int state = writeToEEProm();
        int i;

        if(state== 1){
          Serial.println("Threshold Initialisation Successful");
          for(i = 0; i < 31 ; i++)
          Serial.println(threshold[i]);
          }
         else if(state == 2){
          Serial.println("Threshold update Successfull");
          for(i = 0; i < 31 ; i++)
          Serial.println(threshold[i]);
         // Serial.println(threshold);
          }
         else{
          Serial.println("Failed to access EEprom Unknown command");
          }
        
        }//closing request is threshold
        
    }// close validate number
    
   else{
    Serial.println("Number not authorise to control site, ACTION TERMINATED AND ALARM SENT!! YOU HAVE BEEN WARNED");
    //send an sms to the person and to the head office
    }
  } //close data processing
