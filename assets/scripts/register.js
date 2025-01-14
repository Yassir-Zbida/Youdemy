//  header sidebar
const mobileMenuBtn = document.getElementById('mobile-menu-btn');
const sidebarMenu = document.getElementById('sidebar-menu');
const closeSidebar = document.getElementById('close-sidebar');

mobileMenuBtn.addEventListener('click', () => {
    sidebarMenu.classList.remove('hidden');
});

closeSidebar.addEventListener('click', () => {
    sidebarMenu.classList.add('hidden');
});

sidebarMenu.addEventListener('click', (e) => {
    if (e.target === sidebarMenu) {
        sidebarMenu.classList.add('hidden');
    }
});


// radio buttons
document.querySelectorAll(".role-option").forEach(option => {
    option.addEventListener("click", function () {
        document.querySelectorAll(".role-option").forEach(el => el.classList.remove("selected"));
                this.classList.add("selected");
                this.querySelector("input").checked = true;
    });
});


// register form validation 
// Register form validation
document.getElementById('registerForm').addEventListener('submit', function (e) {
    const username = document.querySelector('input[name="username"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const role = document.querySelector('input[name="role"]:checked');
    
    // Clear previous error messages
    document.querySelector('.errorsContainer').innerHTML = '';

    let isValid = true;

    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-red-500 text-sm mt-4 text-center';
        errorDiv.textContent = message;
        document.querySelector('.errorsContainer').appendChild(errorDiv);
        isValid = false;
    }

    // Check for errors
    if (!role) {
        e.preventDefault();
        showError('Please select a role');
    }

    if (!password) {
        e.preventDefault();
        showError('Password is required');
    }

    if (password !== confirmPassword) {
        e.preventDefault();
        showError('Passwords do not match');
    }

    if (!username || !email) {
        e.preventDefault();
        showError('Please fill in all fields');
    }
    
    if (!isValid) {
        e.preventDefault(); 
    }
});

