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