(function($){

	var homeHeroSlider = function() {
		$('.hero-slider').slick({
			arrows: false,
			// prevArrow: '<button class="slick-arrow slick-prev" aria-label="Previous" type="button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20.93 70"><polygon points="20.77 70 8.15 35.01 20.93 0 13.02 0 0 35 13.02 70 20.77 70"/></svg></button>',
			// nextArrow: '<button class="slick-arrow slick-next" aria-label="Next" type="button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20.93 70"><polygon points=".16 0 12.78 34.99 0 70 7.91 70 20.93 35 7.91 0 .16 0"/></svg></button>',
			dots: false,
			fade: true,
			speed: 2000,
			autoplay: true,
			autoplaySpeed: 4000,
			pauseOnHover: false,
			lazyLoad: 'ondemand',
		});
	};

	var heroVideo = function() {
		document.addEventListener('DOMContentLoaded', function () {
			var video = document.getElementById('hero-video');
			if (!video.length) {
				return;
			}

			video.muted = true;

			var playPromise = video.play();

			if (playPromise !== undefined) {
				playPromise.then(function () {
					// autoplay started
				}).catch(function () {
					// autoplay was blocked
				});
			}
		});

		$('[data-action="hero-popup-play"]').on('click', function(e) {
			e.preventDefault();

			var heroVideoModal = document.getElementById('hero-video-modal');
			var heroVideoModalObj = $('#hero-video-modal');

			$.magnificPopup.open({
				mainClass: 'hero-popup',
				items: {
					src: '#hero-video-modal-container',
					type: 'inline',
				},
				callbacks: {
					open: function() {
						if(heroVideoModalObj.length){
							heroVideoModal.play();
						}
					},
					close: function() {
						if(heroVideoModalObj.length){
							heroVideoModal.pause();
							heroVideoModal.currentTime = 0;
						}
					},
				}
			}, 0);
			
		});
		
		$('[data-action="hero-popup-embed"]').magnificPopup({
			type: 'iframe',
			// mainClass: 'hero-popup'
		});

	};


	var scrollAnim = function(){

		///** convert to use intersectionObserver **///

		var win = $(window);

		var items = $('.scroll-animate-item');

		var itemScrollCheck = function(){

			var winHeight = win.innerHeight();
			var winTop = win.scrollTop();
			var scrollTriggerPos = (winHeight * .8) + winTop;

			items.each(function(i, el){

				var item = $(el);
				var itemTop = item.offset().top;

				if(itemTop <= scrollTriggerPos){
					item.addClass('vis');
				}
				else{
					item.removeClass('vis');
				}

			});

		};

		itemScrollCheck();
		optimizedScroll.add(itemScrollCheck);
	};


	var scrollToAnchor = function(){

		if( location.hash ){
			// window.scrollTo(0,0);

			$( 'body' ).removeClass( 'nav-open' );
			
			var locationHashObj = $(location.hash);
			
			if(locationHashObj.length > 0){
				//$('body').removeClass('nav-open');
				// $('body,html').animate({
				// 	scrollTop: locationHashObj.offset().top - 150
				// }, 500);

				var waitTime = 501;

				if(!$('body').hasClass('scrolled')){
					waitTime = 501;
					$('body').addClass('scrolled');
				}

				setTimeout(function(){
					var anchorPosition = locationHashObj.offset().top;
					var finalPosition = anchorPosition - $('.site-header').outerHeight() - 20;
					$("html, body").animate({scrollTop: finalPosition}, 1000);


				}, waitTime);
			}
		}

		$('.scroll-to-anchor a, a[href^="#"]').on('click', function(e){

			if($(this).hasClass('no-scroll')){
				return;
			}
			else if(this.hash){
				
				var hashTarget = $(this.hash);

				if( hashTarget.length ){
					e.preventDefault();
					var waitTime = 0;
					
					if(!$('body').hasClass('scrolled')){
						waitTime = 501;
						$('body').addClass('scrolled');
					}

					setTimeout(function(){
						var anchorPosition = hashTarget.offset().top;
						var finalPosition = anchorPosition - $('.site-header').outerHeight() - 20;
						$("html, body").animate({scrollTop: finalPosition}, 1000);

						$( 'body' ).removeClass( 'nav-open' );

					}, waitTime);
				}
			}
		});
	};

	var blockGallery = function() {
		$('.wp-block-gallery').magnificPopup({
			delegate: 'a',
			type: 'image',
			gallery: {
				enabled: true
			}
		});
	};

	var faqs = function() {

		$('[data-action="faq"]').on('click', function(e) {
			e.preventDefault();

			$(this).toggleClass('open');
		});
	};


	var homeGallery = function() {
		
		$('.gallery-thumbs').on('click', '[data-action="thumb"]', function(e) {
			e.preventDefault();
			
			var thisGallery = $(this).parents('.gallery').find('.gallery-main');
			var thisLi = $(this).parents('li');
			// var thisHref = this.href;
			var thisHref = $(this).attr('href');
			var thisIndex = $(this).attr('data-index');
			// var thisSrc = $(this).find('img').attr('src');

			thisLi.addClass('vis');
			$('.gallery-thumbs').find('li').not(thisLi).removeClass('vis');
			thisGallery.find('a').attr('href', thisHref);
			thisGallery.find('a').attr('data-index', thisIndex);
			thisGallery.find('img').attr('src', thisHref);
		});

		$('.gallery-main').on('click', '[data-action="gallery"]', function(e) {
			e.preventDefault();

			var thisIndex = parseInt($(this).attr('data-index'));

			var items = $(this).parents('.gallery').find('.gallery-thumbs').find('a').map(function() {
				return {
					src: $(this).attr('href'),
					type: 'image'
				};
			}).get();

			$.magnificPopup.open({
				items: items,
				gallery: {
					enabled: true,
				},
				type: 'image'
			}, thisIndex);
		});

	};


	var headerCountdown = function() {

		var timerContainer = $('.countdown-timer');
		var daysEl = timerContainer.find('.days').find('.number');
		var hoursEl = timerContainer.find('.hrs').find('.number');
		var minsEl = timerContainer.find('.mins').find('.number');
		var secsEl = timerContainer.find('.secs').find('.number');

		var endDate = timerContainer.attr('data-enddate');
		var targetTime = new Date(endDate);
		var _second = 1000;
		var _minute = _second * 60;
		var _hour = _minute * 60;
		var _day = _hour * 24;
		var timer;

		// console.log(targetTime);

		function showRemaining() {
	        var now = new Date();
	        var distance = targetTime - now;
	      
	        if (distance < 0) {
	            clearInterval(timer);
	            // document.getElementById('countdown').innerHTML = 'EXPIRED!';
	            return;
	        }

	        var days = Math.floor(distance / _day);
	        var hours = Math.floor((distance % _day) / _hour);
	        var minutes = Math.floor((distance % _hour) / _minute);
	        var seconds = Math.floor((distance % _minute) / _second);

	        daysEl.html(days);
			hoursEl.html(hours);
			minsEl.html(minutes);
			secsEl.html(seconds);

	        // console.log(days + 'days ' + hours + 'hrs ' + minutes + 'mins ' + seconds + 'secs');
	    }

	    timer = setInterval(showRemaining, _minute);
	};

	
	var productDetail = function() {
		
		// $('[data-action="accordian-toggle"]').on('click', function(e) {
		// 	$(this).parents('.accordian-section').toggleClass('vis');
		// });
	 	
	 	var wrapQuantityInput = function() {
	        // Target the quantity input
	        var $quantityInput = $('input.qty:not(.buttons-added)');

	        $quantityInput.each(function() {
	            var $input = $(this);
	            // Only wrap if not already wrapped
	            if (!$input.parent().hasClass('custom-quantity-wrapper')) {
	                $input.wrap('<div class="custom-quantity-wrapper"></div>');
	                $input.before('<button type="button" class="quantity-minus"><i class="far fa-minus"><span>-</span></i></button>');
	                $input.after('<button type="button" class="quantity-plus"><i class="far fa-plus"><span>+</span></i></button>');
	                $input.addClass('buttons-added');
	            }
	        });
	    }

	    // Initial wrap
	    wrapQuantityInput();

	    // Re-wrap after variation changes (for variable products)
	    $(document).on('found_variation', 'form.variations_form', function() {
	        wrapQuantityInput();
	    });

	    // Handle plus button click
	    $(document).on('click', '.quantity-plus', function() {
	        var $input = $(this).siblings('input.qty');
	        var step = parseFloat($input.attr('step')) || 1;
	        var max = parseFloat($input.attr('max')) || Infinity;
	        var currentVal = parseFloat($input.val()) || 0;
	        var newVal = currentVal + step;

	        if (newVal <= max) {
	            $input.val(newVal).trigger('change');
	        }
	    });

	    // Handle minus button click
	    $(document).on('click', '.quantity-minus', function() {
	        var $input = $(this).siblings('input.qty');
	        var step = parseFloat($input.attr('step')) || 1;
	        var min = parseFloat($input.attr('min')) || 1;
	        var currentVal = parseFloat($input.val()) || 0;
	        var newVal = currentVal - step;

	        if (newVal >= min) {
	            $input.val(newVal).trigger('change');
	        }
	    });

		$('.woocommerce-product-gallery').magnificPopup({
			delegate: 'a',
			type: 'image',
			gallery: {
				enabled: true
			},
			removalDelay: 300,
			mainClass: 'mfp-fade'
		});
	};



	$(document).ready(function(){
		homeHeroSlider();
		heroVideo();
		scrollAnim();
		scrollToAnchor();
		blockGallery();
		homeGallery();
		faqs();
		headerCountdown();
		productDetail();
	});

    // window.addEventListener('load', function() {
    //     var videoContainer = $('.hero-video-container');
    //     videoContainer.addClass('vis');
    // }, false);

})(jQuery);

/* Lazy load bg images https://web.dev/lazy-loading-images/ */
// document.addEventListener("DOMContentLoaded", function() {
//   var lazyBackgrounds = [].slice.call(document.querySelectorAll(".lazy-background"));

//   if ("IntersectionObserver" in window) {
//     let lazyBackgroundObserver = new IntersectionObserver(function(entries, observer) {
//       entries.forEach(function(entry) {
//         if (entry.isIntersecting) {
//           entry.target.classList.add("visible");
//           lazyBackgroundObserver.unobserve(entry.target);
//         }
//       });
//     });

//     lazyBackgrounds.forEach(function(lazyBackground) {
//       lazyBackgroundObserver.observe(lazyBackground);
//     });
//   }
// });