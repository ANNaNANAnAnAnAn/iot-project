#include <WiFi101.h>
#include <ArduinoHttpClient.h>

// Replace with your network credentials and server address
const char* ssid = "your_SSID";
const char* password = "your_PASSWORD";
const char* serverName = "your_server_address";

int status = WL_IDLE_STATUS;

const int flameSensorPin = 1; // Digital Pin D1
const int buzzerPin = 2; // Digital Pin D2

WiFiClient wifiClient;
HttpClient httpClient = HttpClient(wifiClient, serverName, 80);

void setup() {
  Serial.begin(115200);

  pinMode(flameSensorPin, INPUT);
  pinMode(buzzerPin, OUTPUT);

  while (status != WL_CONNECTED) {
    Serial.print("Attempting to connect to SSID: ");
    Serial.println(ssid);
    status = WiFi.begin(ssid, password);

    delay(10000); // wait 10 seconds for connection
  }

  Serial.println("Connected to WiFi");
  printWiFiStatus();
}

void loop() {
  int flameState = digitalRead(flameSensorPin);
  
  if (WiFi.status() == WL_CONNECTED) {
    String httpRequestData = "name=flame&value=" + String(flameState) + "&hour=" + getFormattedTime();

    httpClient.beginRequest();
    httpClient.post("/api.php"); // Adjust the path to your server script
    httpClient.sendHeader("Content-Type", "application/x-www-form-urlencoded");
    httpClient.sendHeader("Content-Length", httpRequestData.length());
    httpClient.beginBody();
    httpClient.print(httpRequestData);
    httpClient.endRequest();

    int statusCode = httpClient.responseStatusCode();
    String response = httpClient.responseBody();

    Serial.print("Status Code: ");
    Serial.println(statusCode);
    Serial.print("Response: ");
    Serial.println(response);
  } else {
    Serial.println("Error in WiFi connection");
  }

  if (flameState == HIGH) {
    digitalWrite(buzzerPin, HIGH);
  } else {
    digitalWrite(buzzerPin, LOW);
  }

  delay(10000); // Send data every 10 seconds
}

String getFormattedTime() {
  // Replace with actual time function
  return "2024-04-23 00:08:00";
}

void printWiFiStatus() {
  // print the SSID of the network you're connected to
  Serial.print("SSID: ");
  Serial.println(WiFi.SSID());

  // print your board's IP address
  IPAddress ip = WiFi.localIP();
  Serial.print("IP Address: ");
  Serial.println(ip);

  // print the received signal strength
  long rssi = WiFi.RSSI();
  Serial.print("Signal strength (RSSI):");
  Serial.print(rssi);
  Serial.println(" dBm");
}
