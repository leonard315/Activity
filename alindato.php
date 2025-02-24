<?php
// Set headers for JSON response
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Database connection
$host = "localhost";
$username = "root";  // Change if you have a different MySQL user
$password = "";      // Change if you have a MySQL password
$database = "leonard"; // Correct database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check if required data is provided
if (isset($data['name'], $data['age'], $data['birthday'])) {
    $name = $data['name'];  // Fixed variable name
    $age = $data['age'];
    $birthday = $data['birthday'];

    // Prepare and execute SQL query
    $stmt = $conn->prepare("INSERT INTO users (name, age, birthday) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $name, $age, $birthday);

    if ($stmt->execute()) {
        echo json_encode(["success" => "Data inserted successfully"]);
    } else {
        echo json_encode(["error" => "Insertion failed"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid input data"]);
}

$conn->close();
?>
