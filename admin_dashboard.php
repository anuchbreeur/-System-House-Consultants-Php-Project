<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all users
$userResult = $conn->query("SELECT * FROM users");

// Fetch all employees
$employeeResult = $conn->query("SELECT * FROM employees");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_employee'])) {
        // Add new employee
        $emp_no = $_POST['emp_no'];
        $dep_cd = $_POST['dep_cd'];
        $name = $_POST['name'];
        $ytd_paid = $_POST['ytd_paid'];
        $club_amt = $_POST['club_amt'];
        $buffer = $_POST['buffer'];
        $remark = $_POST['remark'];
        $conn->query("INSERT INTO employees (emp_no, dep_cd, name, ytd_paid, club_amt, buffer, remark) VALUES ('$emp_no', '$dep_cd', '$name', '$ytd_paid', '$club_amt', '$buffer', '$remark')");
        header("Location: admin_dashboard.php");
    }
    if (isset($_POST['delete_employee'])) {
        // Delete employee
        $emp_no = $_POST['emp_no'];
        $conn->query("DELETE FROM employees WHERE emp_no = '$emp_no'");
        header("Location: admin_dashboard.php");
    }
    if (isset($_POST['update_employee'])) {
        // Update employee
        $emp_no = $_POST['emp_no'];
        $dep_cd = $_POST['dep_cd'];
        $name = $_POST['name'];
        $ytd_paid = $_POST['ytd_paid'];
        $club_amt = $_POST['club_amt'];
        $buffer = $_POST['buffer'];
        $remark = $_POST['remark'];
        $conn->query("UPDATE employees SET dep_cd = '$dep_cd', name = '$name', ytd_paid = '$ytd_paid', club_amt = '$club_amt', buffer = '$buffer', remark = '$remark' WHERE emp_no = '$emp_no'");
        header("Location: admin_dashboard.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { padding-top: 20px; }
        .table-actions { display: flex; gap: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <a href="admin_logout.php" class="btn btn-danger mb-3">Logout</a>

        <h3>Users</h3>
        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $userResult->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['role']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3>Employees</h3>
        <form method="post" class="mb-3">
            <div class="form-row">
                <div class="col">
                    <input type="text" class="form-control" name="emp_no" placeholder="Employee Number" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="dep_cd" placeholder="Department Code" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="name" placeholder="Name" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="ytd_paid" placeholder="YTD Paid" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="club_amt" placeholder="Club Amount" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="buffer" placeholder="Buffer" required>
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="remark" placeholder="Remark" required>
                </div>
                <div class="col">
                    <button type="submit" name="add_employee" class="btn btn-success">Add Employee</button>
                </div>
            </div>
        </form>

        <table class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>Employee Number</th>
                    <th>Department Code</th>
                    <th>Name</th>
                    <th>YTD Paid</th>
                    <th>Club Amount</th>
                    <th>Buffer</th>
                    <th>Remark</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($employee = $employeeResult->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $employee['emp_no']; ?></td>
                    <td><?php echo $employee['dep_cd']; ?></td>
                    <td><?php echo $employee['name']; ?></td>
                    <td><?php echo $employee['ytd_paid']; ?></td>
                    <td><?php echo $employee['club_amt']; ?></td>
                    <td><?php echo $employee['buffer']; ?></td>
                    <td><?php echo $employee['remark']; ?></td>
                    <td class="table-actions">
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="emp_no" value="<?php echo $employee['emp_no']; ?>">
                            <button type="submit" name="delete_employee" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="emp_no" value="<?php echo $employee['emp_no']; ?>">
                            <input type="text" name="dep_cd" value="<?php echo $employee['dep_cd']; ?>" required>
                            <input type="text" name="name" value="<?php echo $employee['name']; ?>" required>
                            <input type="text" name="ytd_paid" value="<?php echo $employee['ytd_paid']; ?>" required>
                            <input type="text" name="club_amt" value="<?php echo $employee['club_amt']; ?>" required>
                            <input type="text" name="buffer" value="<?php echo $employee['buffer']; ?>" required>
                            <input type="text" name="remark" value="<?php echo $employee['remark']; ?>" required>
                            <button type="submit" name="update_employee" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>