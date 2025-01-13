console.log("ffffffffffffffff");
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