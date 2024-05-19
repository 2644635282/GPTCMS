(function ($) {
    "use strict"; // Start of use strict

// Preloader Start
  $(window).on('load',function () {
    $('#preloader-status').fadeOut();
    $('#preloader')
      .delay(350)
      .fadeOut('slow');
    $('body')
      .delay(350)
  });
// Preloader End

    /*---------------------------------
    Brand Logo Slider
   -----------------------------------*/
  $('.direction-ltr .brand-carousel').owlCarousel({
        items: 6,
        loop: true,
        autoplay: true,
        autoplayTimeout: 1500,
        margin: 5,
        nav: false,
        dots: false,
        rtl: false,
        animateIn: 'fadeIn',
        animateOut: 'fadeOut',
        smartSpeed: 3000,
        autoplayTimeout:3000,
        responsive:{
        0:{
            items:1
        },
        575:{
            items:2
        },
        991:{
            items:3
        },
        1199:{
          items:4
        },
        1200:{
          items:6
        }
      }
    });

    $('.direction-rtl .brand-carousel').owlCarousel({
        items: 6,
        loop: true,
        autoplay: true,
        autoplayTimeout: 1500,
        margin: 5,
        nav: false,
        dots: false,
        rtl: true,
        animateIn: 'fadeIn',
        animateOut: 'fadeOut',
        smartSpeed: 3000,
        autoplayTimeout:3000,
        responsive:{
        0:{
            items:1
        },
        575:{
            items:2
        },
        991:{
            items:3
        },
        1199:{
          items:4
        },
        1200:{
          items:6
        }
      }
    })

    /*---------------------------------
    Customer Testimonial JS
   -----------------------------------*/
    $('.direction-ltr .customer-testimonial-slider').owlCarousel({
      items: 2,
      loop: true,
      autoplay: false,
      autoplayTimeout: 1500,
      margin: 25,
      nav: false,
      dots: true,
      rtl: false,
      navText: [
        "<span class=\"iconify\" data-icon=\"bi:arrow-left\"></span>",
        "<span class=\"iconify\" data-icon=\"bi:arrow-right\"></span>",
    ],
      smartSpeed: 3000,
      autoplayTimeout:3000,
      responsive:{
        0:{
            items:1
        },
        575:{
            items:1
        },
        991:{
            items:2
        },
        1199:{
          items:2
        },
        1200:{
          items:2
        }
      }
    });

    $('.direction-rtl .customer-testimonial-slider').owlCarousel({
      items: 2,
      loop: true,
      autoplay: false,
      autoplayTimeout: 1500,
      margin: 25,
      nav: false,
      dots: true,
      rtl: true,
      navText: [
        "<span class=\"iconify\" data-icon=\"bi:arrow-left\"></span>",
        "<span class=\"iconify\" data-icon=\"bi:arrow-right\"></span>",
    ],
      smartSpeed: 3000,
      autoplayTimeout:3000,
      responsive:{
        0:{
            items:1
        },
        575:{
            items:1
        },
        991:{
            items:2
        },
        1199:{
          items:2
        },
        1200:{
          items:2
        }
      }
    });

  /*---------------------------------
    Show/Hide Password/ Toggle Password JS
  -----------------------------------*/
  $(".toggle").on("click", function () {

    if ($(".password").attr("type") == "password")
    {
        //Change type attribute
        $(".password").attr("type", "text");
        $(this).removeClass("fa-eye");
        $(this).addClass("fa-eye-slash");
    } else
    {
        //Change type attribute
        $(".password").attr("type", "password");
        $(this).addClass("fa-eye");
        $(this).removeClass("fa-eye-slash");
    }
});
/*---------------------------------
Show/Hide Password/ Toggle Password JS
-----------------------------------*/

})(jQuery); // End of use strict
