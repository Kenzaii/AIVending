async function handleLogin(event) {
    event.preventDefault();
    
    const countryCode = document.getElementById('country_code').value;
    const phone = document.getElementById('phone').value;
    
    // Basic phone number validation
    if (!phone.match(/^\d{8}$/)) {
        alert('Please enter a valid 8-digit phone number');
        return;
    }

    const fullPhone = countryCode + phone;

    try {
        // You would typically make an API call to your backend here
        // For demo purposes, we'll simulate the OTP generation
        const otp = Math.floor(100000 + Math.random() * 900000);
        
        // Store the phone number in localStorage (or you could use sessionStorage)
        localStorage.setItem('userPhone', fullPhone);
        
        // Simulate sending OTP (in real app, this would be done server-side)
        console.log('OTP for testing:', otp);
        localStorage.setItem('currentOTP', otp); // Never store OTP in frontend in production!
        
        // Redirect to OTP verification page
        window.location.href = 'verify.html';
        
    } catch (error) {
        console.error('Login error:', error);
        alert('An error occurred during login. Please try again.');
    }
} 