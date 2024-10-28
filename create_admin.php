<?php
include 'db_config.php';

// Hash the password
$admin_password = password_hash('admin_password', PASSWORD_DEFAULT);

// Prepare and execute the SQL statement to insert the admin user
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
$admin_username = 'admin';
$stmt->bind_param("ss", $admin_username, $admin_password);

if ($stmt->execute()) {
    echo "Admin user created successfully";
} else {
    echo "Error creating admin user: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
