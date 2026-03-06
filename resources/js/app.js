require("./bootstrap");

// Import Chart.js
import Chart from "chart.js/auto";
window.Chart = Chart;

document.addEventListener("DOMContentLoaded", () => {
    const body = document.body;
    const toggleButtons = document.querySelectorAll("[data-sidebar-toggle]");
    const overlay = document.querySelector("[data-sidebar-overlay]");
    const closeTargets = document.querySelectorAll("[data-sidebar-close]");

    if (!toggleButtons.length) {
        return;
    }

    const openClass = "is-sidebar-open";

    const closeSidebar = () => {
        body.classList.remove(openClass);
    };

    toggleButtons.forEach((button) => {
        button.addEventListener("click", () => {
            body.classList.toggle(openClass);
        });
    });

    if (overlay) {
        overlay.addEventListener("click", closeSidebar);
    }

    closeTargets.forEach((target) => {
        target.addEventListener("click", () => {
            if (window.innerWidth < 992) {
                closeSidebar();
            }
        });
    });

    window.addEventListener("resize", () => {
        if (window.innerWidth >= 992) {
            closeSidebar();
        }
    });
});
