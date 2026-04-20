<?php
include 'koneksi.php';

if ($_POST) {
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $spot_number = mysqli_real_escape_string($conn, $_POST['spot_number']);
    $entry_time = $_POST['entry_time'];
    $exit_time = $_POST['exit_time'];
    $payment_status = 'Pending';
    
    $query = "INSERT INTO reservations (user_name, user_email, spot_number, entry_time, exit_time, payment_status) VALUES ('$user_name', '$user_email', '$spot_number', '$entry_time', '$exit_time', '$payment_status')";
    
    if (mysqli_query($conn, $query)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

