<?php

header('Content-Type: application/json');

$data_dir = 'ti/api/';
$sensor_dirs = [
    'flame' => $data_dir . 'flame/',
    'photoresistor' => $data_dir . 'photoresistor/',
    'led' => $data_dir . 'led/',
    'buzzer' => $data_dir . 'buzzer/'
];

// Ensure directories exist
foreach ($sensor_dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
}

$request_method = $_SERVER['REQUEST_METHOD'];

if ($request_method == 'POST') {
    $name = $_POST['name'] ?? null;
    $value = $_POST['value'] ?? null;
    $hour = $_POST['hour'] ?? null;

    if (isset($name, $value, $hour) && array_key_exists($name, $sensor_dirs)) {
        file_put_contents($sensor_dirs[$name] . 'state.txt', $value);
        file_put_contents($sensor_dirs[$name] . 'date.txt', $hour);
        file_put_contents($sensor_dirs[$name] . 'history.txt', $hour . ',' . $value . PHP_EOL, FILE_APPEND);

        echo json_encode(['status' => 'success', 'message' => 'Data saved successfully']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid parameters or sensor name']);
    }
} elseif ($request_method == 'GET') {
    if (isset($_GET['name']) && array_key_exists($_GET['name'], $sensor_dirs)) {
        $name = $_GET['name'];
        $state = trim(file_get_contents($sensor_dirs[$name] . 'state.txt'));
        $date = trim(file_get_contents($sensor_dirs[$name] . 'date.txt'));

        echo json_encode([
            'state' => $state,
            'date' => $date
        ]);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid parameters or sensor name']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}

?>
