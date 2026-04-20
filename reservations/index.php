<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations - Manage booking and reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>Reservations</h1>
                <p class="text-muted">Manage booking and reservations</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newReservationModal">
                New Reservation
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Spot</th>
                                <th>Time</th>
                                <th>Cost</th>
                                <th>Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'koneksi.php';
                            $result = mysqli_query($conn, "SELECT * FROM reservations ORDER BY created_at DESC");
                            while ($row = mysqli_fetch_assoc($result)) {
                                $duration = strtotime($row['exit_time']) - strtotime($row['entry_time']);
                                $hours = $duration / 3600;
                                $cost = round($hours * 5000);
                                $status = $row['payment_status'] == 'Paid' ? 'badge bg-success' : 'badge bg-warning';
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['user_name'] . ' <br><small>' . $row['user_email'] . '</small>'; ?></td>
                                    <td><?php echo $row['spot_number']; ?></td>
                                    <td><?php echo date('H:i d/m', strtotime($row['entry_time'])) . ' - ' . date('H:i d/m', strtotime($row['exit_time'])); ?></td>
                                    <td>Rp <?php echo number_format($cost); ?></td>
                                    <td><span class="badge <?php echo $status; ?>"><?php echo $row['payment_status']; ?></span></td>
                                    <td>
                                        <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">View</a>
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- New Reservation Modal -->
    <div class="modal fade" id="newReservationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Reservation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="tambah.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">User Name</label>
                            <input type="text" name="user_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">User Email</label>
                            <input type="email" name="user_email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Spot Number</label>
                            <input type="text" name="spot_number" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Entry Time</label>
                            <input type="datetime-local" name="entry_time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Exit Time</label>
                            <input type="datetime-local" name="exit_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Reservation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
