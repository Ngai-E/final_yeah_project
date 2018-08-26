//the end goal of this code is to send sms on particular intervals
//and when there is a critical fault on the system
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>
#include <EEPROM.h>
#include <SoftwareSerial.h>

SoftwareSerial Serial1(7, 8);

char  tempChars[115];        // temporary array for use when parsing
char phoneNumber[22] = {0};
int threshold[31] = {6};

const byte ROWS = 4;
const byte COLS = 3;

const int buzzer = 13;
const int gasSensor = A1;
const int tempSensor = A0;
const int fuelLevelSensor = A3;
const int fan = 11;
const int pirSensor = 12;
const int bulb = 6;
int gasValue;

int analogReadTemp;  // Variable to read the value from Arduino A0
int fuelLevel;
int batteryLevel = 50;
short int voltage = 1;
short int generator = 0; 
boolean motion = false;
float temperature; // variable that receives the converted voltage


int alert[3];

LiquidCrystal_I2C lcd(0x3F, 16, 2); //   


void setup() {
  Serial1.begin(9600);
  Serial.begin(9600);    //setting the baud rate of serial monitor
  pinMode(buzzer, OUTPUT);   //setting the buzzser pin to output
  lcd.begin();               //setup the LCD display
  lcd.setCursor(0, 0);    //setting the innitial index of the lcd display to row 1, column 1
  pinMode(pirSensor,INPUT);  //setting the pirSensor pin to input
  pinMode(fan, OUTPUT);
  pinMode(bulb, OUTPUT);
  digitalWrite(pirSensor,LOW);   //innitialising the pirSensor pin to low
  
  digitalWrite(9,HIGH);     //search for network
   delay(1000);
  digitalWrite(9, LOW);
    delay(5000);
    
//for serial monitor
 delay(100);
  Serial1.print("AT+CMGF=1\r");
    delay(100);

  //set the module to send sms to serial output upon receipt
  Serial1.print("AT+CNMI=2,2,0,0,0\r");
  delay(100);
    
   // Serial1.flush();
    //Serial.println("ok");
     while(Serial1.available()>0)   //clear the serial buffer
     Serial.println(Serial1.read());

  
}

void loop() {
  if(digitalRead(pirSensor)==HIGH)  {       //tell the arduino to check the value of the motion sensor 
      Serial.println("Somebody is around");
      motion = true;
    }
    else  {
      motion = false;
      Serial.println("Nobody.");
    }
    delay(10);

  fuelLevel = analogRead(fuelLevelSensor); //take a sample of the fuel level
  Serial.println(fuelLevel);
  fuelLevel = 1030 - fuelLevel;    //convert the high to low and low to high
  fuelLevel *= 0.219;    //convert to percentage
  Serial.println(fuelLevel); //print the fuel level
 // checkFuelLevel(fuelLevel); //function to determine when to send sms
delay(2000);
  analogReadTemp = analogRead(tempSensor);    //Tell the Arduino to read the voltage on pin A0
  gasValue = analogRead(gasSensor);   // Tell the Arduino to read the voltage on pin A1
  temperature = (5.0 * analogReadTemp)/1023.0; // Convert the read value into a voltage
  temperature /= 0.01;

  delay(2000);
  //-------------------
  if(Serial1.available() > 0){
        String getline = Serial1.readString();
       // Serial.println(getline);
      // Serial.println(getline);
        int b = getline.indexOf("+CMT");
        int c = getline.indexOf("!");
            if(b >= 0 && c >= 0){
             // Serial.println(getline);
                int j = 0;
                for(int i =b+6 ; i < c;i++){
                     tempChars[j++] = getline[i];
                 }
            
                 tempChars[j] = '\0';  //terminate the line
               //Serial.println(tempChars);
                 getline = "";        //empty the string
                  
                   parseData();
                  showParsedData();
                  dataProcessing();
                  //int i;
//                  for(i = 0 ; i < 30; i++);
//                    Serial.print(EEPROM.read(i));
//                  Serial.println();
          
                  delay(100); 
              }

       }
  //-------------------
  lcd.clear();
  Serial.print("Temperature ");
  Serial.println(temperature);
  Serial.println("Gas Sensor");
  Serial.println(gasValue);
  lcd.print("Temp: ");
  lcd.print(temperature);
  lcd.print(char(223));
  lcd.print("C");
  lcd.setCursor(0, 1);
  lcd.print("Gas: ");
  lcd.print(gasValue);

//check the smoke
  if (checkSmokeState() == 1){
    digitalWrite(buzzer, HIGH);
    digitalWrite(fan, HIGH);
  }
  else if (checkSmokeState() == 2){           //turn on only fan
      digitalWrite(fan, HIGH);
      Serial.println("Temperature above Threshold");
  }
  else{
    digitalWrite(buzzer, LOW);
    digitalWrite(fan, LOW);
  }

//check the temperature
  int temperatureState = checkTemperatureState();
  if (temperatureState == 1){        //turn on buxzer and fan 
      digitalWrite(fan, HIGH);
      digitalWrite(buzzer, HIGH);
      //Serial.println("Temperature above Threshold");
  }
 else if (temperatureState == 2){           //turn on only fan
      digitalWrite(fan, HIGH);
     // Serial.println("Temperature above Threshold");
  }
    else {
      digitalWrite(fan, LOW);
      digitalWrite(buzzer, HIGH);
    }
    
 

    delay(8000);

}

