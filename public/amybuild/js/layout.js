// Check if the user has a saved theme preference
if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark");
} else {
    document.body.classList.remove("dark");
}
const defaultColors = {
    primary: "#1F3F49",
    secondary: "#d8f6ff",
    neutral: "#F9FAFB",
};
// Predefined colors for primary, secondary, and neutral
const predefinedColors = {
    primary: ["#1F3F49", "#3D4127 ", "#0F0E47", "#38000A ", "#660F09"],
    secondary: ["#d8f6ff", "#D4DE95 ", "#BFFFED ", "#FFA896 ", "#DBD4FF "],
    neutral: ["#F9FAFB", "#E5E7EB", "#D1D5DB", "#F3F4F6", "#FFFFFF"],
};
// Function to update CSS variables in the root element
function updateThemeColor(variable, value) {
    document.documentElement.style.setProperty(variable, value);
}
// Function to load saved colors and apply them globally
function loadSavedColors() {
    const savedPrimary =
        localStorage.getItem("color-primary") || defaultColors.primary;
    const savedSecondary =
        localStorage.getItem("color-secondary") || defaultColors.secondary;
    const savedNeutral =
        localStorage.getItem("color-neutral") || defaultColors.neutral;

    // Apply saved colors globally
    updateThemeColor("--color-primary", savedPrimary);
    updateThemeColor("--color-secondary", savedSecondary);
    updateThemeColor("--color-neutral", savedNeutral);

    // Update active color boxes
    updateActiveColor("primary", savedPrimary);
    updateActiveColor("secondary", savedSecondary);
    updateActiveColor("neutral", savedNeutral);
}

// Function to save colors globally and persist them in localStorage
function saveColors() {
    const primary =
        localStorage.getItem("color-primary") || defaultColors.primary;
    const secondary =
        localStorage.getItem("color-secondary") || defaultColors.secondary;
    const neutral =
        localStorage.getItem("color-neutral") || defaultColors.neutral;

    // Show loading state
    const saveButton = document.getElementById("set-theme");
    saveButton.disabled = true;
    saveButton.textContent = "Saving...";

    setTimeout(() => {
        // Save colors to localStorage
        localStorage.setItem("color-primary", primary);
        localStorage.setItem("color-secondary", secondary);
        localStorage.setItem("color-neutral", neutral);

        // Update theme colors globally
        updateThemeColor("--color-primary", primary);
        updateThemeColor("--color-secondary", secondary);
        updateThemeColor("--color-neutral", neutral);

        // Show success message
        saveButton.textContent = "Saved!";
        setTimeout(() => {
            saveButton.textContent = "Save Theme";
            saveButton.disabled = false;
        }, 1000);
    }, 1000);
}

// Function to reset colors to defaults globally
function resetColors() {
    localStorage.removeItem("color-primary");
    localStorage.removeItem("color-secondary");
    localStorage.removeItem("color-neutral");
    loadSavedColors();
}

// Function to update the active color box and save the color
function updateActiveColor(type, color) {
    // Add a ring to the active color box
    document.querySelectorAll(`.${type}-color-option`).forEach((colorBox) => {
        if (colorBox.dataset.color === color) {
            colorBox.classList.add("ring-4", "ring-offset-2", `ring-${type}`);
        } else {
            colorBox.classList.remove(
                "ring-4",
                "ring-offset-2",
                `ring-${type}`
            );
        }
    });

    // Save the color to localStorage and apply it globally
    localStorage.setItem(`color-${type}`, color);
    updateThemeColor(`--color-${type}`, color);
}

// Attach click event listeners to color options
["primary", "secondary", "neutral"].forEach((type) => {
    document.querySelectorAll(`.${type}-color-option`).forEach((colorBox) => {
        colorBox.addEventListener("click", (e) => {
            const selectedColor = e.target.dataset.color;
            updateActiveColor(type, selectedColor);
        });
    });
});

// Initialize saved colors on page load
loadSavedColors();

document.getElementById("set-theme").addEventListener("click", saveColors);
document.getElementById("reset-theme").addEventListener("click", resetColors);

