document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.getElementById("custom-sidebar");
    const content = document.getElementById("content");
    if (!sidebar || !content) return; 
    const tabLinks = sidebar.querySelectorAll(".tab-link");
    const sections = content.querySelectorAll("section");

    const updateActiveLink = (activeLink) => {
        tabLinks.forEach((link) => link.classList.remove("bg-gray-200"));
        activeLink.classList.add("bg-gray-200");
    };
    if (tabLinks.length > 0) {
        const firstTab = tabLinks[0];
        firstTab.classList.add("bg-gray-200");

        const firstSection = document.querySelector(firstTab.getAttribute("href"));
        if (firstSection) {
            firstSection.scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }
    }
    tabLinks.forEach((link) => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            const targetId = link.getAttribute("href").substring(1);
            const targetSection = document.getElementById(targetId);

            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }

            updateActiveLink(link);
        });
    });
    content.addEventListener("scroll", () => {
        let scrollPosition = content.scrollTop + window.innerHeight / 2;

        sections.forEach((section) => {
            if (
                section.offsetTop <= scrollPosition &&
                section.offsetTop + section.offsetHeight > scrollPosition
            ) {
                const activeLink = sidebar.querySelector(
                    `.tab-link[href="#${section.id}"]`
                );
                if (activeLink) {
                    updateActiveLink(activeLink);
                    const sidebarItem = activeLink.parentElement;
                    sidebar.scrollTo({
                        top: sidebarItem.offsetTop - sidebar.clientHeight / 2,
                        behavior: "smooth",
                    });
                }
            }
        });
    });
});