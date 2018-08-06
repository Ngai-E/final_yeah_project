
#include <Wire.h> 
#include <LiquidCrystal_I2C.h>
#include <Keypad.h>
#include <EEPROM.h>

const byte ROWS = 4;
const byte COLS = 3;

const int buzzer = 13;
const int gasSensor = A1;
const int tempSensor = A0;
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
float temperature; // variable that receives the converted voltage
float tempThresholdMax = 30.0;
float tempThresholdMin = 20.0;
int smokeThresholdMax = 150;

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
  Serial.begin(9600);
  pinMode(buzzer, OUTPUT);
  lcd.begin();
  lcd.setCursor(0, 0);
  // set up the LCD's number of columns and rows:
//  lcd.begin(16, 2);
//  // Print a message to the LCD.
//  lcd.print("BTS id = 123");
}

void loop() {
  analogReadTemp = analogRead(tempSensor);    //Tell the Arduino to read the voltage on pin A0
  gasValue = analogRead(gasSensor);   // Tell the Arduino to read the voltage on pin A1
  temperature = (5.0 * analogReadTemp)/1023.0; // Convert the read value into a voltage
  temperature /= 0.01;
  Serial.print("Temperature ");
  Serial.println(temperature);
  Serial.println("Gas Sensor");
  Serial.println(gasValue);
  lcd.print("Temp: ");
  lcd.print(temperature);
  lcd.print(char(223));
  lcd.print("C");
//  gasValue = 1024 - gasValue;     //
  lcd.setCursor(0, 1);
  lcd.print("Gas: ");
  lcd.print(gasValue);
  delay(500);
  if(checkSmokeState(gasValue)){
    digitalWrite(buzzer, HIGH);
  }
  else{
    digitalWrite(buzzer, LOW);
  }
  lcd.clear();
//  // set the cursor to column 3, line 1
//  lcd.setCursor(3, 1);
//   // Print the Temperature 
//   lcd.print("temperature reading is: "); 
//  lcd.print(Temperature); 
       
}

/*
 *  This function will detemine if threshold
 *  for temperature is reached  
 *  0 means temperature is ok
 *  1 means temperature is below min
 *  2 means temperature is above max
 */

int checkTemperatureState(float temp){
  if(temp > tempThresholdMax){
    return 2;
  }
  if(temp < tempThresholdMax){
    return 1;
  }
  return 0;
}

/*
 * this function will determine if threshold
 * for smoke 
 * 1 means smoke detected and 0 means no smoke
 */
int checkSmokeState(int gas){
  if(gas > smokeThresholdMax){
    return 1;
  }
  return 0;
}

