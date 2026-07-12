/** === Project Hover === **/
const buttons = document.querySelectorAll(".cat-button");
const productGrid = document.querySelector(".product_grid");
const gridItems = Array.from(productGrid.querySelectorAll(".serach_card"));

buttons.forEach((button) => {
    button.addEventListener("click", function () {

        buttons.forEach((item) => {
            item.classList.remove("--active");
            item.setAttribute("aria-pressed", false);
        });
        button.classList.add("--active");
        button.setAttribute("aria-pressed", true);

        const location = button.textContent.toLowerCase();
        const filteredItems = gridItems.filter(
            (item) => item.querySelector(".card_location").textContent.toLowerCase() === location
        );

        if (location === "all") {
            gridItems.forEach((item) => item.classList.remove("--inactive"));
        } else {
            gridItems.forEach((item) => item.classList.add("--inactive"));
            filteredItems.forEach((item) => item.classList.remove("--inactive"));
        }
    });
});
