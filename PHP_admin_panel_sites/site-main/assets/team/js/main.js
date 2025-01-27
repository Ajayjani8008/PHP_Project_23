$(document).ready(function(){
    $('#h-testimonial-slider').owlCarousel({  
        loop: true,
        margin: 30,
        dots: true,
        nav: false,
        stagePadding: 200, 
        rtl: false,
        responsive: {
            0: { items: 1, stagePadding: 20 },
            768: { items: 2, stagePadding: 0 },
            1024: { items: 2,stagePadding: 100, },
            1440: { items: 2 }
        }
      });

  
});