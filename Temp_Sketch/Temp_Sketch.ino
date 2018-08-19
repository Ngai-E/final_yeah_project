//the end goal of this code is to send sms on particular intervals
//and when there is a critical fault on the system
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>
#include <Keypad.h>
#include <EEPROM.h>

const byte ROWS = 4;
const byte COLS = 3;

const int buzzer = 13;
const int gasSensor = A1;
const int tempSensor = A0;
const int fuelLevelSensor = A3;
const int fan = 11;
const int pirSensor = 12;
int gasValue;

char hexaKeys[ROWS][COLS] = {
  {'1', '2', '3'},
  {'4', '5', '6'},
  {'7', '8', '9'},
  {'*', '0', '#'},
};

byte rowPins[ROWS] = {9, 8, 7, 6};
byte colPins[COLS] = {5, 4, 3, };

int analogReadTemp;  // Variable to read the value from Arduino A0
int fuelLevel;
float temperature; // variable that receives the converted voltage
float tempThresholdMax = 40.0;
float tempThresholdMin = 20.0;
int smokeThresholdMax = 400;

Keypad keypad = Keypad(makeKeymap(hexaKeys), rowPins, colPins, ROWS, COLS);

LiquidCrystal_I2C lcd(0x3F, 16, 2); //   

struct Access{
  char pin[17];
  char num1[10];
  char num2[10];
  char num3[10];
  char num4[10];
};

Access access;

void setup() {
  Serial.begin(9600);    //setting the baud rate of serial monitor
  pinMode(buzzer, OUTPUT);   //setting the buzzser pin to output
  lcd.begin();               //setup the LCD display
  lcd.setCursor(0, 0);    //setting the innitial index of the lcd display to row 1, column 1
  pinMode(pirSensor,INPUT);  //setting the pirSensor pin to input
  pinMode(fan, OUTPUT);
  digitalWrite(pirSensor,LOW);   //innitialising the pirSensor pin to low
  
}

void loop() {
  if(digitalRead(pirSensor)==HIGH)  {       //tell the arduino to check the value of the motion sensor 
      Serial.println("Somebody is around");
    }
    else  {
      Serial.println("Nobody.");
    }
    delay(10);

  getTempAndGasAvg();

  fuelLevel = analogRead(fuelLevelSensor); //take a sample of the fuel level
  Serial.print(fuelLevel); //print the fuel level
  checkFuelLevel(fuelLevel); //function to determine when to send sms
  
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

