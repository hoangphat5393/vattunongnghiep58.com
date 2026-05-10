/**
 * Extracted from resources/views/frontend/layouts/header.blade.php (@push scripts)
 * and duplicated in resources/js/custom.js (DOMContentLoaded IIFE).
 * Opens/closes the off-canvas drawer + dimmed overlay.
 */
document.addEventListener('DOMContentLoaded', function () {
    var btn = document.getElementById('mobile-menu-btn');
    var menu = document.getElementById('mobile-menu');
    var overlay = document.getElementById('mobile-menu-overlay');
    var closeBtn = document.getElementById('close-menu-btn');

    if (!btn || !menu || !overlay || !closeBtn) {
        return;
    }

    var openMenu = function () {
        overlay.classList.remove('hidden');
        setTimeout(function () {
            menu.classList.remove('-translate-x-full');
        }, 10);
    };

    var closeMenu = function () {
        menu.classList.add('-translate-x-full');
        setTimeout(function () {
            overlay.classList.add('hidden');
        }, 300);
    };

    btn.addEventListener('click', openMenu);
    closeBtn.addEventListener('click', closeMenu);
    overlay.addEventListener('click', closeMenu);
});
