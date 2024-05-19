(function ($) {
	"use strict";

    // preloader
    $(window).on("load", function () {
        $(".preloader").delay(350).fadeOut('slow');
    });

	$(".da-process__content-item").on('mouseenter', function () {
		$(".da-process__content-item").removeClass("active");
		$(this).addClass("active");
	});

	// back to top start
	$(window).scroll(function() {
		if ($(this).scrollTop() > 200) {
		$('.backtotop:hidden').stop(true, true).fadeIn();
		} else {
		$('.backtotop').stop(true, true).fadeOut();
		}
	});
	$(function() {
		$(".scroll").on('click', function() {
		$("html,body").animate({scrollTop: 0}, "slow");
		return false
		});
	});

	$(".sidebar-panel > a").on("click", function (e) {
		e.preventDefault();
		$(".slide-bar").toggleClass("show");
		$("body").addClass("on-side");
		$('.body-overlay').addClass('active');
		$(this).addClass('active');
	});
	
	// sticky start
	var wind = $(window);
	var sticky = $('#sticky_header');
	wind.on('scroll', function () {
		var scroll = wind.scrollTop();
		if (scroll < 200) {
			sticky.removeClass('sticky_header');
		} else {
			sticky.addClass('sticky_header');
		}
	});
	// sticky end

	// mobile menu start
	$('#mobile-menu-active').metisMenu();

	$('#mobile-menu-active .dropdown > a').on('click', function (e) {
		e.preventDefault();
	});

	$(".sidebar-nav > a").on("click", function (e) {
		e.preventDefault();
		$(".slide-bar").toggleClass("show");
		$("body").addClass("on-side");
		$('.body-overlay').addClass('active');
		$(this).addClass('active');
	});

	$(".hamburger_menu > a").on("click", function (e) {
		e.preventDefault();
		$(".slide-bar").toggleClass("show");
		$("body").addClass("on-side");
		$('.body-overlay').addClass('active');
		$(this).addClass('active');
	});

	$(".close-mobile-menu > a").on("click", function (e) {
		e.preventDefault();
		$(".slide-bar").removeClass("show");
		$("body").removeClass("on-side");
		$('.body-overlay').removeClass('active');
		$('.hamburger_menu > a').removeClass('active');
	});

	$('.body-overlay').on('click', function () {
		$(this).removeClass('active');
		$(".slide-bar").removeClass("show");
		$("body").removeClass("on-side");
		$('.hamburger-menu > a').removeClass('active');
	});
	// mobile menu end

	// Off Canvas - Start
	// --------------------------------------------------
	$(document).ready(function () {
		$('.cart_close_btn, .body-overlay').on('click', function () {
		$('.cart_sidebar').removeClass('active');
		$('.body-overlay').removeClass('active');
		});

		$('.cart_btn').on('click', function () {
		$('.cart_sidebar').addClass('active');
		$('.body-overlay').addClass('active');
		});
	});


	//data background
	$("[data-background]").each(function () {
		$(this).css("background-image", "url(" + $(this).attr("data-background") + ") ")
	})

	// data bg color
	$("[data-bg-color]").each(function () {
		$(this).css("background-color", $(this).attr("data-bg-color"));

	});



	// brand slide 
	function brandActiveOne($scope, $) {
		$('.brand__slide').slick({
			infinite: true,
			speed: 500,
			slidesToShow: 6,
			autoplay: true,
			  autoplaySpeed: 3500,
			slidesToScroll: 1,
			dots: false,
			arrows: false,
			responsive: [
				{
				  breakpoint: 122,
				  settings: {
					slidesToShow: 6,
				  }
				},
				{
				  breakpoint: 1025,
				  settings: {
					slidesToShow: 5,
				  }
				},
				{
				  breakpoint: 800,
				  settings: {
					slidesToShow: 3,
				  }
				},
				{
				  breakpoint: 480,
				  settings: {
					slidesToShow: 2,
				  }
				}
			  ]
		});
	}


	// testimonial slide 
	function testimonialActiveOne($scope, $) {
		$('.testimonial__slide').slick({
			infinite: true,
			speed: 500,
			slidesToShow: 2,
			autoplay: true,
			autoplaySpeed: 6000,
			slidesToScroll: 1,
			dots: false,
			arrows: true,
			prevArrow: '<i class="slick-arrow slick-prev far fa-angle-left"></i>',
			nextArrow: '<i class="slick-arrow slick-next far far fa-angle-right"></i>',
			responsive: [
				{
				breakpoint: 1025,
				settings: {
					slidesToShow: 2,
				}
				},
				{
				breakpoint: 769,
				settings: {
					slidesToShow: 2,
				}
				},
				{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
				}
				}
			]
		});
	}

	// image gallery 
	function imgeGalleryActive($scope, $) {
		$('.image-gallery__slide').slick({
			infinite: true,
			speed: 500,
			slidesToShow: 4,
			autoplay: true,
			autoplaySpeed: 6000,
			slidesToScroll: 1,
			dots: false,
			arrows: true,
			prevArrow: '<i class="slick-arrow slick-prev"></i>',
			nextArrow: '<i class="slick-arrow slick-next"></i>',
			responsive: [
				{
				breakpoint: 1025,
				settings: {
					slidesToShow: 4,
				}
				},
				{
				breakpoint: 769,
				settings: {
					slidesToShow: 2,
				}
				},
				{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
				}
				}
			]
		});
	}

	// service slider
	function txServiceSlide($scope, $) {
	const slider = $(".tx-service__slide");
		slider
		.slick({
		dots: true,
		arrows: false,
		infinite:false,
		speed: 300,
		
		slidesToShow: 3,
		slidesToScroll: 1,
		responsive: [
			{
			  breakpoint: 1025,
			  settings: {
				slidesToShow: 3,
			  }
			},
			{
			  breakpoint: 769,
			  settings: {
				slidesToShow: 2,
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
				slidesToShow: 1,
			  }
			}
		  ]
		});

		slider.on('wheel', (function(e) {
			e.preventDefault();

			if (e.originalEvent.deltaY < 0) {
				$(this).slick('slickPrev');
			} else {
				$(this).slick('slickNext');
			}
		}));
	}


	// client-benifit
	function benifitSlideActive($scope, $) {
		$('.client-benifit__slide').slick({
			infinite: true,
			speed: 500,
			slidesToShow: 1,
			autoplay: true,
			autoplaySpeed: 6000,
			slidesToScroll: 1,
			dots: true,
			fade: true,
			arrows: true,
			prevArrow: '<i class="slick-arrow slick-prev far fa-angle-left"></i>',
			nextArrow: '<i class="slick-arrow slick-next far far fa-angle-right"></i>',
		});
	}

	// client-benifit
	$('.widget__post-slide').slick({
		infinite: true,
		speed: 500,
		slidesToShow: 1,
		autoplay: true,
  		autoplaySpeed: 6000,
		slidesToScroll: 1,
		dots: false,
		fade: false,
		arrows: true,
		prevArrow: '<i class="slick-arrow slick-prev far fa-angle-left"></i>',
		nextArrow: '<i class="slick-arrow slick-next far far fa-angle-right"></i>',
	});


	function brandMarqueeActive($scope, $) {
		$('.brand-marquee__left').marquee({
			speed: 50,
			gap: 30,
			delayBeforeStart: 0,
			direction: 'left',
			duplicated: true,
			pauseOnHover: false,
			startVisible:true,
		});
		$('.brand-marquee__right').marquee({
			speed: 50,
			gap: 30,
			delayBeforeStart: 0,
			direction: 'right',
			duplicated: true,
			pauseOnHover: false,
			startVisible:true,
		});
	}
	function marquTitleE1($scope, $) {
		$('.fin-marquee__text').marquee({
			speed: 50,
			gap: 30,
			delayBeforeStart: 0,
			direction: 'left',
			duplicated: true,
			pauseOnHover: false,
			startVisible:true,
		});
	}
	$('.marquee__text').marquee({
		speed: 50,
		gap: 30,
		delayBeforeStart: 0,
		direction: 'left',
		duplicated: true,
		pauseOnHover: false,
		startVisible:true,
	});
	
	$('.before-after__slide-1').marquee({
		speed: 50,
		gap: 0,
		delayBeforeStart: 0,
		direction: 'left',
		duplicated: true,
		pauseOnHover: true,
		startVisible:true,
	});
	
	$('.before-after__slide-2').marquee({
		speed: 50,
		gap: 0,
		delayBeforeStart: 0,
		direction: 'right',
		duplicated: true,
		pauseOnHover: true,
		startVisible:true,
	});

	$('.al-intigration__marquee--1').marquee({
		speed: 50,
		gap: 0,
		delayBeforeStart: 0,
		direction: 'left',
		duplicated: true,
		pauseOnHover: true,
		startVisible:true,
	});
	
	$('.al-intigration__marquee--2').marquee({
		speed: 50,
		gap: 0,
		delayBeforeStart: 0,
		direction: 'right',
		duplicated: true,
		pauseOnHover: true,
		startVisible:true,
	});
	

	
	/* magnificPopup img view */
	$('.popup-image').magnificPopup({
		type: 'image',
		gallery: {
			enabled: true
		}
	});

	/* magnificPopup video view */
	$('.popup-video').magnificPopup({
		type: 'iframe'
	});

	  /*-------------------------------------
    theiaStickySidebar
    -------------------------------------*/
	if (typeof $.fn.theiaStickySidebar !== "undefined") {
		$(".sticky-coloum-wrap .sticky-coloum-item").theiaStickySidebar({
		  additionalMarginTop: 130,
		});
	  }

	  //  Countdown
	$('[data-countdown]').each(function () {

		var $this = $(this),
			finalDate = $(this).data('countdown');
		if (!$this.hasClass('countdown-full-format')) {
			$this.countdown(finalDate, function (event) {
				$this.html(event.strftime('<div class="single"><h1>%D</h1><p>Days</p></div> <div class="single"><h1>%H</h1><p>Hours</p></div> <div class="single"><h1>%M</h1><p>Mins</p></div> <div class="single"><h1>%S</h1><p>Sec</p></div>'));
			});
		} else {
			$this.countdown(finalDate, function (event) {
				$this.html(event.strftime('<div class="single"><h1>%Y</h1><p>Years</p></div> <div class="single"><h1>%m</h1><p>Months</p></div> <div class="single"><h1>%W</h1><p>Weeks</p></div> <div class="single"><h1>%d</h1><p>Days</p></div> <div class="single"><h1>%H</h1><p>Hours</p></div> <div class="single"><h1>%M</h1><p>Mins</p></div> <div class="single"><h1>%S</h1><p>Sec</p></div>'));
			});
		}
	});

	
	$('.main-menu nav > ul > li').slice(-2).addClass('menu-last');

	// modell single
	$(function () {
		$('.model-option li').on('click', function () {
			var active = $('.model-option li.active');
			active.removeClass('active');
			$(this).addClass('active');
		});
	});



	/*------------------------------------------
        = NEWSLETTER POPUP 
    -------------------------------------------*/
    function newsletterPopup() {
        var newsletter = $(".newsletter-popup-area-section");
        var newsletterClose = $(".newsletter-close-btn");

        var test = localStorage.input === 'true'? true: false;
        $(".show-message").prop('checked', test || false);

        var localValue = localStorage.getItem("input");

        //console.log(localValue);

        if(localValue === "true") {
            newsletter.css({
                "display": "none"
            });                
        }

        newsletter.addClass("active-newsletter-popup");

        newsletterClose.on("click", function(e) {
            newsletter.removeClass("active-newsletter-popup");
            return false;
        })

        $(".show-message").on('change', function() {
            localStorage.input = $(this).is(':checked');
        });
    }


	// Accordion Box start
	if ($(".accordion_box").length) {
		$(".accordion_box").on("click", ".acc_btn", function () {
		var outerBox = $(this).parents(".accordion_box");
		var target = $(this).parents(".accordion");

		if ($(this).next(".acc_content").is(":visible")) {
			$(this).removeClass("active");
			$(this).next(".acc_content").slideUp(300);
			$(outerBox).children(".accordion").removeClass("active_block");
		} else {
			$(outerBox).find(".accordion .acc_btn").removeClass("active");
			$(this).addClass("active");
			$(outerBox).children(".accordion").removeClass("active_block");
			$(outerBox).find(".accordion").children(".acc_content").slideUp(300);
			target.addClass("active_block");
			$(this).next(".acc_content").slideDown(300);
		}
		});
	}
	// Accordion Box end

	
	$(".pricing__info li").on('mouseenter', function () {
		$(".pricing__info li").removeClass("active");
		$(this).addClass("active");
	});


	$(".contact-info__item").on('mouseenter', function () {
		$(".contact-info__item").removeClass("active");
		$(this).addClass("active");
	});
	

	
    // Elements Animation
	if($('.wow').length){
		var wow = new WOW(
		  {
			boxClass:     'wow',      // animated element css class (default is wow)
			animateClass: 'animated', // animation css class (default is animated)
			offset:       0,          // distance to the element when triggering the animation (default is 0)
			mobile:       true,       // trigger animations on mobile devices (default is true)
			live:         true       // act on asynchronously loaded content (default is true)
		  }
		);
		wow.init();
	}
	

	// bar start
	$(document).ready(function(){
		$('.tx-bar').barfiller({ barColor: '#FBC332' });
		
	});
	// bar end


	// odometer counter start
	jQuery('.odometer').appear(function (e) {
		var odo = jQuery(".odometer");
		odo.each(function () {
			var countNumber = jQuery(this).attr("data-count");
			jQuery(this).html(countNumber);
		});
	});
	// odometer counter end
	

	
	// modell single
	$(function () {
		$('.pricing__single').on('click', function () {
			var active = $('.pricing__single.active');
			active.removeClass('active');
			$(this).addClass('active');
		});
	});

	// isotop
	function portfolioMasonaryActive($scope, $) {
		$('.grid').imagesLoaded( function() {
			// init Isotope
			var $grid = $('.grid').isotope({
			itemSelector: '.grid-item',
			percentPosition: true,
			masonry: {
				// use outer width of grid-sizer for columnWidth
				columnWidth: '.grid-item',
			}
			});

			// filter items on button click
		$('.portfolio-menu').on( 'click', 'button', function() {
			var filterValue = $(this).attr('data-filter');
			$grid.isotope({ filter: filterValue });
		});
		});
	}

	
	function wowAnimationActive($scope, $) {
		var wow = new WOW(
		  {
			boxClass:     'wow',      // animated element css class (default is wow)
			animateClass: 'animated', // animation css class (default is animated)
			offset:       0,          // distance to the element when triggering the animation (default is 0)
			mobile:       true,       // trigger animations on mobile devices (default is true)
			live:         true       // act on asynchronously loaded content (default is true)
		  }
		);
		wow.init();
	}
	function wowAnimationActive($scope, $) {
		var wow = new WOW(
		  {
			boxClass:     'wow',      // animated element css class (default is wow)
			animateClass: 'animated', // animation css class (default is animated)
			offset:       0,          // distance to the element when triggering the animation (default is 0)
			mobile:       true,       // trigger animations on mobile devices (default is true)
			live:         true       // act on asynchronously loaded content (default is true)
		  }
		);
		wow.init();
	}
	$( document ).ready(function() {
        $('.finh__content .tx-text--slide:nth-child(3) .letter').addClass("highlight-img");
        $('.tx-heading--fin .tx-text--slide:nth-child(3) .letter').addClass("highlight-img");
        $('.tx-heading--tma .tx-text--slide:nth-child(3) .letter').addClass("highlight-img");
    });

	function fitnessHeroAnimatedUnderline( $scope, $ ){ 
		var $_this = $scope.find('.tx-item--title');
		var $currentID = '#'+$_this.attr('id'),
            $position = $_this.data('position');
            var pfy_post_car = $( $currentID );
		function selectNth (n) {
			$('.hero__content .tx-text--slide:nth-child('+n+') .letter').addClass("highlight");
		}	
		selectNth($position)
	}

	function ftaskHeroAnimatedUnderline( $scope, $ ){ 
		var $_this = $scope.find('.tx-item--title');
		var $currentID = '#'+$_this.attr('id'),
            $position = $_this.data('position');
            var pfy_post_car = $( $currentID );
		function selectNth (n) {
			$('.tmah__content .tx-text--slide:nth-child('+n+') .letter').addClass("highlight-img");
		}	
		selectNth($position)
	}
	function appHeroAnimatedUnderline( $scope, $ ){ 
		var $_this = $scope.find('.tx-item--title');
		var $currentID = '#'+$_this.attr('id'),
            $position = $_this.data('position');
            var pfy_post_car = $( $currentID );
		function selectNth (n) {
			$('.hero__app-landing-content .tx-heading .tx-text--slide:nth-child('+n+') .letter').addClass("highlight");
		}	
		selectNth($position)
	}
	function appAboutAnimatedUnderline( $scope, $ ){ 
		var $_this = $scope.find('.tx-item--title');
		var $currentID = '#'+$_this.attr('id'),
            $position = $_this.data('position');
            var pfy_post_car = $( $currentID );
		function selectNth (n) {
			$('.about .tx-heading .tx-text--slide:nth-child('+n+') .letter').addClass("highlight");
		}	
		selectNth($position)
	}
	function appPricingAnimatedUnderline( $scope, $ ){ 
		var $_this = $scope.find('.tx-item--title');
		var $currentID = '#'+$_this.attr('id'),
            $position = $_this.data('position');
            var pfy_post_car = $( $currentID );
		function selectNth (n) {
			$('.pricing .tx-heading .tx-text--slide:nth-child('+n+') .letter').addClass("highlight");
		}	
		selectNth($position)
	}

	function titleAppActive( $scope, $ ){ 
		var $_this = $scope.find('.tx-item--title');
		var $currentID = '#'+$_this.attr('id'),
            $position = $_this.data('position');
            var pfy_post_car = $( $currentID );
		function selectNth (n) {
			$('.tx-app-title .tx-text--slide:nth-child('+n+') .letter').addClass("highlight");
		}	
		selectNth($position)
	}
	
	
	function titleVTActive( $scope, $ ){ 
		$('.tx-it-v2 .tx-text--slide:nth-child(2) .letter').addClass("highlight");
	}

	/**
	 * Intigration
	 */
	 function intigrationActive( $scope, $ ){ 
	$('.integration__content').slick({
		dots: false,
		arrows: false,
		fade: false,
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 3000,
		asNavFor: '.integration__nav'
	  });

	  $('.integration__nav').slick({
		dots: false,
		arrows: true,
		slidesToShow: 7,
		slidesToScroll: 1,
		centerMode: true,
		focusOnSelect: true,
		verticalSwiping: true,
		centerPadding: 0,
		asNavFor: '.integration__content',
		prevArrow: '<button type="button" class="slick-prev"><i class="far fa-chevron-left"></i></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="far fa-chevron-right"></i></button>',
		responsive: [
			{
			  breakpoint: 122,
			  settings: {
				slidesToShow: 7,
			  }
			},
			{
			  breakpoint: 1025,
			  settings: {
				slidesToShow: 5,
			  }
			},
			{
			  breakpoint: 769,
			  settings: {
				slidesToShow: 3,
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
				slidesToShow: 1,
			  }
			}
		  ]
	  });
	}
	function appShowcaseActive( $scope, $ ){ 
		$('.da-brand__slide').slick({
			infinite: true,
			speed: 800,
			slidesToShow: 7,
			autoplay: true,
			autoplaySpeed: 3500,
			slidesToScroll: 1,
			centerMode: true,
			centerPadding: 0,
			dots: false,
			arrows: true,
			prevArrow: '<button type="button" class="slick-prev"><i class="far fa-chevron-left"></i></button>',
			nextArrow: '<button type="button" class="slick-next"><i class="far fa-chevron-right"></i></button>',
			responsive: [
				{
				breakpoint: 122,
				settings: {
					slidesToShow: 7,
				}
				},
				{
				breakpoint: 1025,
				settings: {
					slidesToShow: 5,
				}
				},
				{
				breakpoint: 769,
				settings: {
					slidesToShow: 5,
				}
				},
				{
				breakpoint: 480,
				settings: {
					slidesToShow: 3,
				}
				}
			]
		});
	}
	function appLandingSliderActive( $scope, $ ){ 
		$('.app-landing__slider').slick({
			infinite: true,
			speed: 500,
			slidesToShow: 1,
			autoplay: true,
			autoplaySpeed: 6000,
			slidesToScroll: 1,
			dots: false,
			fade: false,
			arrows: true,
			prevArrow: '<i class="slick-arrow slick-prev far fa-angle-left"></i>',
			nextArrow: '<i class="slick-arrow slick-next far far fa-angle-right"></i>',
		});
	}
	function brandNwActive( $scope, $ ){ 
		$('.bar-video-sponsor-slider').owlCarousel({
			loop:true,
			margin:0,
			nav:false,
			smartSpeed: 500,
			autoplay: 4000,
			responsive:{
				0:{
					items:1
				},
				480:{
					items:1
				},
				600:{
					items:2
				},
				800:{
					items:3
				},
				1024:{
					items:4
				},
				1600:{
					items:6
				},
				1920:{
					items:7
				}
			}
		});   
	}
	function processActive( $scope, $ ){ 
		$('.al-process__slide').slick({
			infinite: true,
			speed: 500,
			slidesToShow: 4,
			autoplay: true,
			  autoplaySpeed: 6000,
			slidesToScroll: 1,
			dots: false,
			arrows: true,
			prevArrow: '<i class="slick-arrow slick-prev far fa-angle-left"></i>',
			nextArrow: '<i class="slick-arrow slick-next far far fa-angle-right"></i>',
			responsive: [
				{
				  breakpoint: 1500,
				  settings: {
					slidesToShow: 3,
				  }
				},
				{
				  breakpoint: 1201,
				  settings: {
					slidesToShow: 2,
				  }
				},
				{
				  breakpoint: 769,
				  settings: {
					slidesToShow: 2,
				  }
				},
				{
				  breakpoint: 480,
				  settings: {
					slidesToShow: 1,
				  }
				}
			  ]
		});
	}
	function invoiceBrandActive( $scope, $ ){ 
		$('.is-sponsor-slider').owlCarousel({
			loop:true,
			margin:30,
			nav:false,
			smartSpeed: 500,
			autoplay: true,
			responsive:{
				0:{
					items:1
				},
				480:{
					items:2
				},
				600:{
					items:3
				},
				800:{
					items:4
				},
				1024:{
					items:5
				},
				1200:{
					items:6
				}
			}
		});    
	}
	function invoiceServiceSliderActive( $scope, $ ){ 
		$('.is-service-slider').owlCarousel({
			loop:true,
			dot: false,
			nav:false,
			items:4,
			margin:30,
			pagination: true,
			smartSpeed: 500,
			autoplay: true,
			responsive:{
				0:{
					items:1
				},
				480:{
					items:2
				},
				600:{
					items:3
				},
				800:{
					items:4
				},
				1024:{
					items:3
				},
				1200:{
					items:4
				}
			}
		});   
	}
	function invoiceTestimonial( $scope, $ ){ 
		$('.is-testimonial-slider').owlCarousel({
			loop:true,
			dot: false,
			nav:true,
			items:1,
			navText: [ '<i class="fas fa-arrow-left"></i>', '<i class="fas fa-arrow-right"></i>' ],
			pagination: true,
			smartSpeed: 500,
			autoplay: false,
		});     
	}

	
	
	function testimonialActveM( $scope, $ ){
		$('.ba-testimonial__marquee--1').marquee({
			speed: 50,
			gap: 0,
			delayBeforeStart: 0,
			direction: 'left',
			duplicated: true,
			pauseOnHover: false,
			startVisible:true,
		});		

		$('.ba-testimonial__marquee--2').marquee({
			speed: 50,
			gap: 0,
			delayBeforeStart: 0,
			direction: 'right',
			duplicated: true,
			pauseOnHover: false,
			startVisible:true,
		});
	}

	$('.is-case-study-scroll1').marquee({
		speed: 50,
		gap: 10,
		delayBeforeStart: 0,
		direction: 'left',
		duplicated: true,
		pauseOnHover: true,
		startVisible:true,
	});
	$('.is-case-study-scroll2').marquee({
		speed: 50,
		gap: 10,
		delayBeforeStart: 0,
		direction: 'right',
		duplicated: true,
		pauseOnHover: true,
		startVisible:true,
	});

	function invoiceTextMarquee( $scope, $ ){
		$('.is-text-scroll-item').marquee({
			speed: 50,
			gap: 20,
			delayBeforeStart: 0,
			direction: 'left',
			duplicated: true,
			pauseOnHover: true,
			startVisible:true,
		});
	}
	

