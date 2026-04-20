<?php
include 'koneksi.php';

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM reservations WHERE id=$id");
$row = mysqli_fetch_assoc($result);

$duration = strtotime($row['exit_time']) - strtotime($row['entry_time']);
$hours = $duration / 3600;
$cost = round($hours * 5000);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>Reservation Detail - ID: <?php echo $row['id']; ?></h5>
                <a href="index.php" class="btn btn-secondary">← Back</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>User Information</h6>
                        <p><strong>Name:</strong> <?php echo $row['user_name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $row['user_email']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Parking Details</h6>
                        <p><strong>Spot:</strong> <?php echo $row['spot_number']; ?></p>
                        <p><strong>Entry Time:</strong> <?php echo date('d M Y H:i', strtotime($row['entry_time'])); ?></p>
                        <p><strong>Exit Time:</strong> <?php echo date('d M Y H:i', strtotime($row['exit_time'])); ?></p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h6>Cost</h6>
                        <p><strong>Duration:</strong> <?php echo number_format($hours, 1); ?> hours</p>
                        <p><strong>Total:</strong> <span class="badge bg-success fs-5">Rp <?php echo number_format($cost); ?></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Payment Status</h6>
                        <span class="badge <?php echo $row['payment_status'] == 'Paid' ? 'bg-success' : 'bg-warning'; ?> fs-5"><?php echo $row['payment_status']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

