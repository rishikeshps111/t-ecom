document.addEventListener('DOMContentLoaded', function () {
    var header = document.querySelector('.header');

    function updateHeaderState() {
        if (!header) {
            return;
        }

        header.classList.toggle('sticky-header', window.scrollY > 50);
    }

    updateHeaderState();
    window.addEventListener('scroll', updateHeaderState, { passive: true });

    if (!window.jQuery || !jQuery.fn.owlCarousel) {
        return;
    }

    jQuery('.testimonial-carousel').owlCarousel({
        loop: false,
        dots: false,
        nav: true,
        margin: 30,
        autoplay: true,
        autoplayTimeout: 3000,
        autoHeight: true,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 1
            },
            991: {
                items: 2
            },
            1200: {
                items: 2
            }
        }
    });

    jQuery('.team-carousel').owlCarousel({
        loop: false,
        dots: true,
        nav: true,
        margin: 30,
        autoplay: true,
        autoplayTimeout: 3000,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 3
            },
            991: {
                items: 3
            },
            1200: {
                items: 4
            }
        }
    });
});
