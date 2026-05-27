/**
 * Mở/đóng drawer mobile + overlay (mirror resources/js/custom.js cùng đoạn với mobile menu).
 * Dropdown desktop: dùng thêm `js/desktop-nav-dropdown.js` (class `is-open` trên [data-nav-dropdown]).
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
