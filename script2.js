// Calculate amount
function calculateAmount() {

    if (!validateForm()) return;

    const name = document.getElementById('name').value;
    const type = document.getElementById('type').value;
    const weight = parseFloat(document.getElementById('weight').value);

    const pricing = {
        'Plastic': 15,
        'Organic': 5,
        'Metal': 20,
        'Glass': 12
    };

    const rate = pricing[type] || 10;
    const amount = (weight * rate).toFixed(2);

    // 👉 amount field me value set (IMPORTANT for PHP)
    document.getElementById('amount').value = amount;

    // 👉 UI result (same tera style)
    document.getElementById('result').innerHTML = 
        `Hello ${name}, for ${weight}kg of ${type} waste:<br>
        Rate: ₹${rate}/kg<br>
        <strong>Total Amount: ₹${amount}</strong>`;

    // Show submit button
    document.getElementById('payBtn').style.display = 'block';
}


// ❌ REMOVED localStorage (DB use ho raha hai ab)
// ❌ REMOVED makePayment modal logic (optional banaya niche)


// ✅ OPTIONAL: agar tu modal rakhna chahta hai (UI ke liye)
function makePayment() {

    if (!validateForm()) return;

    const name = document.getElementById('name').value;
    const type = document.getElementById('type').value;
    const weight = document.getElementById('weight').value;
    const amount = document.getElementById('amount').value;

    // ❗ Amount check (important)
    if (!amount || amount == 0) {
        alert("Please calculate amount first!");
        return;
    }

    const receipt = `
        *** WASTE MANAGEMENT RECEIPT ***
        Name: ${name}
        Type: ${type}
        Weight: ${weight}kg
        Total Amount: ₹${amount}
        Status: Submitted Successfully
    `;

    document.getElementById('paymentModalBody').innerHTML = receipt.replace(/\n/g, '<br>');
    document.getElementById('paymentModal').style.display = 'block';

    // 🔥 MAIN FIX: form submit
    setTimeout(() => {
        document.getElementById('wasteForm').submit();
    }, 1000);
}


// Close modal
function closeModal() {
    const modal = document.getElementById('paymentModal');
    if (modal) modal.style.display = 'none';
}


// Form validation
function validateForm() {
    const name = document.getElementById('name').value.trim();
    const weight = document.getElementById('weight').value;

    if (!name.match(/^[a-zA-Z\s]+$/)) {
        showError('Name should contain only letters');
        return false;
    }

    if (weight <= 0 || weight > 1000) {
        showError('Weight must be between 0.1 and 1000kg');
        return false;
    }

    return true;
}


// Show error
function showError(msg) {
    const result = document.getElementById('result');
    result.innerHTML = `<span style="color: #ff6b6b;">❌ ${msg}</span>`;
    result.style.background = 'linear-gradient(45deg, #ff6b6b, #ee5a24)';

    setTimeout(() => {
        result.innerHTML = '';
        result.style.background = '';
    }, 3000);
}


// Reset form
function resetForm() {
    document.getElementById('wasteForm').reset();
    document.getElementById('result').innerHTML = '';
    document.getElementById('amount').value = '';
    document.getElementById('payBtn').style.display = 'none';
}


// Load Font Awesome (icons ke liye)
if (!document.querySelector('link[href*="font-awesome"]')) {
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css';
    document.head.appendChild(link);
}


// Close modal on outside click
window.onclick = function(event) {
    const modal = document.getElementById('paymentModal');
    if (modal && event.target == modal) {
        closeModal();
    }
}