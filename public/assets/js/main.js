$(function () {
    'use strict';

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();

    // Initiate the wowjs
    new WOW().init();

    // Sticky Navbar
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 300) {
            $('.sticky-top').addClass('shadow-sm').css('top', '0px');
        } else {
            $('.sticky-top').removeClass('shadow-sm').css('top', '-150px');
        }
    });

    $('.btn-close').on('click', function () {
        $('.offcanvas-nav').removeClass('collapse show');
    });

    // Back to top button
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').on('click', function () {
        $('html, body').animate({ scrollTop: 0 }, 500, 'easeInOutExpo');
        return false;
    });

    // Modal Video
    var $videoSrc;
    $('.btn-play').on('click', function () {
        $videoSrc = $(this).data('src');
    });
    // console.log($videoSrc);
    $('#videoModal').on('shown.bs.modal', function (e) {
        $('#video').attr('src', $videoSrc + '?autoplay=1&amp;modestbranding=1&amp;showinfo=0');
    });
    $('#videoModal').on('hide.bs.modal', function (e) {
        $('#video').attr('src', $videoSrc);
    });

    $('.input-search').on('keyup paste', function () {
        $('.input-search').val($(this).val());
    });
});
