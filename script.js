const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');

// Toggle between login and register forms
registerBtn.addEventListener('click', () => {
    container.classList.add('active');
});

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
});

// Show error messages near the forms
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        // You can add client-side validation here if needed
        // The PHP backend will handle the main validation
    });
});