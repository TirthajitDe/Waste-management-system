<?php
session_start();
include("connect.php"); // Database 'waste_db' connect karega

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: auth.php");
    exit();
}

// Fetch requests with user names
$sql = "SELECT wr.*, u.name as customer_name 
        FROM waste_requests wr 
        JOIN users u ON wr.user_id = u.id 
        ORDER BY wr.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | EcoCollect</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

    <header>
        <div class="nav-left">
            <span class="logo-icon">🌱</span>
            <span class="logo-text">EcoCollect Admin</span>
        </div>
        <div class="nav-right">
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main class="admin-container">
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Requests</h3>
                <p><?php echo $result->num_rows; ?></p>
            </div>
        </div>

        <div class="table-card">
            <div class="table-header">
                <h2>User Requests</h2>
                <p>Track and manage waste pickup schedules</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Waste Details</th>
                        <th>Pickup Date</th>
                        <th>Address & Location</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['customer_name']); ?></strong><br>
                                    <small>ID: #<?php echo $row['id']; ?></small>
                                </td>
                                <td>
                                    <span class="badge type"><?php echo $row['waste_type']; ?></span><br>
                                    <small>Qty: <?php echo $row['quantity']; ?></small>
                                </td>
                                <td><?php echo date('d M, Y', strtotime($row['pickup_date'])); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($row['location']); ?><br>
                                    <?php if (!empty($row['coordinates'])): ?>
                                        <a href="https://www.google.com/maps?q=<?php echo $row['coordinates']; ?>" target="_blank" class="map-link">
                                            <i class="fas fa-map-marker-alt"></i> View on Map
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td><strong>₹<?php echo $row['amount']; ?></strong></td>
                                <td>
                                    <span class="status-pill <?php echo strtolower($row['status']); ?>">
                                        <?php echo $row['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <form action="update_status.php" method="POST" class="update-form">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <select name="status">
                                            <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="Ongoing" <?php if ($row['status'] == 'Ongoing') echo 'selected'; ?>>Ongoing</option>
                                            <option value="Completed" <?php if ($row['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                        </select>
                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="no-data">No Requests Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>

</html>