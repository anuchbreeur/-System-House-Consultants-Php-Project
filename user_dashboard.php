<?php
session_start();
include 'db_config.php';

// Check if user is logged in
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch all employees
$employees = [];
$query = "SELECT * FROM employees";
$result = $conn->query($query);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }
}

// Search for a specific employee
$search_result = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_emp_no'])) {
    $search_emp_no = $_POST['search_emp_no'];
    $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_no = ?");
    $stmt->bind_param("s", $search_emp_no);
    $stmt->execute();
    $search_result = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Dashboard</h2>
        <a href="user_logout.php" class="btn btn-danger mb-3">Logout</a>
        <form method="POST" class="mb-3">
            <div class="input-group">
                <input type="text" name="search_emp_no" class="form-control" placeholder="Search by Employee Number" required>
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>

        <?php if ($search_result): ?>
            <div class="alert alert-info">
                <h4>Search Result:</h4>
                <p>Employee Number: <?php echo $search_result['emp_no']; ?></p>
                <p>Department Code: <?php echo $search_result['dep_cd']; ?></p>
                <p>Name: <?php echo $search_result['name']; ?></p>
                <p>YTD Paid: <?php echo $search_result['ytd_paid']; ?></p>
                <p>Club Amount: <?php echo $search_result['club_amt']; ?></p>
                <p>Buffer: <?php echo $search_result['buffer']; ?></p>
                <p>Remark: <?php echo $search_result['remark']; ?></p>
            </div>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <div class="alert alert-danger">Employee not found.</div>
        <?php endif; ?>

        <h4>All Employees</h4>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Employee Number</th>
                    <th>Department Code</th>
                    <th>Name</th>
                    <th>YTD Paid</th>
                    <th>Club Amount</th>
                    <th>Buffer</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?php echo $employee['emp_no']; ?></td>
                        <td><?php echo $employee['dep_cd']; ?></td>
                        <td><?php echo $employee['name']; ?></td>
                        <td><?php echo $employee['ytd_paid']; ?></td>
                        <td><?php echo $employee['club_amt']; ?></td>
                        <td><?php echo $employee['buffer']; ?></td>
                        <td><?php echo $employee['remark']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>