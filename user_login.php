<?php
session_start();
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emp_no = trim($_POST['emp_no']);

    $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_no = ?");
    $stmt->bind_param("s", $emp_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && trim($user['emp_no']) === $emp_no) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['emp_no'] = $emp_no;
        header("Location: user_dashboard.php");
        exit();
    } else {
        $error = "Invalid employee number!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px; 
            padding: 20px; 
            background-color: white; 
            border-radius: 8px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center">User Login</h2>
        <form method="POST">
            <div class="mb-3">
                <label>Employee Number</label>
                <input type="text" name="emp_no" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
            <?php if (isset($error)) { echo "<p class='text-danger mt-3'>$error</p>"; } ?>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>