// ------fullscreen aplication js-------
const fullscreenToggleButton = document.getElementById("fullscreen-toggle");
fullscreenToggleButton.addEventListener("click", () => {
    if (!document.fullscreenElement) {
        if (document.documentElement.requestFullscreen) {
            document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
            document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
            document.documentElement.webkitRequestFullscreen();
        } else if (document.documentElement.msRequestFullscreen) {
            document.documentElement.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
});
// ----Drawar Custome Js------
document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("click", function (event) {
        const drawers = document.querySelectorAll(".drawer");
        const openButtons = document.querySelectorAll(".open-drawer-btn");
        let clickedDrawer = null;
        let clickedButton = null;
        drawers.forEach((drawer) => {
            if (drawer.contains(event.target)) {
                clickedDrawer = drawer;
            }
        });
        openButtons.forEach((button) => {
            if (button.contains(event.target)) {
                clickedButton = button;
            }
        });
        if (clickedButton) {
            const targetDrawerId = clickedButton.getAttribute("data-drawer");
            const targetDrawer = document.getElementById(targetDrawerId);
            if (targetDrawer) {
                drawers.forEach((drawer) => {
                    if (drawer !== targetDrawer) {
                        drawer.classList.add("-translate-x-full");
                    }
                });
                targetDrawer.classList.remove("-translate-x-full");
            }
            return;
        }
        if (!clickedDrawer) {
            drawers.forEach((drawer) => {
                drawer.classList.add("-translate-x-full");
            });
        }
    });
    document.querySelectorAll(".close-drawer-btn").forEach((button) => {
        button.addEventListener("click", function () {
            this.closest(".drawer").classList.add("-translate-x-full");
        });
    });
});

// -----sidebar toggler-----
const sidebar = document.getElementById("sidebar");
const sidebarToggleDesktop = document.getElementById("sidebar-toggle-desktop");
const sidebarToggleMobile = document.getElementById("sidebar-toggle-mobile");
const expandedLogo = document.querySelector(".expanded-logo");
const collapsedLogo = document.querySelector(".collapsed-logo");
const dropdownItems = document.querySelectorAll("#sidebar ul li a");
const dropdownLists = document.querySelectorAll(".dropdown-list");
let isCollapsed = false;
// Function to update the logo based on collapsed state
function updateLogo() {
    if (isCollapsed) {
        expandedLogo.classList.add("hidden");
        collapsedLogo.classList.remove("hidden");
    } else {
        expandedLogo.classList.remove("hidden");
        collapsedLogo.classList.add("hidden");
    }
}
// Handle sidebar collapse/expand for desktop
if (sidebarToggleDesktop) {
    sidebarToggleDesktop.addEventListener("click", () => {
        isCollapsed = !isCollapsed;
        sidebar.classList.toggle("collapsed");
        toggleDropdownListClass(isCollapsed);
        updateLogo();
    });
}
// Handle mouseenter and mouseleave for hover
sidebar.addEventListener("mouseenter", () => {
    if (isCollapsed) {
        dropdownLists.forEach((dropdown) => {
            dropdown.classList.remove("hidden");
        });
    }
});

sidebar.addEventListener("mouseleave", () => {
    if (isCollapsed) {
        dropdownLists.forEach((dropdown) => {
            dropdown.classList.add("hidden");
        });
    }
});
// Handle sidebar visibility for mobile
// if (sidebarToggleMobile) {
//     sidebarToggleMobile.addEventListener("click", () => {
//         sidebar.classList.toggle("hidden");
//     });
// }
const overlay = document.getElementById("sidebar-overlay");
// Function to open sidebar and show overlay
function openSidebar() {
    sidebar.classList.remove("hidden");
    overlay.classList.remove("hidden");
}
// Function to close sidebar and hide overlay
function closeSidebar() {
    sidebar.classList.add("hidden");
    overlay.classList.add("hidden");
}
// Toggle sidebar on mobile button click
if (sidebarToggleMobile) {
    sidebarToggleMobile.addEventListener("click", () => {
        if (sidebar.classList.contains("hidden")) {
            openSidebar();
        } else {
            closeSidebar();
        }
    });
}
// Close sidebar when clicking the overlay
if (overlay) {
    overlay.addEventListener("click", closeSidebar);
}
// Hover behavior for collapsed sidebar
sidebar.addEventListener("mouseenter", () => {
    if (isCollapsed) {
        sidebar.classList.remove("collapsed");
        expandedLogo.classList.remove("hidden");
        collapsedLogo.classList.add("hidden");
    }
});
sidebar.addEventListener("mouseleave", () => {
    if (isCollapsed) {
        sidebar.classList.add("collapsed");
        updateLogo();
    }
});
function toggleDropdownListClass(isCollapsed) {
    dropdownLists.forEach((dropdown) => {
        if (isCollapsed) {
            dropdown.classList.add("hidden");
        } else {
            dropdown.classList.remove("hidden");
        }
    });
}

// Ensure active dropdown labels are hidden in collapsed mode
function updateDropdownItems() {
    dropdownItems.forEach((item) => {
        const label = item.querySelector("span");
        if (isCollapsed) {
            if (label) label.classList.add("hidden");
        } else {
            if (label) label.classList.remove("hidden");
        }
    });
}
// Automatically collapse sidebar on small screens
function handleSidebarOnResize() {
    if (window.innerWidth < 768) {
        sidebar.classList.add("hidden");
        sidebar.classList.remove("collapsed");
        isCollapsed = false;
        updateLogo();
    } else {
        sidebar.classList.remove("hidden");
    }
}
handleSidebarOnResize();
window.addEventListener("resize", handleSidebarOnResize);
updateLogo();
updateDropdownItems();
toggleDropdownListClass(isCollapsed);

