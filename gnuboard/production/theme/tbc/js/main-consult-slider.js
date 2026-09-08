(function () {
    function initConsultSlider() {
        var wrap = document.querySelector('#main_banner_wrap .tbc-consult-slider-wrap');
        if (!wrap || wrap.getAttribute('data-tbc-consult-ready') === '1') {
            return;
        }

        if (typeof Swiper === 'undefined') {
            return;
        }

        var sliderEl = wrap.querySelector('.tbc_consult_slide');
        var contact = wrap.closest('.contact');
        var nextEl = contact ? contact.querySelector('.tbc-consult-next') : null;
        var prevEl = contact ? contact.querySelector('.tbc-consult-prev') : null;
        if (!sliderEl || !nextEl || !prevEl) {
            return;
        }

        wrap.setAttribute('data-tbc-consult-ready', '1');

        var consultSwiper = new Swiper(sliderEl, {
            effect: 'fade',
            fadeEffect: { crossFade: true },
            slidesPerView: 1,
            loop: true,
            speed: 500,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: nextEl,
                prevEl: prevEl,
            },
        });

        if (consultSwiper.autoplay && typeof consultSwiper.autoplay.start === 'function') {
            consultSwiper.autoplay.start();
        }
    }

    function bootConsultSlider() {
        initConsultSlider();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bootConsultSlider);
    } else {
        bootConsultSlider();
    }

    window.addEventListener('load', bootConsultSlider);
})();
