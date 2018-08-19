
// Example 5 - Receive with start- and end-markers combined with parsing

#include <SoftwareSerial.h>

//configure software serial port

SoftwareSerial Serial1(7,8);

const byte numChars = 50;
char receivedChars[numChars];
char tempChars[numChars];        // temporary array for use when parsing

// variables to hold the parsed data
char phoneNumber[numChars] = {0};
char empty[numChars] = {0};
char date[numChars] = {0};
char controlMessage[numChars] = {0};

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
        newData = false;
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
    strcpy(empty, strtokIndx);     // convert this part to an integer

    strtokIndx = strtok(NULL, ",");
    strcpy(date, strtokIndx);     // convert this part to a float

    strtokIndx = strtok(NULL, "\r");
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
