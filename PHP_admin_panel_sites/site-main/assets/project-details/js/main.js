$(document).ready(function(){
   
    $('#h-other-project-slider').owlCarousel({
        loop: false,
        margin: 30,
        dots: true,
        nav: false,
        autoplay: false,
        animateOut: 'slideOutUp',
        stagePadding: 150,
        responsive: {
            0: { items: 1,margin:20,stagePadding: 20, },
            768: { items: 2,margin:30,stagePadding: 30, },
            1024: { items: 2.5,margin:30,stagePadding: 50, },
            1440: { items: 2.5 }
        }
    });

});