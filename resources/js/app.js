require("./bootstrap");

// Import Chart.js
import Chart from "chart.js/auto";
window.Chart = Chart;

const DASHBOARD_CONTENT_SELECTOR = "[data-dashboard-content]";
const DASHBOARD_FILTER_FORM_SELECTOR = "[data-dashboard-filter-form]";
const DASHBOARD_CLEAR_SELECTOR = "[data-dashboard-clear]";
const DASHBOARD_META_SELECTOR = "[data-dashboard-meta]";
const TOPBAR_SUBTITLE_SELECTOR = ".lg-topbar__subtitle";

const destroyEfficiencyChart = () => {
    if (window.__dashboardEfficiencyChart) {
        window.__dashboardEfficiencyChart.destroy();
        window.__dashboardEfficiencyChart = null;
    }
};

const syncTopbarSubtitle = (scope = document) => {
    const metaElement = scope.querySelector(DASHBOARD_META_SELECTOR);
    const topbarSubtitle = document.querySelector(TOPBAR_SUBTITLE_SELECTOR);

    if (!metaElement || !topbarSubtitle) {
        return;
    }

    const subtitle = metaElement.getAttribute("data-page-subtitle");

    if (subtitle) {
        topbarSubtitle.textContent = subtitle;
    }
};

const renderEfficiencyChart = (scope = document) => {
    const canvas = scope.querySelector("#efficiencyChart");
    const trendDataElement = scope.querySelector("#efficiencyTrendData");

    if (!canvas || !trendDataElement) {
        destroyEfficiencyChart();
        return;
    }

    let trendData = [];

    try {
        trendData = JSON.parse(trendDataElement.textContent || "[]");
    } catch (error) {
        console.error("Falha ao ler dados do grafico de eficiencia.", error);
        return;
    }

    if (!Array.isArray(trendData) || !trendData.length) {
        destroyEfficiencyChart();
        return;
    }

    const lineColors = [
        { border: "#a70077", bg: "rgba(167, 0, 119, 0.1)" },
        { border: "#0066cc", bg: "rgba(0, 102, 204, 0.1)" },
        { border: "#ff6b35", bg: "rgba(255, 107, 53, 0.1)" },
        { border: "#00a86b", bg: "rgba(0, 168, 107, 0.1)" },
    ];

    const allDays = [];

    trendData.forEach((line) => {
        if (!line || !Array.isArray(line.data)) {
            return;
        }

        line.data.forEach((point) => {
            if (point && !allDays.includes(point.day)) {
                allDays.push(point.day);
            }
        });
    });

    const datasets = trendData.map((line, index) => {
        const colorIndex = index % lineColors.length;
        const color = lineColors[colorIndex];

        const dataPoints = allDays.map((day) => {
            const point = Array.isArray(line.data)
                ? line.data.find((item) => item.day === day)
                : null;

            return point ? point.efficiency : null;
        });

        return {
            label: line.name,
            data: dataPoints,
            borderColor: color.border,
            backgroundColor: color.bg,
            borderWidth: 2,
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointHoverRadius: 6,
            pointBackgroundColor: color.border,
            pointBorderColor: "#fff",
            pointBorderWidth: 2,
            pointHoverBackgroundColor: color.border,
            pointHoverBorderColor: "#fff",
        };
    });

    destroyEfficiencyChart();

    window.__dashboardEfficiencyChart = new Chart(canvas, {
        type: "line",
        data: {
            labels: allDays,
            datasets,
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: "index",
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: "top",
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12,
                            weight: "500",
                        },
                    },
                },
                tooltip: {
                    backgroundColor: "rgba(0, 0, 0, 0.8)",
                    padding: 12,
                    titleFont: {
                        size: 13,
                        weight: "600",
                    },
                    bodyFont: {
                        size: 12,
                    },
                    callbacks: {
                        label(context) {
                            const label = context.dataset.label || "";
                            const value =
                                context.parsed.y !== null
                                    ? `${context.parsed.y.toFixed(2)}%`
                                    : "N/A";

                            return `${label}: ${value}`;
                        },
                    },
                },
            },
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback(value) {
                            return `${value.toFixed(1)}%`;
                        },
                        font: {
                            size: 11,
                        },
                        color: "#666",
                    },
                    grid: {
                        color: "rgba(0, 0, 0, 0.06)",
                        drawBorder: false,
                    },
                },
                x: {
                    ticks: {
                        font: {
                            size: 11,
                        },
                        color: "#666",
                        maxRotation: 45,
                        minRotation: 0,
                    },
                    grid: {
                        display: false,
                        drawBorder: false,
                    },
                },
            },
        },
    });
};

const setupMonthInputClickable = (scope = document) => {
    const monthInput = scope.querySelector(".lg-month-input");

    if (!monthInput || monthInput.dataset.clickBound === "true") {
        return;
    }

    monthInput.dataset.clickBound = "true";

    monthInput.addEventListener("click", function (event) {
        // Só abre o picker se não clicou no ícone do calendário
        if (event.target === this) {
            try {
                this.showPicker();
            } catch (error) {
                // Fallback para navegadores que não suportam showPicker()
                this.focus();
            }
        }
    });
};

const loadDashboardContent = async (url, contentContainer) => {
    destroyEfficiencyChart();

    const response = await fetch(url, {
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-Dashboard-Partial": "content",
        },
    });

    if (!response.ok) {
        throw new Error(`Falha ao carregar dashboard: ${response.status}`);
    }

    const html = await response.text();
    contentContainer.innerHTML = html;

    window.history.replaceState({}, "", url);

    syncTopbarSubtitle(contentContainer);
    setupDashboardFilters(contentContainer);
    setupMonthInputClickable(contentContainer);
    renderEfficiencyChart(contentContainer);
};

const setupDashboardFilters = (scope = document) => {
    const contentContainer = document.querySelector(DASHBOARD_CONTENT_SELECTOR);
    const form = scope.querySelector(DASHBOARD_FILTER_FORM_SELECTOR);

    if (!contentContainer || !form || form.dataset.asyncBound === "true") {
        return;
    }

    form.dataset.asyncBound = "true";

    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        const action = form.getAttribute("action") || window.location.pathname;
        const url = new URL(action, window.location.origin);
        url.search = new URLSearchParams(new FormData(form)).toString();

        try {
            await loadDashboardContent(url.toString(), contentContainer);
        } catch (error) {
            window.location.assign(url.toString());
        }
    });

    const clearLink = form.querySelector(DASHBOARD_CLEAR_SELECTOR);

    if (!clearLink || clearLink.dataset.asyncBound === "true") {
        return;
    }

    clearLink.dataset.asyncBound = "true";

    clearLink.addEventListener("click", async (event) => {
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        event.preventDefault();

        const url = clearLink.getAttribute("href");
        if (!url) {
            return;
        }

        try {
            await loadDashboardContent(url, contentContainer);
        } catch (error) {
            window.location.assign(url);
        }
    });
};

document.addEventListener("DOMContentLoaded", () => {
    setupDashboardFilters(document);
    setupMonthInputClickable(document);
    syncTopbarSubtitle(document);
    renderEfficiencyChart(document);

    const body = document.body;
    const toggleButtons = document.querySelectorAll("[data-sidebar-toggle]");
    const overlay = document.querySelector("[data-sidebar-overlay]");
    const closeTargets = document.querySelectorAll("[data-sidebar-close]");

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
