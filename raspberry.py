import RPi.GPIO as GPIO
import time
import requests

# Setup
SSID = "LigaT_56424E"  # Your WiFi SSID
PASSWORD = "wlan2f195f"  # Your WiFi Password
SERVER_URL = "http://localhost:78/project_TI/api.php"  # Replace with your server address

PHOTORESISTOR_PIN = 17  # GPIO pin for photoresistor
LED_PIN = 27  # GPIO pin for LED

GPIO.setmode(GPIO.BCM)
GPIO.setup(PHOTORESISTOR_PIN, GPIO.IN)
GPIO.setup(LED_PIN, GPIO.OUT)

def get_photoresistor_state():
    return GPIO.input(PHOTORESISTOR_PIN)

def send_data(name, value, hour):
    data = {
        'name': name,
        'value': value,
        'hour': hour
    }
    response = requests.post(SERVER_URL, data=data)
    return response

while True:
    photoresistor_state = get_photoresistor_state()
    
    # Send photoresistor state to server
    current_time = time.strftime("%Y-%m-%d %H:%M:%S")
    send_data('photoresistor', photoresistor_state, current_time)
    
    # Turn on LED if photoresistor value is below threshold
    if photoresistor_state < 450:
        GPIO.output(LED_PIN, GPIO.HIGH)
    else:
        GPIO.output(LED_PIN, GPIO.LOW)
    
    time.sleep(10)
