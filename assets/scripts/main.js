// Mobile sidebar menu
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


function toggleAnswer(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector("i");

    if (answer.classList.contains("hidden")) {
        answer.classList.remove("hidden");
        answer.style.maxHeight = answer.scrollHeight + "px";
    } else {
        answer.style.maxHeight = "0";
        setTimeout(() => {
            answer.classList.add("hidden");
        }, 300);
    }

    icon.classList.toggle("ri-arrow-down-s-line");
    icon.classList.toggle("ri-arrow-up-s-line");
}

document.querySelectorAll(".role-option").forEach(option => {
    option.addEventListener("click", function () {
        document.querySelectorAll(".role-option").forEach(el => el.classList.remove("selected"));
        this.classList.add("selected");
        const input = this.querySelector("input");
        input.checked = true;
    });
});


