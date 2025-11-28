// DOM Ready
document.addEventListener("DOMContentLoaded", function () {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById("mobile-menu-button");
    const mobileMenu = document.getElementById("mobile-menu");

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener("click", () => {
            mobileMenu.classList.toggle("hidden");
        });
    }

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        });
    });

    // Dark mode toggle
    const darkModeToggle = document.getElementById("dark-mode-toggle");
    if (darkModeToggle) {
        darkModeToggle.addEventListener("click", () => {
            document.documentElement.classList.toggle("dark");
            localStorage.setItem(
                "darkMode",
                document.documentElement.classList.contains("dark")
            );
        });
    }

    // Check for saved user preference for dark mode
    const darkModePref = localStorage.getItem("darkMode");
    if (darkModePref === "true") {
        document.documentElement.classList.add("dark");
    } else {
        document.documentElement.classList.remove("dark");
    }

    // Initialize tooltips
    const tooltipTriggers = document.querySelectorAll("[data-tooltip]");
    tooltipTriggers.forEach((trigger) => {
        let tooltip = document.createElement("div");
        tooltip.className =
            "tooltip hidden bg-gray-900 text-white text-xs rounded py-1 px-2 absolute z-50 whitespace-nowrap";
        tooltip.textContent = trigger.getAttribute("data-tooltip");
        document.body.appendChild(tooltip);

        const updateTooltipPosition = (e) => {
            const rect = trigger.getBoundingClientRect();
            tooltip.style.top = `${rect.bottom + window.scrollY + 5}px`;
            tooltip.style.left = `${
                rect.left + (rect.width - tooltip.offsetWidth) / 2
            }px`;
        };

        trigger.addEventListener("mouseenter", (e) => {
            tooltip.classList.remove("hidden");
            updateTooltipPosition(e);
        });

        trigger.addEventListener("mouseleave", () => {
            tooltip.classList.add("hidden");
        });

        trigger.addEventListener("mousemove", updateTooltipPosition);
    });

    // Animation on scroll
    const animateOnScroll = () => {
        const elements = document.querySelectorAll(".animate-on-scroll");
        elements.forEach((element) => {
            const elementTop = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;

            if (elementTop < windowHeight - 100) {
                element.classList.add("opacity-100", "translate-y-0");
            }
        });
    };

    // Initial check
    animateOnScroll();

    // Check on scroll
    window.addEventListener("scroll", animateOnScroll);
});
