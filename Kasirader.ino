
#include <SoftwareSerial.h>
SoftwareSerial espSerial(11, 10);// RX, TX
#include <dht11.h>
dht11 DHT11;

#define SSID "Geekulcha"
#define PASS "geekulcha@2017"
#define THINGSPEAK_IP "184.106.153.149"// thingspeak.com
#define host "192.168.1.102"// Server
#define MAX_FAILS 3


unsigned long sample_interval = 5000;
unsigned long last_time;
int fails = 0;
int alert= 8;
int MQ7 = A0;
int MQ5 = A1;

void setup() 
{
  
  pinMode(MQ7, INPUT);   //gas sensor
  pinMode(MQ5, INPUT);   //gas sensor
  pinMode(alert, OUTPUT);   //alert led 
  delay(3000);         
 
  Serial.begin(115200);    
  espSerial.begin(115200);
  espSerial.setTimeout(2000);
  DHT11.attach(12);
  last_time = millis();  // get the current time;
 
  Serial.print(F("Send sensor data to ThingSpeak and server Database"));
  Serial.print(F("every "));
  Serial.print(sample_interval/1000);
  Serial.println(F("s."));
  
  if (!resetESP()) return;
  if (!connectWiFi()) return;
}

void echoAll() {
  while (espSerial.available()) {
    char c = espSerial.read();
    Serial.write(c);
    if(c=='\r') Serial.print('\n');
  }
}

boolean resetESP() {
   // test if module ready
   Serial.print(F("reset ESP8266..."));
 
   // physically reset EPS module
   digitalWrite(9, LOW);          
   delay(100);
   digitalWrite(9, HIGH);  
   delay(500);
   
   if (!send("AT+RST", "ready", F("%% module no response"))) return false;
   
   Serial.print(F("module ready..."));
   return true;
}

boolean connectWiFi() {
  int tries = 5;
  
  while(tries-- > 0 && !tryConnectWiFi());
  
  if (tries <= 0) {
    Serial.println(F("%% tried X times to connect, please reset"));
    return false;
  }
  
  delay(500); // send and wait for correct response?
  
  // set the single connection mode
  espSerial.println("AT+CIPMUX=0");
  
  delay(500); // send and wait for correct response?

  
  return true;
}

boolean tryConnectWiFi() {
   espSerial.println("AT+CWMODE=1");
   delay(2000); // send and wait for correct response?
   
   String cmd="AT+CWJAP=\"";
   cmd+=SSID;
   cmd+="\",\"";
   cmd+=PASS;
   cmd+="\"";
   
   if (!send(cmd, "OK", F("%% cannot connect to wifi..."))) return false;
   
   Serial.println(F("WiFi OK..."));
   return true;
}

boolean send(String cmd, char* waitFor, String errMsg) {
    espSerial.println(cmd);
    if (!espSerial.find(waitFor)) {
      Serial.print(errMsg);
      return false;
    }
    return true;
}

boolean connect(char* ip) {
   String cmd; 
   cmd = "AT+CIPSTART=\"TCP\",\"";
   cmd += ip;
   cmd += "\",80";
   espSerial.println(cmd);
 
   if(espSerial.find("Error")) return false;
   return true;
}

boolean sendGET(String path) {
   String cmd = "GET ";
   cmd += path;
   
   // Part 1: send info about data to send
   String xx = "AT+CIPSEND=";
   xx += cmd.length();
   if (!send(xx, ">", F("%% connect timeout"))) return false;
   Serial.print(">");
   
   // Part 2: send actual data
   if (!send(cmd, "SEND OK", F("%% no response"))) return false;
   
   return true;
}

void loop() {
  
  if (millis()-last_time < sample_interval) return;
  
    int chk = DHT11.read();
    float t = DHT11.temperature;
    float  h = DHT11.humidity;
    
    float CO  = analogRead(MQ7);
    float LPG = analogRead(MQ5);
    
    last_time = millis();

  Serial.print("data =");
  Serial.print(t, 2);
  Serial.print("C");
  Serial.print(" ");
  Serial.print(h, 2);
  Serial.print("% ");
  Serial.print(" ");
  Serial.print(CO, 2);
  Serial.print(" ppm ");
  Serial.print(" ");
  Serial.print(LPG, 2);
  Serial.print(" ppm ");



  ///send temperature and humidity data to thingspeak      
  if (!sendDataThingSpeak(t,h)) {
    Serial.println(F("%% failed sending data"));
    // we failed X times, at MAX_FAILS reconnect till it works
    if (fails++ > MAX_FAILS) {
      if (!resetESP()) return;
      if (!connectWiFi()) return;
    }
  } else {
    fails = 0;
  }

  ///send gas sensor data to thingspeak
    if (!sendDataThingSpeak1(CO,LPG)) {
    Serial.println(F("%% failed sending data"));
    if (fails++ > MAX_FAILS) {
      if (!resetESP()) return;
      if (!connectWiFi()) return;
    }
  } else {
    fails = 0;
  }

  ///send gas sensor data to databse
  if (!sendDataThingSpeak1(t,h,CO,LPG)) {
    Serial.println(F("%% failed sending data"));
   
    if (fails++ > MAX_FAILS) {
      if (!resetESP()) return;
      if (!connectWiFi()) return;
    }
  } else {
    fails = 0;
 
  }
  digitalWrite(alert,HIGH);
  delay(2000);
  digitalWrite(alert,LOW);
  Serial.println();
}

boolean sendDataThingSpeak(float temp, float hum) {
   if (!connect(THINGSPEAK_IP)) return false;

   String path = "/update?key=UJVEZ0KW6KXN34JH&field1=";
   path += temp;
   path += "&field2=";
   path += hum;
   path += "\r\n";
   if (!sendGET(path)) return false;
   
   Serial.print(F(" thingspeak.com OK"));
   return true;
}
boolean sendDataThingSpeak1(float carbon, float petrolium) {
   if (!connect(THINGSPEAK_IP)) return false;

   String path = "/update?key=UJVEZ0KW6KXN34JH&field3=";
   path += petrolium;
   path += "&field4=";
   path += carbon;
   path += "\r\n";
   if (!sendGET(path)) return false;
   
   Serial.print(F(" thingspeak.com OK"));
   return true;
}
//send data to server database
boolean sendDataThingSpeak1(float temp,float hum, float carbon, float petrolium) {
  if (!connect(host)) return false;
   String path = "/krem/store/data.php?code=loyd&t=";
   path += temp;
   path += "&h=";
   path += hum;
   path += "&c=";
   path += carbon;
   path += "&n=";
   path += petrolium;
   path += "\r\n";
   if (!sendGET(path)) return false;
      
   Serial.print(F(" database OK"));
   return true;
}
