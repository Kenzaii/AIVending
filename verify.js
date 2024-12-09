async function verifyOTP(event) {
    event.preventDefault();
    
    const enteredOTP = document.getElementById('otp').value;
    const storedOTP = localStorage.getItem('currentOTP');
    
    if (enteredOTP === storedOTP) {
        // Success! In a real app, you would validate this server-side
        alert('Successfully verified!');
        
        // Clear sensitive data
        localStorage.removeItem('currentOTP');
        
        // Redirect to dashboard or home page
        window.location.href = 'dashboard.html';
    } else {
        alert('Invalid OTP. Please try again.');
    }
} 