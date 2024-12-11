<?php

header('Content-Type: application/json');

// Database configuration
$db_host = 'host';
$db_name = 'dbname';
$db_user = 'name';
$db_pass = 'pass';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ensure tables are created
    createTables($pdo);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Function to create tables if they don't exist
function createTables($pdo)
{
    $sensorsTable = "
        CREATE TABLE IF NOT EXISTS sensors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            value VARCHAR(255) NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
    ";

    $actuatorsTable = "
        CREATE TABLE IF NOT EXISTS actuators (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            state VARCHAR(255) NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
    ";

    $pdo->exec($sensorsTable);
    $pdo->exec($actuatorsTable);
}

// Supported devices
$valid_sensors = ['photoresistor', 'hall_sensor', 'flame'];
$valid_actuators = ['led', 'buzzer', 'servo'];

// Get the request method
$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method == 'POST') {
    $name = $_POST['name'] ?? null;
    $value = $_POST['value'] ?? null;
    $type = $_POST['type'] ?? null;

    if (!$name || !$value || !$type) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
        exit;
    }

    if ($type == 'sensor') {
        if (!in_array($name, $valid_sensors)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid sensor name']);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO sensors (name, value) VALUES (:name, :value)");
        $stmt->execute([':name' => $name, ':value' => $value]);
    } elseif ($type == 'actuator') {
        if (!in_array($name, $valid_actuators)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid actuator name']);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO actuators (name, state) VALUES (:name, :state)");
        $stmt->execute([':name' => $name, ':state' => $value]);
    }

    echo json_encode(['status' => 'success', 'message' => 'Data saved successfully']);
} elseif ($request_method == 'GET') {
    $name = $_GET['name'] ?? null;
    $type = $_GET['type'] ?? null;

    if (!$name || !$type) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
        exit;
    }

    if ($type == 'sensor') {
        if (!in_array($name, $valid_sensors)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid sensor name']);
            exit;
        }
        $stmt = $pdo->prepare("SELECT value, timestamp FROM sensors WHERE name = :name ORDER BY timestamp DESC LIMIT 1");
    } elseif ($type == 'actuator') {
        if (!in_array($name, $valid_actuators)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid actuator name']);
            exit;
        }
        $stmt = $pdo->prepare("SELECT state AS value, timestamp FROM actuators WHERE name = :name ORDER BY timestamp DESC LIMIT 1");
    }

    $stmt->execute([':name' => $name]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        echo json_encode($data);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No data found']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>
