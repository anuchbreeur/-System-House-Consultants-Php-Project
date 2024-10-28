<?php
include 'db_config.php';

// Hash the password
$admin_password = password_hash('admin_password', PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
$admin_username = 'admin';
$stmt->bind_param("ss", $admin_username, $admin_password);

if ($stmt->execute()) {
    echo "Admin user created successfully";
} else {
    echo "Error creating admin user: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
