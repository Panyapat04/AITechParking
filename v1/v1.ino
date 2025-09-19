#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "dewdy";
const char* password = "12345678";
const char* serverNameadd  = "http://10.239.232.60/3stepnotyet/esp32/esp32_add_data.php";
const char* serverNameread = "http://10.239.232.60/3stepnotyet/esp32/esp32_read_data.php";

const int servoPin = 12;
const int ledPin = 2;

const int trigPin = 18;
const int echoPin = 19;

#define SOUND_SPEED 0.034
#define CM_TO_INCH 0.393701

long duration;
float distanceCm;

void servo90()
{
  for(int i=0;i<=50;i++){
    digitalWrite(servoPin, LOW);delay(19);
    digitalWrite(servoPin, HIGH);delay(1);    
  }
}G0WW371DS16L4X0M43402801F

void servo180()
{
  for(int i=0;i<=50;i++){
    digitalWrite(servoPin, LOW);delay(18);
    digitalWrite(servoPin, HIGH);delay(2);    
  }
}

float sr04()
{
  digitalWrite(trigPin, LOW);
  delayMicroseconds(2);
  // Sets the trigPin on HIGH state for 10 micro seconds
  digitalWrite(trigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(trigPin, LOW);
  
  // Reads the echoPin, returns the sound wave travel time in microseconds
  duration = pulseIn(echoPin, HIGH);
  
  // Calculate the distance
  distanceCm = duration * SOUND_SPEED/2;
  return(distanceCm);
}

void setup() {
  Serial.begin(115200);
  pinMode(2, OUTPUT);
  pinMode(4, OUTPUT);
  pinMode(12, OUTPUT);

  pinMode(trigPin, OUTPUT); 
  pinMode(echoPin, INPUT);

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("Connected");
  
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverNameread);
    int httpCode = http.GET();
   
    if (httpCode == 200) {
      String dir = http.getString();
      Serial.println("Direction: " + dir);
      if (dir == "1") {
        digitalWrite(2, HIGH);
        digitalWrite(4, LOW);
        if(sr04()<=20)
          {
            servo90();//เปิด        
            while(sr04()<=20)
             {
              delay(200);
              Serial.println(sr04());
              delay(200);
             }
          }
          delay(3000);
          servo180();//ปิด
          http.begin(serverNameadd);
          http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    
          String postData = "status=" + String(0);
          httpCode = http.POST(postData);
    
          if (httpCode > 0) {
            String response = http.getString();
            Serial.println("Update Response: " + response);
          } else {
            Serial.println("Failed to update");
          }
             
      } else {
        digitalWrite(2, LOW);
        digitalWrite(4, HIGH);
        servo180();
      }
    } else {
      Serial.println("HTTP Error");
    }
    http.end();      
  }
  delay(500);
  Serial.println(sr04());
}