/*
 *  This function will detemine if threshold
 *  for temperature is reached  
 *  0 means temperature is ok
 *  1 means temperature is below min
 *  2 means temperature is above max
 */

int checkTemperatureState(){
  if(temperature >= EEPROM.read(0) && temperature <= EEPROM.read(1)){   //checking for normal values
    if(alert[0] != 0)
    Serial.println("temperature normal");
    
      alert[0] = 0;
    return 0;
  }

  else if(temperature > EEPROM.read(1) && temperature <= EEPROM.read(2)){   //checking for warning values
    if(alert[0] != 1){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
         // Serial.println("temperature warning");     
           alert[0] = 1;
      }
    return 2;
  }

  else if(temperature > EEPROM.read(2) && temperature <= EEPROM.read(3)){   //checking for error values
    if(alert[0] != 2){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
          //Serial.println("temperature error");
      alert[0] = 2;
      }
    return 2;
  }

  else if(temperature > EEPROM.read(3) && temperature <= EEPROM.read(4)){   //checking for critical values
    if(alert[0] != 3){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
          //Serial.println("temperature critical");
      alert[0] = 3;
      }
    return 2;
  }

  else if(temperature > EEPROM.read(4) && temperature <= EEPROM.read(5)){   //checking for alert values
    if(alert[0] != 4){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
         sendsms(dataToSend);
         //Serial.println("temperature alert");
      alert[0] = 4;
      }
    return 2;
  }

  else if(temperature >=EEPROM.read(5)){  
    if(alert[0] != 5){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
          //Serial.println("temperature emergency");
      alert[0] = 5;
      }
    return 1;
  }
}

/*
 * this function will determine if threshold
 * for smoke 
 * 1 means smoke detected and 0 means no smoke
 */
int checkSmokeState(){
 if(gasValue >= (EEPROM.read(6)* 10) && gasValue <= (EEPROM.read(7)* 10)){  
    if(alert[1] != 0)
      alert[1] = 0;
    return 0;
  }

  else if(gasValue > (EEPROM.read(7)* 10) && gasValue <= (EEPROM.read(8)* 10)){  
    if(alert[1] != 1){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
      alert[1] = 1;
      }
    return 2;
  }

  else if(gasValue > (EEPROM.read(8)* 10) && gasValue <= (EEPROM.read(9)* 10)){  
    if(alert[1] != 2){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
      alert[1] = 2;
      }
    return 1;
  }

  else if(gasValue > (EEPROM.read(9)* 10) && gasValue <= (EEPROM.read(10)* 10)){  
    if(alert[1] != 3){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
      alert[1] = 3;
      }
    return 1;
  }

  else if(gasValue > (EEPROM.read(10)* 10) && gasValue <= (EEPROM.read(11)* 10)){  
    if(alert[1] != 4){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
      alert[1] = 4;
      }
    return 1;
  }

  else if(gasValue >= (EEPROM.read(11)* 10)){  
    if(alert[1] != 5){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
      alert[1] = 5;
      }
    return 1;
  }
}

void checkFuelLevel(int s){
//  s = 1008 - s;    //convert the high to low and low to high
//  s *= 0.1413;    //convert to percentage

Serial.print(s);
Serial.println("%");
   if(s <= EEPROM.read(12) && s >= EEPROM.read(13)){   //checking for normal values
    if(alert[2] != 0)
      alert[2] = 0;
  }

  else if(s < EEPROM.read(13) && s >= EEPROM.read(14)){     //checking for warning values 
    if(alert[2] != 1){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
      alert[2] = 1;
      }
  }

  else if(s <EEPROM.read(14) && s >=EEPROM.read(15)){     //checking for error values
    if(alert[2] != 2){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
      alert[2] = 2;
      }
  }

  else if(s < EEPROM.read(15) && s >=EEPROM.read(16)){    //checking for critical values
    if(alert[2] != 3){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
      alert[2] = 3;
      }
  }

  else if(s < EEPROM.read(16) && s >=EEPROM.read(17)){    //checking for alert
    if(alert[2] != 4){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
      alert[2] = 4;
      }

  }

  else if(s < EEPROM.read(17)){              //checkign for emergency values
    if(alert[2] != 5){
      String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
      alert[2] = 5;
      }

  }

  
 }

