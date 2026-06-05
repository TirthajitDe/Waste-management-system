<?php
session_start();
// Database connection include karo
include("connect.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: auth.php");
    exit();
}

// User ID session se nikalna (Zaroori hai requests fetch karne ke liye)
$user_id = $_SESSION['user_id'];

// Database se user ki purani requests nikalna
$query = "SELECT * FROM waste_requests WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoCollect</title>
    <link rel="stylesheet" href="home_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>

<body>

    <header>
        <div class="nav-left">
            <span class="logo-icon">🌱</span>
            <span class="logo-text">EcoCollect</span>
        </div>
        <div class="nav-right">
            <span class="user-greet"><?php echo htmlspecialchars($_SESSION['role']); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main class="main-container">

        <div id="dashboard-view">
            <div class="page-header">
                <h1>Your pickups</h1>
                <p>Track requests and schedule new ones.</p>
            </div>

            <div class="request-container">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="dashboard-card" style="text-align:left; padding:20px; margin-bottom:15px; border-style:solid; display:block;">
                            <div style="display:flex; justify-content:space-between;">
                                <strong style="font-size:18px;"><?php echo $row['waste_type']; ?> Pickup</strong>
                                <span class="status-pill <?php echo strtolower($row['status']); ?>" style="padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; background:#fef3c7; color:#92400e;">
                                    <?php echo $row['status']; ?>
                                </span>
                            </div>
                            <p style="margin:10px 0; color:#6b7280;">
                                <i class="fas fa-calendar"></i> <?php echo date('d M, Y', strtotime($row['pickup_date'])); ?> |
                                <i class="fas fa-box"></i> <?php echo $row['quantity']; ?>
                            </p>
                            <small style="color:#9ca3af;"><i class="fas fa-map-marker-alt"></i> <?php echo $row['location']; ?></small>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="dashboard-card">
                        <h2>No requests yet</h2>
                        <p>Schedule your first waste pickup — it takes 30 seconds.</p>
                    </div>
                <?php endif; ?>
            </div>

            <button class="action-btn" onclick="showView('form')" style="margin-top:20px;">
                <i class="fas fa-plus"></i> CREATE REQUEST
            </button>
        </div>

        <div id="form-view" style="display: none;">
            <div class="page-header">
                <a href="javascript:void(0)" onclick="showView('dashboard')" style="text-decoration:none; color:#d85d31; font-size:14px; font-weight:600;">
                    ← Back to pickups
                </a>
                <h1 style="margin-top:15px;">Schedule a pickup</h1>
                <p>Pin your spot, choose your waste, and we'll take it from there.</p>
            </div>

            <div class="stepper">
                <span class="step active">1 Details</span>
                <span class="step">2 Payment</span>
            </div>

            <form id="wasteForm" action="submit_waste.php" method="POST">

                <div class="input-group">
                    <label>Waste type</label>
                    <div class="type-grid">
                        <input type="radio" name="waste_type" value="Household" id="t1" checked>
                        <label for="t1" class="type-card">Household</label>

                        <input type="radio" name="waste_type" value="Recyclable" id="t2">
                        <label for="t2" class="type-card">Recyclable</label>

                        <input type="radio" name="waste_type" value="E-Waste" id="t3">
                        <label for="t3" class="type-card">E-Waste</label>

                        <input type="radio" name="waste_type" value="Garden" id="t4">
                        <label for="t4" class="type-card">Garden</label>
                    </div>
                </div>

                <div class="input-group">
                    <label>Quantity</label>
                    <div class="quantity-grid">
                        <input type="radio" name="quantity" value="Small" id="q1" checked>
                        <label for="q1" class="qty-card">Small (1–2 bags)</label>

                        <input type="radio" name="quantity" value="Medium" id="q2">
                        <label for="q2" class="qty-card">Medium (3–6 bags)</label>

                        <input type="radio" name="quantity" value="Large" id="q3">
                        <label for="q3" class="qty-card">Large (Truck-load)</label>
                    </div>
                </div>
                

                <div class="input-group">
                    <label>Address</label>
                    <input type="text" name="location" placeholder="House / street / city" required>
                </div>

                <div style="display: flex; gap: 20px;">
                    <div class="input-group" style="flex: 1;">
                        <label>Preferred date</label>
                        <input type="date" name="pickup_date" required>
                    </div>
                    <div class="input-group" style="flex: 1;">
                        <label>Notes (optional)</label>
                        <input type="text" name="notes" placeholder="Gate code, etc.">
                    </div>
                </div>
                <div class="input-group">
                    <label>Pin your location on map</label>
                    <div id="map" style="width: 100%; height: 250px; border-radius: 12px; border: 1px solid #ddd; z-index: 1; margin-bottom: 20px;"></div>
                    <input type="hidden" id="latlng" name="coordinates" required>
                </div>

                <button type="button" id="submitBtn" class="action-btn" style="width: 100%; justify-content: center; padding: 16px; background: #9ca3af; cursor: not-allowed; border: none; color: white; font-weight: 600;" onclick="showView('payment')" disabled>
                    CONTINUE TO PAYMENT
                </button>
                


            </form>
        </div>

        <div id="payment-view" style="display: none;">
            <div class="page-header">
                <a href="javascript:void(0)" onclick="showView('form')" style="text-decoration:none; color:#d85d31; font-size:14px; font-weight:600;">
                    ← Back to details
                </a>
                <h1 style="margin-top:15px;">Payment</h1>
                <p>Dummy payment — no real charge.</p>
            </div>

            <div class="stepper">
                <span class="step completed">1 Details</span>
                <span class="step active">2 Payment</span>
            </div>

            <div class="payment-layout" style="display: flex; gap: 30px; align-items: flex-start;">

                <div style="flex: 1.5;">
                    <div class="input-group">
                        <label>Method</label>
                        <div class="payment-methods">
                            <input type="radio" name="pay_method" value="Card" id="p1" checked>
                            <label for="p1" class="pay-card">Credit / Debit card</label>

                            <input type="radio" name="pay_method" value="UPI" id="p2">
                            <label for="p2" class="pay-card">UPI</label>

                            <input type="radio" name="pay_method" value="Wallet" id="p3">
                            <label for="p3" class="pay-card">Wallet</label>

                            <input type="radio" name="pay_method" value="Cash" id="p4">
                            <label for="p4" class="pay-card">Cash on pickup</label>
                        </div>
                    </div>

                    <div id="card-details">
                        <div class="input-group">
                            <label>Name on card</label>
                            <input type="text" placeholder="John Doe">
                        </div>
                        <div class="input-group">
                            <label>Card number</label>
                            <input type="text" placeholder="4242 4242 4242 4242">
                        </div>
                        <div style="display: flex; gap: 15px;">
                            <div class="input-group" style="flex: 1;">
                                <label>Expiry</label>
                                <input type="text" placeholder="MM/YY">
                            </div>
                            <div class="input-group" style="flex: 1;">
                                <label>CVV</label>
                                <input type="text" placeholder="123">
                            </div>
                        </div>
                    </div>

                    <button type="button" class="action-btn" style="width: 100%; justify-content: center; padding: 16px; margin-top: 10px;" onclick="submitFinalForm()">
                        PAY & SUBMIT REQUEST
                    </button>
                </div>

                <div class="summary-box" style="flex: 1; background: #fff; padding: 24px; border-radius: 12px; border: 1px solid #e5e7eb;">
                    <h3 style="font-size: 18px; margin-bottom: 20px;">Summary</h3>
                    <div id="summary-items">
                        <div class="summary-item">
                            <span class="summary-label">Type</span>
                            <span class="summary-value" id="summary-type">-</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Quantity</span>
                            <span class="summary-value" id="summary-qty">-</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Date</span>
                            <span class="summary-value" id="summary-date">-</span>
                        </div>
                        <div class="summary-item">
                            <span class="summary-label">Address</span>
                            <span class="summary-value" id="summary-addr">-</span>
                        </div>
                        <hr style="margin: 20px 0; border: 0; border-top: 1px solid #e5e7eb;">
                        <div class="summary-item" style="font-weight: 700; font-size: 18px;">
                            <span>Total</span>
                            <span id="summary-total">₹0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script src="script2.js"></script>


</body>

</html>