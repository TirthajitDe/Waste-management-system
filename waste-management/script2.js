function showView(viewName) {
    document.getElementById('dashboard-view').style.display = 'none';
    document.getElementById('form-view').style.display = 'none';
    document.getElementById('payment-view').style.display = 'none';

    if (viewName === 'form') {
        document.getElementById('form-view').style.display = 'block';
        setTimeout(() => {
            initMap();
            if (map) map.invalidateSize();
        }, 300);
    }
    else if (viewName === 'payment') {
        if (validateDetails()) {
            updateSummary();
            document.getElementById('payment-view').style.display = 'block';
        } else {
            document.getElementById('form-view').style.display = 'block';
        }
    }
    else {
        document.getElementById('dashboard-view').style.display = 'block';
    }
}

let map, marker;
function initMap() {
    if (!map) {
        map = L.map('map').setView([23.8315, 91.2868], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        map.on('click', function (e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            if (marker) map.removeLayer(marker);
            marker = L.marker([lat, lng]).addTo(map);
            document.getElementById('latlng').value = lat + "," + lng;
            const btn = document.getElementById('submitBtn');
            btn.disabled = false;
            btn.style.background = "#111827";
            btn.style.cursor = "pointer";
        });
    }
}

function updateSummary() {
    const type = document.querySelector('input[name="waste_type"]:checked').value;
    const qtyLabel = document.querySelector('input[name="quantity"]:checked').nextElementSibling.innerText;
    const date = document.querySelector('input[name="pickup_date"]').value;
    const addr = document.querySelector('input[name="location"]').value;
    const pricing = {
        'Household': 199,
        'Recyclable': 299,
        'E-Waste': 499,
        'Garden': 150
    };
    const total = pricing[type] || 199;
    document.getElementById('summary-type').innerText = type;
    document.getElementById('summary-qty').innerText = qtyLabel;
    document.getElementById('summary-date').innerText = date;
    document.getElementById('summary-addr').innerText = addr;
    document.getElementById('summary-total').innerText = "₹" + total.toFixed(2);
}

function validateDetails() {
    const coords = document.getElementById('latlng').value;
    const addr = document.querySelector('input[name="location"]').value;
    const date = document.querySelector('input[name="pickup_date"]').value;

    if (!coords) {
        alert("Please pin your location on the map!");
        return false;
    }
    if (!addr.trim() || !date) {
        alert("Please enter address and preferred date!");
        return false;
    }
    return true;
}

function submitFinalForm() {
    const payMethod = document.querySelector('input[name="pay_method"]:checked').value;
    alert("Payment confirmed via " + payMethod + "! Your request has been scheduled.");

    document.getElementById('wasteForm').submit();
}

document.addEventListener('change', function (e) {
    if (e.target.name === 'pay_method') {
        const cardSection = document.getElementById('card-details');
        if (cardSection) {
            cardSection.style.display = (e.target.value === 'Card') ? 'block' : 'none';
        }
    }
});
