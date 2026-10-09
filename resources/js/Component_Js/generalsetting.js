document.addEventListener("DOMContentLoaded", () => {
    const sidebarLinks = document.querySelectorAll(".sidebar-link");
    const sections = document.querySelectorAll(".scroll-section");
    const mainContent = document.querySelector("main");
    sidebarLinks[0].classList.add(
        "bg-secondary",
        "border-l-2",
        "border-primary",
        "dark:bg-red-500"
    );
    sidebarLinks.forEach((link) => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            const targetId = link.getAttribute("data-target");
            const targetSection = document.getElementById(targetId);
            targetSection.scrollIntoView({
                behavior: "smooth",
                block: "start",
            });
            sidebarLinks.forEach((link) =>
                link.classList.remove(
                    "bg-secondary",
                    "border-l-2",
                    "border-primary",
                    "dark:bg-red-500"
                )
            );
            link.classList.add(
                "bg-secondary",
                "dark:bg-red-500",
                "border-l-2",
                "border-primary"
            );
        });
    });

    mainContent.addEventListener("scroll", () => {
        let currentSectionId = "";
        let maxVisibility = 0;
        sections.forEach((section) => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            const sectionVisible = Math.max(
                0,
                Math.min(
                    sectionTop + sectionHeight,
                    mainContent.scrollTop + mainContent.clientHeight
                ) - Math.max(sectionTop, mainContent.scrollTop)
            );
            const visibilityPercentage = (sectionVisible / sectionHeight) * 100;
            if (visibilityPercentage > maxVisibility) {
                maxVisibility = visibilityPercentage;
                currentSectionId = section.getAttribute("id");
            }
        });
        if (maxVisibility > 50) {
            sidebarLinks.forEach((link) => {
                link.classList.remove(
                    "bg-secondary",
                    "border-l-2",
                    "border-primary",
                    "dark:bg-red-500"
                );
                if (link.getAttribute("data-target") === currentSectionId) {
                    link.classList.add(
                        "bg-secondary",
                        "border-l-2",
                        "border-primary",
                        "dark:bg-red-500"
                    );
                }
            });
        }
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const settingsidebar = document.getElementById("settingsidebar");
    const mainContent = document.getElementById("mainContent");
    const Settingsidebartoggler = document.getElementById(
        "Settingsidebartoggler"
    );
    const sidebarTogglerIcon = document.getElementById("sidebar-toggler-icon");
    let sidebarVisible = window.innerWidth >= 1024;
    const handleResize = () => {
        if (window.innerWidth >= 1024) {
            sidebarVisible = true;
            settingsidebar.classList.remove("hidden");
            mainContent.classList.remove("full-width");
            sidebarTogglerIcon.classList.remove("ri-arrow-right-s-line");
            sidebarTogglerIcon.classList.add("ri-arrow-left-s-line");
        } else {
            sidebarVisible = false;
            settingsidebar.classList.add("hidden");
            mainContent.classList.add("full-width");
            sidebarTogglerIcon.classList.remove("ri-arrow-left-s-line");
            sidebarTogglerIcon.classList.add("ri-arrow-right-s-line");
        }
    };
    handleResize();
    window.addEventListener("resize", handleResize);
    Settingsidebartoggler.addEventListener("click", () => {
        sidebarVisible = !sidebarVisible;
        if (sidebarVisible) {
            settingsidebar.classList.remove("hidden");
            mainContent.classList.remove("full-width");
            sidebarTogglerIcon.classList.remove("ri-arrow-right-s-line");
            sidebarTogglerIcon.classList.add("ri-arrow-left-s-line");
        } else {
            settingsidebar.classList.add("hidden");
            mainContent.classList.add("full-width");
            sidebarTogglerIcon.classList.remove("ri-arrow-left-s-line");
            sidebarTogglerIcon.classList.add("ri-arrow-right-s-line");
        }
    });
});
