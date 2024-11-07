console.log('Media Wolf Security Facts JS ... ')

jQuery = jQuery.noConflict();

jQuery(document).ready(function($) {
    $('.security-facts-carousel').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 6000,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    });
});