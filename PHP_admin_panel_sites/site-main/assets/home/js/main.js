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

      $('#h-blog-slider').owlCarousel({
        loop: true,
        margin: 100,
        dots: true,
        nav: false,
        autoplay: false,
        animateOut: 'slideOutUp',
        stagePadding: 100,
        responsive: {
            0: { items: 1,margin:20,stagePadding: 20, },
            768: { items: 2,margin:30,stagePadding: 30, },
            1024: { items: 2.5,margin:50,stagePadding: 50, },
            1440: { items: 2.5 }
        }
    });


    $('.tabs-nav-main li').click(function(){
      $('.tabs-nav-main li').removeClass('active');
      $('.price-content').removeClass('active');
      var tid=$(this).attr('tab');
      $(this).addClass('active');
      $('#' +tid).addClass('active');
     });
});