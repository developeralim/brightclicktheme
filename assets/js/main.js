import "../css/main.css";

/**
 * Header: toggle the frosted-glass state once the page is scrolled.
 */
const header = document.getElementById("site-header");
if (header) {
    const onScroll = () => {
        header.classList.toggle("is-scrolled", window.scrollY > 8);
    };
    onScroll();
    window.addEventListener("scroll", onScroll, { passive: true });
}

/**
 * Mobile menu: toggle the panel and swap the hamburger / close icons.
 */
const toggle = document.querySelector("[data-menu-toggle]");
const panel = document.getElementById("mobile-menu");
if (toggle && panel) {
    const iconOpen = toggle.querySelector("[data-menu-icon-open]");
    const iconClose = toggle.querySelector("[data-menu-icon-close]");

    toggle.addEventListener("click", () => {
        const isOpen = panel.classList.toggle("hidden") === false;
        toggle.setAttribute("aria-expanded", String(isOpen));
        iconOpen?.classList.toggle("hidden", isOpen);
        iconClose?.classList.toggle("hidden", !isOpen);
    });
}
