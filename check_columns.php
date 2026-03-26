<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'real_estate_saas';

$conn = new mysqli($host, $user, $pass, $db);

function printColumns($conn, $table) {
    echo "--- Columns for $table ---\n";
    $result = $conn->query("DESCRIBE $table");
    if ($result) {
        while($row = $result->fetch_assoc()) {
            echo $row['Field'] . "\n";
        }
    } else {
        echo "Error: " . $conn->error . "\n";
    }
}

printColumns($conn, 'projects');
printColumns($conn, 'project_types');

$conn->close();
