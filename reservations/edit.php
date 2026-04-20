<?php
include 'koneksi.php';

$id = $_GET['id'];
if ($_POST) {
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $spot_number = mysqli_real_escape_string($conn, $_POST['spot_number']);
    $entry_time = $_POST['entry_time'];
    $exit_time = $_POST['exit_time'];
    $payment_status = $_POST['payment_status'];
    
    $query = "UPDATE reservations SET user_name='$user_name', user_email='$user_email', spot_number='$spot_number', entry_time='$entry_time', exit_time='$exit_time', payment_status='$payment_status' WHERE id=$id";
    
    if (mysqli_query($conn, $query)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

$result = mysqli_query($conn, "SELECT * FROM reservations WHERE id=$id");
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h5>Edit Reservation ID: <?php echo $row['id']; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">User Name</label>
                        <input type="text" name="user_name" class="form-control" value="<?php echo $row['user_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">User Email</label>
                        <input type="email" name="user_email" class="form-control" value="<?php echo $row['user_email']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Spot Number</label>
                        <input type="text" name="spot_number" class="form-control" value="<?php echo $row['spot_number']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Entry Time</label>
                        <input type="datetime-local" name="entry_time" class="form-control" value="<?php echo $row['entry_time']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Exit Time</label>
                        <input type="datetime-local" name="exit_time" class="form-control" value="<?php echo $row['exit_time']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-control">
                            <option value="Pending" <?php echo $row['payment_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Paid" <?php echo $row['payment_status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

