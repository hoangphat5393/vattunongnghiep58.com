(function () {
    'use strict';

    if (typeof axios !== 'undefined') {
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfMeta.getAttribute('content');
        }
    }

    function httpPost(url, data, config) {
        return axios.post(url, data, config);
    }

    function httpGet(url, config) {
        return axios.get(url, config);
    }

    window.httpPost = httpPost;
    window.httpGet = httpGet;

    $(function () {
        const spinner = function () {
            setTimeout(function () {
                if ($('#spinner').length > 0) {
                    $('#spinner').removeClass('show');
                }
            }, 1);
        };
        spinner();

        if (typeof WOW !== 'undefined') {
            new WOW().init();
        }

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

        let $videoSrc;
        $('.btn-play').on('click', function () {
            $videoSrc = $(this).data('src');
        });

        $('#videoModal').on('shown.bs.modal', function () {
            $('#video').attr('src', $videoSrc + '?autoplay=1&amp;modestbranding=1&amp;showinfo=0');
        });

        $('#videoModal').on('hide.bs.modal', function () {
            $('#video').attr('src', $videoSrc);
        });

        $('.input-search').on('keyup paste', function () {
            $('.input-search').val($(this).val());
        });
    });
})();
