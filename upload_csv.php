<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_POST['upload_csv'])) {
    // Check if the file is uploaded
    if (is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, "r");

        fgetcsv($handle, 1000, ",");

        // Loop through each row of the CSV file
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $emp_no = $data[0];
            $dep_cd = $data[1];
            $name = $data[2];
            $ytd_paid = $data[3];
            $club_amt = $data[4];
            $buffer = $data[5];
            $remark = $data[6];

            $stmt = $conn->prepare("INSERT INTO employees (emp_no, dep_cd, name, ytd_paid, club_amt, buffer, remark) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdids", $emp_no, $dep_cd, $name, $ytd_paid, $club_amt, $buffer, $remark);
            $stmt->execute();
        }
        fclose($handle);

        $_SESSION['message'] = "CSV file data has been imported successfully.";
    } else {
        $_SESSION['error'] = "File upload failed. Please try again.";
    }

    header("Location: admin_dashboard.php");
    exit();
}
?>
