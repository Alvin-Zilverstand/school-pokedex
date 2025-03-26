<?php
$servername = "localhost:3306";
$username = "database1";
$password = "181t$1lJg";
$dbname = "pokedex1";

// Set the custom log file path
$logFile = __DIR__ . '/error_log.txt';

// Function to log messages
function logMessage($message) {
    global $logFile;
    error_log($message . "\n", 3, $logFile);
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    logMessage("Connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

header("Cache-Control: max-age=86400"); // Cache for 24 hours
header("Content-Type: application/json"); // Ensure JSON response

// Fetch competitors data
$sql = "SELECT user_id, COUNT(pokemon_id) AS pokemon_count
        FROM user_pokemon
        GROUP BY user_id
        ORDER BY pokemon_count DESC";
logMessage("Executing query: $sql");
$result = $conn->query($sql);

if ($result) {
    $competitors = [];
    while ($row = $result->fetch_assoc()) {
        $competitors[] = $row;
    }
    logMessage("Query result: " . json_encode($competitors));
    echo json_encode($competitors);
} else {
    logMessage("Error executing query: " . $conn->error);
    echo json_encode(["error" => "Error fetching competitors data"]);
}

$conn->close();
?>
