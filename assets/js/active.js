/**
 * Mosh front-end behaviour, without jQuery: the search toggle, scroll-to-top
 * (ColorlibUI's drop-in ScrollUp), empty "#" links, reveal on scroll, the
 * sticky header and the preloader.
 */
(function () {
    'use strict';

    var UI = window.ColorlibUI;
    if (!UI) return;

    UI.ready(function () {
        var searchBtn = document.getElementById('search-btn');
        if (searchBtn) {
            searchBtn.addEventListener('click', function () {
                document.body.classList.toggle('search-form-open');
                UI.toElements('.search-form-area').forEach(function (el) {
                    el.classList.toggle('fadeIn');
                });
            });
        }

        UI.toElements('a[href="#"]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
            });
        });
    });

    UI.scrollUp({
        scrollSpeed: 1500,
        scrollText: '<i class="fa-solid fa-angle-up"></i>'
    });

    if (document.documentElement.clientWidth > 767) {
        UI.reveal('.wow');
    }

    window.addEventListener('scroll', function () {
        var sticky = window.pageYOffset > 0;
        UI.toElements('.header_area').forEach(function (header) {
            header.classList.toggle('sticky', sticky);
        });
    }, { passive: true });

    function hidePreloader() {
        var preloader = document.getElementById('preloader');
        if (!preloader) return;
        if (window.getComputedStyle(preloader).display === 'none') {
            preloader.remove();
            return;
        }
        UI.fade(preloader, 'out', 600, function () {
            this.remove();
        });
    }
    if (document.readyState === 'complete') {
        hidePreloader();
    } else {
        window.addEventListener('load', hidePreloader);
    }
}());
