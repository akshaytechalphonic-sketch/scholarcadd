<script>
    document.addEventListener("DOMContentLoaded", function() {
        const drawers = new Set();
        const overlay = document.querySelector(".drawer-overlay");
        document.querySelectorAll(".open-drawer-btn").forEach(button => {
            button.addEventListener("click", function() {
                const targetDrawerId = this.getAttribute("data-target");
                const position = this.getAttribute("data-position") || "left";
                const targetDrawer = document.getElementById(targetDrawerId);
                if (!targetDrawer) return;
                document.querySelectorAll(`.drawer[data-position="${position}"]`).forEach(d => {
                    d.classList.add("hidden");
                    drawers.delete(d);
                });
                targetDrawer.setAttribute("data-position", position);
                targetDrawer.className =
                    "drawer fixed z-50 bg-white dark:bg-gray-800 transition-transform";
                if (position === "left") {
                    targetDrawer.classList.add("top-0", "left-0", "h-full", "w-80",
                        "-translate-x-full");
                    setTimeout(() => targetDrawer.classList.remove("-translate-x-full"), 10);
                } else if (position === "right") {
                    targetDrawer.classList.add("top-0", "right-0", "h-full", "w-80",
                        "translate-x-full");
                    setTimeout(() => targetDrawer.classList.remove("translate-x-full"), 10);
                } else if (position === "top") {
                    targetDrawer.classList.add("top-0", "left-0", "w-full", "h-60",
                        "-translate-y-full");
                    setTimeout(() => targetDrawer.classList.remove("-translate-y-full"), 10);
                } else if (position === "bottom") {
                    targetDrawer.classList.add("bottom-0", "left-0", "w-full", "h-60",
                        "translate-y-full");
                    setTimeout(() => targetDrawer.classList.remove("translate-y-full"), 10);
                }
                targetDrawer.classList.remove("hidden");
                drawers.add(targetDrawer);
                overlay.classList.remove("hidden");
            });
        });
        overlay.addEventListener("click", function() {
            drawers.forEach(drawer => drawer.classList.add("hidden"));
            drawers.clear();
            overlay.classList.add("hidden");
        });
        document.querySelectorAll(".close-drawer-btn").forEach(button => {
            button.addEventListener("click", function() {
                const drawer = this.closest(".drawer");
                drawer.classList.add("hidden");
                drawers.delete(drawer);
                if (drawers.size === 0) overlay.classList.add("hidden");
            });
        });
    });
</script>
