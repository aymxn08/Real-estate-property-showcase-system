<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'real_estate_saas';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT id, project_name FROM projects WHERE slug IS NULL OR slug = ''");

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $name = $row['project_name'];
        $baseSlug = substr(strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name))), 0, 200);
        
        $slug = $baseSlug;
        $counter = 1;
        
        // Ensure uniqueness
        while (true) {
            $check = $conn->query("SELECT id FROM projects WHERE slug = '$slug' AND id != $id");
            if ($check->num_rows == 0) break;
            $slug = $baseSlug . '-' . $counter++;
        }
        
        $stmt = $conn->prepare("UPDATE projects SET slug = ? WHERE id = ?");
        $stmt->bind_param("si", $slug, $id);
        $stmt->execute();
        echo "Updated Project ID $id with slug: $slug\n";
    }
} else {
    echo "No projects to update.\n";
}

$conn->close();
