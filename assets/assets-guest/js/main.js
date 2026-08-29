/**
* Template Name: Green - v2.3.1
* Template URL: https://bootstrapmade.com/green-free-one-page-bootstrap-template/
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/
!(function($) {
  "use strict";

  // Smooth scroll for the navigation menu and links with .scrollto classes
  var scrolltoOffset = $('#header').outerHeight() - 1;
  $(document).on('click', '.nav-list a, .scrollto', function(e) {
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
      var target = $(this.hash);
      if (target.length) {
        e.preventDefault();

        var scrollto = target.offset().top - scrolltoOffset;

        if ($(this).attr("href") == '#header') {
          scrollto = 0;
        }

        $('html, body').animate({
          scrollTop: scrollto
        }, 1500, 'easeInOutExpo');

        if ($(this).parents('.nav-list').length) {
          $('.nav-list .active').removeClass('active');
          $(this).closest('li').addClass('active');
        }

        if ($('body').hasClass('mobile-nav-active')) {
          $('body').removeClass('mobile-nav-active');
          $('.mobile-nav-toggle').attr('aria-expanded', 'false');
        }
        return false;
      }
    }
  });


  // Activate smooth scroll on page load with hash links in the url
  $(document).ready(function() {
    if (window.location.hash) {
      var initial_nav = window.location.hash;
      if ($(initial_nav).length) {
        var scrollto = $(initial_nav).offset().top - scrolltoOffset;
        $('html, body').animate({
          scrollTop: scrollto
        }, 1500, 'easeInOutExpo');
      }
    }
  });

  // Mobile Navigation
  function setMobileNavOpen(isOpen) {
    $('body').toggleClass('mobile-nav-active', isOpen);
    $('.mobile-nav-toggle')
      .attr('aria-expanded', isOpen)
      .attr('aria-label', isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');
  }

  $(document).on('click', '.mobile-nav-toggle', function(e) {
    e.stopPropagation();
    setMobileNavOpen(!$('body').hasClass('mobile-nav-active'));
  });

  // Close mobile nav when clicking on overlay
  $(document).on('click', '#mobileNavOverlay', function() {
    setMobileNavOpen(false);
  });

  // Close mobile nav when clicking outside
  $(document).on('click', function(e) {
    if ($('body').hasClass('mobile-nav-active')) {
      var $target = $(e.target);
      if (!$target.closest('.nav-list, .mobile-nav-toggle').length) {
        setMobileNavOpen(false);
      }
    }
  });

  // Close mobile nav when a nav link is clicked
  $(document).on('click', '.nav-list a', function() {
    if ($('body').hasClass('mobile-nav-active')) {
      setMobileNavOpen(false);
    }
  });

  // Close mobile nav on Escape key, returning focus to the toggle button
  $(document).on('keydown', function(e) {
    if (e.key === 'Escape' && $('body').hasClass('mobile-nav-active')) {
      setMobileNavOpen(false);
      $('.mobile-nav-toggle').trigger('focus');
    }
  });

  // Note: this used to also toggle .active on .nav-menu/.mobile-nav items
  // based on scroll position, matching in-page #anchor sections. SILARIS's
  // nav links are real page URLs (not #anchors), so that logic never
  // matched anything useful and its "force the first item active near the
  // top of the page" fallback was actively overriding the server-rendered
  // active state (application/views/include/header.php) on every scroll.

  // Toggle .header-scrolled class to #header when page is scrolled
  $(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
      $('#header').addClass('header-scrolled');
      $('#topbar').addClass('topbar-scrolled');
    } else {
      $('#header').removeClass('header-scrolled');
      $('#topbar').removeClass('topbar-scrolled');
    }
  });

  if ($(window).scrollTop() > 100) {
    $('#header').addClass('header-scrolled');
    $('#topbar').addClass('topbar-scrolled');
  }

  // Intro carousel
  var heroCarousel = $("#heroCarousel");
  var heroCarouselIndicators = $("#hero-carousel-indicators");
  heroCarousel.find(".carousel-inner").children(".carousel-item").each(function(index) {
    (index === 0) ?
    heroCarouselIndicators.append("<li data-target='#heroCarousel' data-slide-to='" + index + "' class='active'></li>"):
      heroCarouselIndicators.append("<li data-target='#heroCarousel' data-slide-to='" + index + "'></li>");
  });

  heroCarousel.on('slid.bs.carousel', function(e) {
    $(this).find('h2').addClass('animate__animated animate__fadeInDown');
    $(this).find('p, .btn-get-started').addClass('animate__animated animate__fadeInUp');
  });

  // Back to top button
  $(window).scroll(function() {
    if ($(this).scrollTop() > 100) {
      $('.back-to-top').fadeIn('slow');
    } else {
      $('.back-to-top').fadeOut('slow');
    }
  });

  $('.back-to-top').click(function() {
    $('html, body').animate({
      scrollTop: 0
    }, 1500, 'easeInOutExpo');
    return false;
  });

  // Clients carousel (uses the Owl Carousel library)
  $(".clients-carousel").owlCarousel({
    autoplay: true,
    dots: true,
    loop: true,
    responsive: {
      0: {
        items: 2
      },
      768: {
        items: 4
      },
      900: {
        items: 6
      }
    }
  });

  // Portfolio details carousel
  $(".portfolio-details-carousel").owlCarousel({
    autoplay: true,
    dots: true,
    loop: true,
    items: 1
  });

})(jQuery);