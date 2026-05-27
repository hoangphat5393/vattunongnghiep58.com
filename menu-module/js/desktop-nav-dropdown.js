/**
 * Desktop nav cấp 2: toggle class `is-open` + aria-expanded (mirror resources/js/custom.js).
 * Cần markup [data-nav-dropdown] và nút [data-nav-dropdown-toggle] như header production.
 */
document.addEventListener('DOMContentLoaded', function () {
    var navDropdownRoots = document.querySelectorAll('[data-nav-dropdown]');

    var closeAllNavDropdowns = function () {
        navDropdownRoots.forEach(function (root) {
            root.classList.remove('is-open');
            var toggle = root.querySelector('[data-nav-dropdown-toggle]');
            if (toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    };

    navDropdownRoots.forEach(function (root) {
        var toggle = root.querySelector('[data-nav-dropdown-toggle]');
        if (!toggle) {
            return;
        }

        toggle.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            var willOpen = !root.classList.contains('is-open');
            closeAllNavDropdowns();
            if (willOpen) {
                root.classList.add('is-open');
                toggle.setAttribute('aria-expanded', 'true');
            }
        });
    });

    document.addEventListener('click', function (event) {
        if (!event.target.closest('[data-nav-dropdown]')) {
            closeAllNavDropdowns();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeAllNavDropdowns();
        }
    });
});
