//the end goal of this code is to send sms on particular intervals
//and when there is a critical fault on the system
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>
#include <EEPROM.h>
#include <SoftwareSerial.h>

SoftwareSerial Serial1(7, 8);

char  tempChars[180];        // temporary array for use when parsing
char phoneNumber[22] = {0};
char empty[3] = {0};
char date[25] = {0};
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
float tempThresholdMax = 40.0;
float tempThresholdMin = 20.0;
int smokeThresholdMax = 400;

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
//for serial monitor
 delay(100);
  Serial1.print("AT+CMGF=1\r");
    delay(100);

  //set the module to send sms to serial output upon receipt
  Serial1.print("AT+CNMI=2,2,0,0,0\r");
  delay(100);
    
   // Serial1.flush();
    Serial.println("ok");
     while(Serial.available()>0)
      Serial1.read();
  
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

 // getTempAndGasAvg();

  fuelLevel = analogRead(fuelLevelSensor); //take a sample of the fuel level
  Serial.print(fuelLevel); //print the fuel level
  checkFuelLevel(fuelLevel); //function to determine when to send sms

  analogReadTemp = analogRead(tempSensor);    //Tell the Arduino to read the voltage on pin A0
  gasValue = analogRead(gasSensor);   // Tell the Arduino to read the voltage on pin A1
  temperature = (5.0 * analogReadTemp)/1023.0; // Convert the read value into a voltage
  temperature /= 0.01;

  delay(500);
  //-------------------
  if(Serial1.available() > 0){
        String getline = Serial1.readString();
       Serial.println("waiting");
        int b = getline.indexOf("+CMT");
        int c = getline.indexOf("!");
            if(b >= 0 && c >= 0){
                int j = 0;
                for(int i =b+6 ; i < c;i++){
                     tempChars[j++] = getline[i];
                 }
          
                 tempChars[j] = '\0';  //terminate the line
                 getline = "";        //empty the string
                  
                   parseData();
                  showParsedData();
                  dataProcessing();
          
                  delay(10); //tempChars.replace(tempChars,"");
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
//  
//  String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0") + ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
//  Serial.println("Sending SMS");
//  sendsms(dataToSend);
//  Serial.println("SMS sent");
  if (checkSmokeState()){
    digitalWrite(buzzer, HIGH);
  }
  else{
    digitalWrite(buzzer, LOW);
  }

  int temperatureState = checkTemperatureState();
  if (temperatureState){
    // if above threshold
    if (temperatureState == 2) {
      digitalWrite(fan, HIGH);
      Serial.println("Temperature above Threshold");
    }

    else {
      digitalWrite(fan, LOW);
    }
    // if below threshold
    if (temperatureState == 1) {
      
    }
    else {
      
    }
  }

  else {
    digitalWrite(fan, LOW);
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
  if(temperature > tempThresholdMax){
    return 2;
  }
  if(temperature < tempThresholdMax){
    return 1;
  }
  return 0;
}

/*
 * this function will determine if threshold
 * for smoke 
 * 1 means smoke detected and 0 means no smoke
 */
int checkSmokeState(){
  if(gasValue > smokeThresholdMax){
    return 1;
  }
  return 0;
}

int  checkFuelLevel(int s){
  if(s >= 1000) {
   Serial.println("NO FUEL LEFT");
  return 0;
  }
  if(s < 1000 && s >= 600) { 
   Serial.println("fuel level is too low");
  return 0;
  }
  if(s < 600 && s >= 500) {
   Serial.println("fuel level is moderate"); 
  return 1;
  }
  if(s < 500) {
   Serial.println("there is enough fuel in tank");
   return 1;
  }
 }

void getTempAndGasAvg(){
  float temp = 0.0;
  int gas = 0;
  for (int i = 0; i < 10; i++) {
     temp += getTemperature();
     gas += getGasLevel();
     delay(500);
  }
  temperature = temp/10;
  gasValue = gas/10;
 }

float getTemperature(){
  analogReadTemp = analogRead(tempSensor);    //Tell the Arduino to read the voltage on pin A0
  float tempe = (5.0 * analogReadTemp)/1023.0; // Convert the read value into a voltage
  tempe /= 0.01;
  return tempe;
}

int getGasLevel(){
  int gas = analogRead(gasSensor);   // Tell the Arduino to read the voltage on pin A1
  return gas;
}

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
    strcpy(tempChars, strtokIndx);  

}

//============

void showParsedData() {
    Serial.print("number ");
    Serial.println(phoneNumber);
    Serial.print("date is ... ");
    Serial.println(date);
     Serial.print("content ");
    Serial.println(tempChars);
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

    strtokIndx = strtok(tempChars,",");      // get the first part 0 for update and  1 for init
    //Serial.println(strtokIndx);
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
    if(strcmp(tempChars, "GET") ==0){
      return 1;   //it is a command to get data
      }
     else
      return 0;     //it is a command to set threshold
  }
void sendsms(String txt){
   Serial1.print("AT+CMGS = "); //command to send sms to the specified number
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
  Serial.println("Processing data");
  if(validateNumber()){   //check if the number is valid before proceeding
    Serial.println("Number is valid");
      if(checkRequest()){  //check the types of request issued
        Serial.println("Request is get data");
        //send and sms contain all data in the appropriate format
          String dataToSend = "temp="+String(temperature)+",fuel="+String(fuelLevel)+",motion="+(motion?"1":"0");
          dataToSend +=  ",battery="+String(batteryLevel)+",voltages="+String(voltage)+",generator="+String(generator)+",gas="+String(gasValue);
          sendsms(dataToSend);
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
    Serial.println("No access for number,TERMINATED AND ALARM SENT!! YOU HAVE BEEN WARNED");
    //send an sms to the person and to the head office
    sendsms("YOU HAVE BEEN WARNED");
    }
  } //close data processing