//int getGasLevel(){
//  int gas = analogRead(gasSensor);   // Tell the Arduino to read the voltage on pin A1
//  return gas;
//}



void parseData() {      // split the data into its parts

    char * strtokIndx; // this is used by strtok() as an index
    char date[25] = {0};
char empty[3] = {0};
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
    strcpy(tempChars, strtokIndx);  

}

//============

void showParsedData() {
    Serial.print("number ");
    Serial.println(phoneNumber);
//    Serial.print("date is ... ");
//    Serial.println(date);
     Serial.print("content ");
    Serial.println(tempChars);
}
int validateNumber(){    //this function will check if the sender is authorised to communicate with the system
  if(strcmp(phoneNumber, "\"+237650931636\"")==0||strcmp(phoneNumber,"\"+237671906987\"")==0)
    return 1;
   else return 0;
 // return 1:strcmp(phoneNumber, authorised)==0||strcmp(phoneNumber,authorised2)==0?0;    //return 1 if valid and 0 otherwise
  }
  
 void parseMessage(){    //this function splits the messages to remove various info and will only be called if message is a request to store thresholds
    int i;
    char * strtokIndx; // this is used by strtok() as an index

    strtokIndx = strtok(tempChars,",");      // get the first part 0 for update and  1 for init
    //Serial.println(strtokIndx);
    threshold[0] = atoi(strtokIndx); // copy it to array of threshold
    for(i = 1 ; i < 31 ; i++){
      strtokIndx = strtok(NULL, ","); // this continues where the previous call left off
      threshold[i] = atoi(strtokIndx);     // convert this part to an integer
      }
  }//close parse Message

int writeToEEProm(){  //this function writes to eeprom incase message is a request to store threshold
 // Serial.print("threshold value are: ");
  //Serial.println(String(threshold));
    if(threshold[0] == 1){ //initiallise the eeprom starting from index 1 of the array and return 1 when done
      for (int i = 0; i < 30; i++) {
        EEPROM.put(i, threshold[i+1]);
      }
      
      return 1;
      }   
    if(threshold[0] == 0){return 2;} //update the eeprom starting from index 1 of the array and return two when done
    else{
      for (int i = 0; i < 30; i++) {
        EEPROM.put(i, threshold[i+1]);
      }
      return 0;  //could not understand request
     }
  }

 int checkRequest(){  //request to the BTS can be of two types 1-> setting thresholds 2-> getting live data
    if(strcmp(tempChars, "GET") ==0){
      return 1;   //it is a command to get data
      }
     else
      return 0;     //it is a command to set threshold
  }
void sendsms(String txt){
   Serial1.println("AT+CMGS = \"+237650931636\""); //command to send sms to the specified number
  // Serial1.println(phoneNumber);
  delay(1000);
  
  
  Serial.print("sending : ");     //sending string variable ,sending , to the specified number
  Serial.println(txt);
  Serial1.println(txt);
  delay(100);
                              //depicting end of sms
Serial1.write(0x0D);  
Serial1.println();
Serial1.println((char)26); 
delay(200);

    while(Serial.available()>0)
      Serial1.read();
  }

 void sendSms(String txt){
   Serial1.println("AT+CMGS = "); //command to send sms to the specified number
  Serial1.println(phoneNumber);
  delay(1000);
  
  
  Serial.print("sending : ");     //sending string variable ,sending , to the specified number
  Serial.println(txt);
  Serial1.println(txt);
  delay(100);
                              //depicting end of sms
Serial1.write(0x0D);  
Serial1.println();
Serial1.println((char)26); 
delay(200);

    while(Serial.available()>0)
      Serial1.read();
  }

void dataProcessing(){
 // Serial.println("Processing data");
  if(validateNumber()){   //check if the number is valid before proceeding
   // Serial.println("Number is valid");
      if(checkRequest()){  //check the types of request issued
        Serial.println("Request is get data");
        //send and sms contain all data in the appropriate format
          String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          dataToSend += "done";
          sendsms(dataToSend);
        }//close request type get data
        
      else{
        //Serial.println("request is threshold");
        parseMessage();  //sttip the threshold and store in array
        delay(100);

        int state = writeToEEProm();
        int i;

        if(state== 1){
          Serial.println("Threshold Init Success");
          for(i = 0; i < 31 ; i++)
          Serial.println(threshold[i]);
          }
         else if(state == 2){
          Serial.println("update Successfull");
          for(i = 0; i < 31 ; i++)
          Serial.println(threshold[i]);
         // Serial.println(threshold);
          }
         else{
          Serial.println(" EEprom write failed");
          }
        
        }//closing request is threshold
        
    }// close validate number
    
   else{
    Serial.println("Unknown number detected");
    //send an sms to the person and to the head office
    sendSms("YOU HAVE BEEN WARNED");
    }
  } //close data processing