//Theme change Js
function saveTheme(theme) {
    localStorage.setItem("theme", theme);
    location.reload();
}
window.addEventListener("DOMContentLoaded", () => {
    const savedTheme = localStorage.getItem("theme");
    const lightRadio = document.getElementById("light-radio");
    const darkRadio = document.getElementById("dark-radio");
    if (savedTheme === "dark") {
        document.documentElement.classList.add("dark");
        darkRadio.checked = true;
    } else {
        document.documentElement.classList.remove("dark");
        lightRadio.checked = true;
    }
});
document.getElementById("light-radio").addEventListener("change", () => {
    saveTheme("light");
});
document.getElementById("dark-radio").addEventListener("change", () => {
    saveTheme("dark");
});

// topbar toggler-----
const topbar = document.getElementById("Topbar");
const toggleButton = document.getElementById("toggleButton");
const toggleIcon = document.getElementById("toggleIcon");
toggleButton.addEventListener("click", () => {
    if (topbar.classList.contains("opacity-100")) {
        topbar.classList.remove("opacity-100", "translate-y-0");
        topbar.classList.add("opacity-0", "-translate-y-full");
        setTimeout(() => {
            topbar.classList.add("hidden");
        }, 500);
        toggleIcon.classList.remove("ri-arrow-up-s-line");
        toggleIcon.classList.add("ri-arrow-down-s-line");
    } else {
        topbar.classList.remove("hidden", "opacity-0", "-translate-y-full");
        setTimeout(() => {
            topbar.classList.add("opacity-100", "translate-y-0");
        }, 10);
        toggleIcon.classList.remove("ri-arrow-down-s-line");
        toggleIcon.classList.add("ri-arrow-up-s-line");
    }
});

// footer toggler-----
const footer = document.getElementById("footer");
const toggleButtonfooter = document.getElementById("toggleButtonfooter");
const toggleIconfooter = document.getElementById("toggleIconfooter");
toggleButtonfooter.addEventListener("click", () => {
    if (footer.classList.contains("opacity-100")) {
        footer.classList.remove("opacity-100", "translate-y-0");
        footer.classList.add("opacity-0", "translate-y-full");
        setTimeout(() => {
            footer.classList.add("hidden");
        }, 500);
        toggleIconfooter.classList.remove("ri-arrow-down-s-line");
        toggleIconfooter.classList.add("ri-arrow-up-s-line");
    } else {
        footer.classList.remove("hidden", "opacity-0", "translate-y-full");
        setTimeout(() => {
            footer.classList.add("opacity-100", "translate-y-0");
        }, 10);
        toggleIconfooter.classList.remove("ri-arrow-up-s-line");
        toggleIconfooter.classList.add("ri-arrow-down-s-line");
    }
});

// RTL Toggle Logic

const savedDirection = localStorage.getItem("direction");
if (savedDirection === "rtl") {
    document.documentElement.setAttribute("dir", "rtl");
    const selectElement = document.getElementById("language");
    selectElement.value = "AR";
} else {
    document.documentElement.setAttribute("dir", "ltr");
    const selectElement = document.getElementById("language");
    selectElement.value = "EN";
}
// selectElement.dispatchEvent(new Event("change"));

// onedropdown open at one time
document.querySelectorAll('[data-toggle="dropdown"]').forEach((button) => {
    button.addEventListener("click", function () {
        const dropdown = this.nextElementSibling;
        document.querySelectorAll("[data-dropdown]").forEach((el) => {
            if (el !== dropdown) {
                el.classList.add("hidden");
            }
        });
        dropdown.classList.toggle("hidden");
    });
});

// modal js
// Open Modal functionality for multiple modals
document.querySelectorAll(".openModalBtn").forEach((button) => {
    button.addEventListener("click", function () {
        const targetModalId = this.getAttribute("data-target");
        const modal = document.getElementById(targetModalId);
        modal.classList.remove("hidden");
        setTimeout(() => {
            const modalContent = modal.querySelector(".relative");
            modalContent.classList.remove("opacity-0", "-translate-y-10");
            modalContent.classList.add("opacity-100", "translate-y-0");
        }, 10);
    });
});

// Close Modal functionality for multiple modals
document.querySelectorAll(".closeModalBtn").forEach((button) => {
    button.addEventListener("click", function () {
        const modal = this.closest(".modal");
        modal
            .querySelector(".relative")
            .classList.add("opacity-0", "-translate-y-10");
        setTimeout(() => {
            modal.classList.add("hidden");
        }, 300);
    });
});