// parallax - start
  // --------------------------------------------------
  if ($('.scene,.scene_1,.scene_2,.scene_3,.scene_4,.scene_5').length > 0 ) {
    $('.scene,.scene_1,.scene_2,.scene_3,.scene_4,.scene_5').parallax({
      scalarX: 10.0,
      scalarY: 10.0,
    }); 
  }
	function testimonialActiveFour( $scope, $ ){ 
		$('.bar-video-testimonial-slider').owlCarousel({
			loop:true,
			nav:false,
			dots: true,
			smartSpeed: 500,
			autoplay: 4000,
			items: 1,
		});  
	}

	

$(window).on('elementor/frontend/init', function () {
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-service-carousel-id.default', txServiceSlide);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-image-gallery-id.default', imgeGalleryActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-project-id.default', portfolioMasonaryActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-fin-hero-id.default', wowAnimationActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-fit-hero-id.default', wowAnimationActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-bank-hero-id.default', wowAnimationActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-app-about-id.default', wowAnimationActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/appics-app-pricing-plan-id.default', wowAnimationActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics--brand-id.default', brandMarqueeActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics--brand-v2-id.default', brandMarqueeActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-testimonial-id.default', testimonialActiveOne);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-testimonial-v2-id.default', testimonialActiveOne);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-s-marqu-id.default', marquTitleE1);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-logo-brand-id.default', brandActiveOne);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-logo-brand-v2-id.default', brandActiveOne);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-bnifit-features-id.default', benifitSlideActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-fit-hero-id.default', fitnessHeroAnimatedUnderline);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-s-heading-v2-id.default', titleVTActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-app-heading-id.default', titleAppActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-task-man-hero-id.default', ftaskHeroAnimatedUnderline);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-task-man-hero-id.default', wowAnimationActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-s-heading-v3-id.default', wowAnimationActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-intigrations-id.default', intigrationActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics--app-showcase-v2-id.default', appShowcaseActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics--brand-v3-id.default', brandNwActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-testimonial-v4-id.default', testimonialActiveFour);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-testimonial-v5-id.default', testimonialActveM);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-app-hero-id.default', appHeroAnimatedUnderline);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-app-hero-id.default', appLandingSliderActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-app-about-id.default', appAboutAnimatedUnderline);
	elementorFrontend.hooks.addAction('frontend/element_ready/appics-app-pricing-plan-id.default', appPricingAnimatedUnderline);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-app-process-id.default', processActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-invoice-brand-id.default', invoiceBrandActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-invoice-service-slider-id.default', invoiceServiceSliderActive);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-invoice-testimonial-id.default', invoiceTestimonial);
	elementorFrontend.hooks.addAction('frontend/element_ready/apics-invoice-text-marquee-id.default', invoiceTextMarquee);
	
});


})(jQuery);

