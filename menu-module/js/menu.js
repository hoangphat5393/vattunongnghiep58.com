/**
 * Extracted from resources/js/custom.js (DOMContentLoaded IIFE tail).
 * Syncs ?keyword= or ?q= from the URL into search inputs (name="keyword" or "q").
 */
document.addEventListener('DOMContentLoaded', function () {
    var urlParams = new URLSearchParams(window.location.search);
    var query = urlParams.get('keyword') || urlParams.get('q');

    if (!query) {
        return;
    }

    var searchInputs = document.querySelectorAll('input[name="keyword"], input[name="q"]');
    searchInputs.forEach(function (input) {
        input.value = query;
    });
});
