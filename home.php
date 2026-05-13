<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: auth.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Management System - Smart Recycling</title>
    <link rel="stylesheet" href="style2.css">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌱</text></svg>">
</head>

<body>

<header style="position: relative;">
    <h1>🌱 Smart Waste Management System</h1>
    <p>Recycle Today for a Sustainable Tomorrow</p>

    <a href="logout.php" style="
        position: absolute;
        top: 20px;
        right: 25px;
        background: rgba(0,0,0,0.4);
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;">
        Logout
    </a>
</header>

<section class="video-section">
    <h2>Intelligent Waste Processing</h2>
    <div class="video-placeholder">
        <i class="fas fa-recycle" style="font-size: 4rem; color: var(--primary-green);"></i>
        <div>📹 Recycling Process </div>
    </div>
</section>

<section class="form-section">
    <h2>Submit Your Waste for Processing</h2>
    <p style="text-align:center; color:#666; margin-bottom:2rem;">
        Enter details to get instant quote
    </p>

    <!-- ✅ FORM FIX -->
    <form id="wasteForm" action="submit_waste.php" method="POST">

        <div class="input-group" id="name-group">
            <label>Full Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter your name" required>
        </div>

        <div class="input-group" id="type-group">
            <label>Waste Type:</label>
            <select id="type" name="waste_type">
                <option value="Plastic">🛢️ Plastic (₹15/kg)</option>
                <option value="Organic">🌿 Organic (₹5/kg)</option>
                <option value="Metal">🔩 Metal (₹20/kg)</option>
                <option value="Glass">🫙 Glass (₹12/kg)</option>
            </select>
        </div>

        <div class="input-group" id="weight-group">
            <label>Weight (kg):</label>
            <input type="number" id="weight" name="weight" min="0.1" max="1000" step="0.1" required>
        </div>

        <!-- ✅ NEW: AMOUNT FIELD -->
        <div class="input-group">
            <label>Total Amount:</label>
            <input type="text" id="amount" name="amount" readonly>
        </div>

        <div class="input-group" id="location-group">
            <label>Pickup Location (Optional):</label>
            <input type="text" id="location" name="location" placeholder="Your address">
        </div>

        <div style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
            <button type="button" onclick="calculateAmount()">Calculate Quote</button>
            <button type="button" onclick="resetForm()" style="background:#6c757d;">Reset</button>
        </div>

        <button id="payBtn" onclick="makePayment()" style="display:none; width:100%;">
            🚀 Proceed to Payment
        </button>
    </form>

    <h3 id="result"></h3>

    <!-- ✅ SAME BUTTON (WORKING NOW) -->
</section>

<!-- Payment Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 style="color: var(--primary-green);">✅ Payment Successful!</h2>
        <div id="paymentModalBody"></div>
        <div style="margin-top:2rem;">
            <button onclick="closeModal()" style="width:48%; margin-right:4%;">Close</button>
            <button onclick="resetForm();closeModal();" style="width:48%;">New Submission</button>
        </div>
    </div>
</div>

<footer>
    <p>2026 Smart Waste Management Project | Powered by PHP & MySQL</p>
    <p style="font-size:0.9rem; opacity:0.8;">
        All submissions are securely stored in database
    </p>
</footer>

<script src="script2.js"></script>
</body>

</html>