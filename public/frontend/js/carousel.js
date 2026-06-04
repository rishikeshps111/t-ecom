$('.baner-main-carousel').owlCarousel({
    loop: true,
    dots: false,
    nav: false,
    margin:0,
    animateIn: 'fadeIn',
    animateOut: 'fadeOut',
    autoplay: true,
    autoplayTimeout: 3000,
    responsive: {
        0: {
            items: 1
        },
        768: {
            items: 1
        },
        991: {
            items: 1
        },
        1200: {
            items: 1
        },
    }
});

$('.category-carusel').owlCarousel({
     loop: true,
    dots: false,
    nav: true,
    margin:10,
    autoplay: true,
    autoplayTimeout: 3000,
  
    responsive: {
        0: {
            items: 1
        },
        768: {
            items: 2
        },
        991: {
            items: 3
        },
        1200: {
            items:4
        },
    }
});
$('.deal-short-carusel').owlCarousel({
     loop: true,
    dots: false,
    nav: true,
    margin:0,
    autoplay: true,
    autoplayTimeout: 3000,
  
    responsive: {
        0: {
            items: 1
        },
        768: {
            items: 1
        },
        991: {
            items: 1
        },
        1200: {
            items:1
        },
    }
});
$('.event-photo-carusel').owlCarousel({
     loop: true,
    dots: false,
    nav: true,
    margin:0,
    autoplay: true,
    autoplayTimeout: 3000,
  
    responsive: {
        0: {
            items: 1
        },
        768: {
            items: 1
        },
        991: {
            items: 1
        },
        1200: {
            items:1
        },
    }
});
$('.cities-carusel').owlCarousel({
     loop: true,
    dots: false,
    nav: true,
    margin:10,
    autoplay: true,
    responsive: {
        0: {
            items: 1
        },
        768: {
            items: 1
        },
        991: {
            items: 1
        },
        1200: {
            items:1
        },
    }
});

$('.gallery-carousel').owlCarousel({
    loop: true,
    dots: false,
    nav: true,
    autoplay: true,
    autoplayTimeout: 3000,
    responsive: {
        0: {
            items: 1
        },
        768: {
            items: 1
        },
        991: {
            items: 1
        },
        1200: {
            items: 1
        },
    }
});