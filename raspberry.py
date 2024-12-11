import RPi.GPIO as GPIO
import time
import requests
from gpiozero import Servo

# Server URL
SERVER_URL = "http://localhost:78/project_TI/api.php"  # Replace with your actual API endpoint

# GPIO pin assignments
PHOTORESISTOR_PIN = 17  # GPIO pin for photoresistor
LED_PIN = 27           # GPIO pin for LED
FLAME_SENSOR_PIN = 5   # GPIO pin for flame sensor
BUZZER_PIN = 6         # GPIO pin for buzzer
HALL_SENSOR_PIN = 13   # GPIO pin for linear hall sensor
SERVO_PIN = 18         # GPIO pin for servo motor

# Setup GPIO
GPIO.setmode(GPIO.BCM)
GPIO.setup(PHOTORESISTOR_PIN, GPIO.IN)
GPIO.setup(LED_PIN, GPIO.OUT)
GPIO.setup(FLAME_SENSOR_PIN, GPIO.IN)
GPIO.setup(BUZZER_PIN, GPIO.OUT)
GPIO.setup(HALL_SENSOR_PIN, GPIO.IN)

# Servo setup (using gpiozero library)
servo = Servo(SERVO_PIN)
door_open = False  # Tracks whether the door is open or closed

# Function to send data to the server
def send_data(name, value, data_type):
    data = {
        'name': name,
        'value': value,
        'type': data_type
    }
    try:
        response = requests.post(SERVER_URL, data=data)
        if response.status_code == 200:
            print(f"Data sent successfully: {response.json()}")
        else:
            print(f"Failed to send data: {response.status_code} {response.text}")
    except requests.exceptions.RequestException as e:
        print(f"Error communicating with the server: {e}")

# Photoresistor and LED logic
def handle_photoresistor():
    photoresistor_state = GPIO.input(PHOTORESISTOR_PIN)
    current_time = time.strftime("%Y-%m-%d %H:%M:%S")
    send_data('photoresistor', photoresistor_state, 'sensor')

    if photoresistor_state < 450:  # Adjust threshold as needed
        GPIO.output(LED_PIN, GPIO.HIGH)
        print("Low light detected. LED turned on.")
    else:
        GPIO.output(LED_PIN, GPIO.LOW)
        print("Sufficient light detected. LED turned off.")

# Flame detection logic
def handle_flame_detection():
    if GPIO.input(FLAME_SENSOR_PIN) == 0:  # Assuming 0 indicates flame detected
        GPIO.output(BUZZER_PIN, GPIO.HIGH)  # Turn on the buzzer
        print("Flame detected! Buzzer activated.")
        send_data('flame', 'detected', 'sensor')
    else:
        GPIO.output(BUZZER_PIN, GPIO.LOW)  # Turn off the buzzer
        print("No flame detected.")
        send_data('flame', 'not_detected', 'sensor')

# Hall sensor logic for door control
def handle_hall_sensor():
    global door_open
    if GPIO.input(HALL_SENSOR_PIN) == 0:  # Assuming 0 indicates magnetic key detected
        if not door_open:
            servo.max()  # Open the door
            print("Magnetic key detected. Door opened.")
            send_data('door', 'opened', 'actuator')
            door_open = True
        else:
            servo.min()  # Close the door
            print("Magnetic key detected again. Door closed.")
            send_data('door', 'closed', 'actuator')
            door_open = False

# Main loop
try:
    while True:
        handle_photoresistor()       # Check photoresistor and control LED
        handle_flame_detection()    # Check flame sensor and manage buzzer
        handle_hall_sensor()        # Check hall sensor and manage servo door
        time.sleep(0.5)             # Adjust delay for smoother operation

except KeyboardInterrupt:
    print("Script interrupted by user.")

finally:
    GPIO.cleanup()
