<script>
function validateCardHolderName() {
    const cardholderName = document.getElementById("cardholder-name");
    cardholderName.value = cardholderName.value.replace(/[^A-Za-z\s]/g, ''); 
}
function validateCardNumber() {
    const cardNumber = document.getElementById("card-number");
    const value = cardNumber.value.replace(/\D/g, '');
    cardNumber.value = value.slice(0, 16); 
}
function validateCVV() {
    const cvv = document.getElementById("cvv");
    const value = cvv.value.replace(/\D/g, ''); 
    cvv.value = value.slice(0, 3); 
}
function validateExpirationDate() {
    const expirationMonth = document.getElementById("expiration-month");
    const expirationYear = document.getElementById("expiration-year");
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth() + 1;  
    const currentYear = currentDate.getFullYear() % 100; 
    let month = parseInt(expirationMonth.value);
    if (month < 1 || month > 12) {
        alert("Month must be between 01 and 12.");
        expirationMonth.setCustomValidity("Invalid month.");
    } else {
        expirationMonth.setCustomValidity(""); 
    }
    let year = parseInt(expirationYear.value);
    if (year < currentYear) {
        alert("Year must not be in the past.");
        expirationYear.setCustomValidity("Year cannot be in the past.");
    } else if (year === currentYear && month < currentMonth) {
        alert("Expiration date cannot be in the past month.");
        expirationMonth.setCustomValidity("Month cannot be in the past.");
    } else {
        expirationYear.setCustomValidity("");
        expirationMonth.setCustomValidity("");
    }
}
document.getElementById('expiration-month').addEventListener('input', function(event) {
    let value = event.target.value;
    value = value.replace(/[^0-9]/g, '');
    if (value.length > 2) {
        value = value.slice(0, 2); 
    }
    // if (parseInt(value) > 12) {
    //     value = '12';
    // }
    event.target.value = value;
});

document.getElementById("payment-form").addEventListener("submit", function(event) {
    const cardholderName = document.getElementById("cardholder-name").value;
    const cardNumber = document.getElementById("card-number").value;
    const cvv = document.getElementById("cvv").value;
    if (!/^[A-Za-z\s]+$/.test(cardholderName)) {
        alert("Card Holder Name must contain only letters and spaces.");
        event.preventDefault(); 
        return;
    }
    if (cardNumber.length !== 16 || isNaN(cardNumber)) {
        alert("Card Number must contain exactly 16 digits.");
        event.preventDefault(); 
        return;
    }
    if (cvv.length !== 3 || isNaN(cvv)) {
        alert("CVV must contain exactly 3 digits.");
        event.preventDefault(); 
        return;
    }
});

</script>
