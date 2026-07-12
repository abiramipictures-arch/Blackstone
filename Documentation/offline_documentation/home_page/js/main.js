;(function ($) {
	"use strict";

	//*=============menu sticky js =============*//
	var $window = $(window);
	var didScroll,
		lastScrollTop = 0,
		delta = 5,
		$mainNav = $("#sticky"),
		$mainNavHeight = $mainNav.outerHeight(),
		scrollTop;

	$window.on("scroll", function () {
		didScroll = true;
		scrollTop = $(this).scrollTop();
	});

	setInterval(function () {
		if (didScroll) {
			hasScrolled();
			didScroll = false;
		}
	}, 200);

	function hasScrolled() {
		if (Math.abs(lastScrollTop - scrollTop) <= delta) {
			return;
		}
		if (scrollTop > lastScrollTop && scrollTop > $mainNavHeight) {
			$mainNav.removeClass("fadeInDown").addClass("fadeInUp").css('top', -$mainNavHeight);
		} else {
			if (scrollTop + $(window).height() < $(document).height()) {
				$mainNav.removeClass("fadeInUp").addClass("fadeInDown").css('top', 0);
			}
		}
		lastScrollTop = scrollTop;
	}

	function navbarFixed() {
		if ($('#sticky').length) {
			$(window).scroll(function () {
				var scroll = $(window).scrollTop();
				if (scroll) {
					$("#sticky").addClass("navbar_fixed");
					$(".sticky-nav-doc .body_fixed").addClass("body_navbar_fixed");
				} else {
					$("#sticky").removeClass("navbar_fixed");
					$(".sticky-nav-doc .body_fixed").removeClass("body_navbar_fixed");
				}
			});
		};
	};
	navbarFixed();

	function navbarFixedTwo() {
		if ($('#stickyTwo').length) {
			$(window).scroll(function () {
				var scroll = $(window).scrollTop();
				if (scroll) {
					$("#stickyTwo").addClass("navbar_fixed");
				} else {
					$("#stickyTwo").removeClass("navbar_fixed");
				}
			});
		};
	};
	navbarFixedTwo();

	/*  Menu Click js  */
	function Menu_js() {
		if ($('.submenu').length) {
			$('.submenu > .dropdown-toggle').click(function () {
				var location = $(this).attr('href');
				window.location.href = location;
				return false;
			});
		}
	}
	Menu_js();

	$('.doc_menu a[href^="#"]:not([href="#"]').on('click', function (event) {
		var $anchor = $(this);
		$('html, body').stop().animate({
			scrollTop: $($anchor.attr('href')).offset().top
		}, 900);
		event.preventDefault();
	});

	$(window).on("load", function () {
		if ($('.scroll').length) {
			$(".scroll").mCustomScrollbar({
				mouseWheelPixels: 50,
				scrollInertia: 0,
			});
		}
	});

	/*--------------- nav-sidebar js--------*/
	if ($('.nav-sidebar > li').hasClass('active')) {
		$(".nav-sidebar > li.active").find('ul').slideDown(700);
	}
	function active_dropdown() {
		$('.nav-sidebar > li .icon').on('click', function (e) {
			$(this).parent().find('ul').first().toggle(300);
			$(this).parent().siblings().find('ul').hide(300);
		});
	}
	active_dropdown();

	$('.nav-sidebar > li .icon').each(function () {
		var $this = $(this);
		$this.on('click', function (e) {
			var has = $this.parent().hasClass('active');
			$('.nav-sidebar li').removeClass('active');
			if (has) {
				$this.parent().removeClass('active');
			} else {
				$this.parent().addClass('active');
			}
		});
	});

	/*--------- WOW js-----------*/
	function bodyScrollAnimation() {
		var scrollAnimate = $('body').data('scroll-animation');
		if (scrollAnimate === true) {
			new WOW({}).init()
		}
	}
	bodyScrollAnimation();

	/*-------------------------------------
	Intersection Observer
	-------------------------------------*/
	if (!!window.IntersectionObserver) {
		let observer = new IntersectionObserver((entries, observer) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					entry.target.classList.add("active-animation");
					//entry.target.src = entry.target.dataset.src;
					observer.unobserve(entry.target);
				}
			});
		}, {
			rootMargin: "0px 0px -100px 0px"
		});
		document.querySelectorAll('.has-animation').forEach(block => {
			observer.observe(block)
		});
	} else {
		document.querySelectorAll('.has-animation').forEach(block => {
			block.classList.remove('has-animation')
		});
	}

    // === Back to Top Button
	var back_top_btn = $('#back-to-top');
	$(window).scroll(function() {
		if ($(window).scrollTop() > 300) {
			back_top_btn.addClass('show');
		} else {
			back_top_btn.removeClass('show');
		}
	});
	back_top_btn.on('click', function(e) {
		e.preventDefault();
		$('html, body').animate({scrollTop:0}, '300');
	});
})(jQuery);