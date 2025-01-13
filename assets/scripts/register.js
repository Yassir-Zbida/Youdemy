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


// Script to add selected class to active radio buttons
document.querySelectorAll(".role-option").forEach(option => {
    option.addEventListener("click", function () {
        // Remove the 'selected' class from all labels
        document.querySelectorAll(".role-option").forEach(el => el.classList.remove("selected"));
        
        // Add the 'selected' class to the clicked label
        this.classList.add("selected");
        
        // Optionally add a class to the input element to visually indicate selection (if you want)
        this.querySelector("input").checked = true;
    });
});
