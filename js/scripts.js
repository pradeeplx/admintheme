jQuery(window).scroll(function($){
	var fromTop = jQuery(window).scrollTop();
    if ( fromTop  > 500) {
       jQuery('.search-header-form').addClass('show');
    } else {
       jQuery('.search-header-form').removeClass('show');
    }
});

jQuery(document).ready(function($){

  //about page legacy menu navigator js
     $(".legacy-menu li a").on('click', function(event) {
        if (this.hash !== "") {
            event.preventDefault();
            $(".legacy-menu li").removeClass('activated');
            $(this).parent().addClass('activated');
            var hash = this.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset().top - 232
            }, 800, function(){
                window.location.hash = hash - 232;
            });
        }
    });
    // SEarch Hide
    $('button.popup-search').click(function () {
        $('.hiding-search').addClass('search-show');
        return false;
    });
    $('.hiding-search-form .search-close').click(function () {
        $('.hiding-search').removeClass('search-show');
        return false;
    });
    // SEarch Hide

    // Date PICKER
    $('#DOB').datepicker({
      dateFormat: 'yy-mm-dd'
    });

    $('#preference').datepicker({
      dateFormat: 'yy-mm-dd'
    });



    /*========Cart Multi Step ===========*/
    $('#cart').click(function(){
    $('.cart-menu ul li').removeClass('active');
    $(this).addClass('active done');
    return false;
  });
  $('li#login').click(function(){
    $('.cart-menu ul li').removeClass('active');
    $(this).addClass('active done');
    return false;
  });
  $('li#pay').click(function(){
    $('.cart-menu ul li').removeClass('active');
    $(this).addClass('active done');
    return false;
  });
  $('li#booking').click(function(){
    $('.cart-menu ul li').removeClass('active');
    $(this).addClass('active done');
    return false;
  });

  
 $('#login-tap').click(function(){
    $('.staps').hide();
    $('.login-steps').show();
	$('.cart-menu ul li').removeClass('active');
    $('#login').addClass('active done');
    return false;
  });

 $('#logincheck').click(function(){
    $('.staps').hide();
    $('.user-login-checkout').show();
    return false;
  });

 $('#signupcheck').click(function(){
    $('.staps').hide();
    $('.user-signup-checkout').show();
    return false;
  });

 $('#guestSignup').click(function(){
    $('.staps').hide();
    $('.user-guest-checkout').show();
    return false;
  });

 $('#confirmCheck').click(function(){
    $('.staps').hide();
    $('.payment-steps').show();
	$('.cart-menu ul li').removeClass('active');
    $('#pay').addClass('active done');
    return false;
  });

 $('#paymentDone').click(function(){
    $('.staps').hide();
    $('.booking-status').show();
	$('.cart-menu ul li').removeClass('active');
    $('#booking').addClass('active done');
    return false;
  });




    /*========Login Page ===========*/
    
  $('#si-btn').click(function(){
    $('.login-tabs-area button').removeClass('active');
    $(this).addClass('active');
    $('.singup-tab-content').hide();
    $('.login-tab-content').show();
  });
  $('#su-btn').click(function(){
    $('.login-tabs-area button').removeClass('active');
    $(this).addClass('active');
    $('.login-tab-content').hide();
    $('.singup-tab-content').show();
  });


  /*======== Profile Page ===========*/
  $('.profile-tab-menu ul li a').click(function(){
    $('.profile-tab-menu ul li').removeClass('active');
    $(this).parent('li').addClass('active');
    return false;
  });

  $('#myPro').click(function(){
    $('.pro-content').hide();
    $('.myProfileContent.pro-content').fadeIn();
  });
   $('#myAddr').click(function(){
    $('.pro-content').hide();
    $('.saveContentArea.pro-content').fadeIn();
  });
  $('#myOrd').click(function(){
    $('.pro-content').hide();
    $('.history-content-area.pro-content').fadeIn();
  });
  


    /*========Search Box ===========*/

  //   $(window).click(function(){
  //     $('.search-form2 form').removeClass('hide');
  // });
  // $('.search-form2 form button').click(function(){
  //     $('.search-form2 form').addClass('hide');
  //     return false;
  // });

    // Shoping Cart
    $('.header-login ul li a.cart-notify').click(function(){
      $('.add-to-cart-area').fadeToggle(500);
      return false;
    });

    // OFFER
    $('.offer').owlCarousel({
      loop: true,
      dots:true,
      nav:true,
      items:1,
      responsive:{
        0:{
          nav:false
        },
        768:{
          nav:true
        },
      }
    });

    // TESTIMONIAL
    $('.testimonial').owlCarousel({
      loop: true,
      dots: true,
      nav: false,
      items:3,
      responsive:{
        0:{
          items:1
        },
        768:{
          items:2
        },
        992:{
          items:3
        },
      }
    });

    // Lipid Profile Tab
    $('.lipid-profile-tab ul li').click(function(){
      $('.lipid-profile-tab ul li').removeClass('active');
      $(this).addClass('active');
      return false;
  });

    $('.lipid-profile-tab ul li#patient').click(function(){
      $('.lipid-profile-content').hide();
      $('.patients').fadeIn(1000);
      return false;
  });

    $('.lipid-profile-tab ul li#doctor').click(function(){
      $('.lipid-profile-content').hide();
      $('.doctors').fadeIn(1000);
      return false;
  });


    // Canvas menu
    $('.header-login ul li a.bars').click(function(){
      $('.canvas-menu-area').addClass('canvas-show');
      $('.canvas-overlay').addClass('show-overlay');
      return false;
  });

    $('.canvas-menu-area .canvas-close, .canvas-overlay').click(function(){
      $('.canvas-menu-area').removeClass('canvas-show');
       $('.canvas-overlay').removeClass('show-overlay');
      return false;
  });

  //   $(window).click(function(){
  //     $('.canvas-menu-area').removeClass('canvas-show');
  // });


    // Promo Offer Show
    $('.promocode').click(function(){
      $('.promocode-area').addClass('promo-show');
      return false;
  });

    $('.promocode-area .promo-close').click(function(){
      $('.promocode-area').removeClass('promo-show');
      return false;
  });


  $('.show-more-details').readmore({
    speed: 1000,
    moreLink: '<a class="show-more" href="#">Show More <i class="fas fa-angle-down"></i></a>',
    lessLink: '<a class="less-more" href="#">Less More <i class="fas fa-angle-up"></i></a>',
    collapsedHeight: 525,
    afterToggle: function(trigger, element, expanded) {
      if(! expanded) { // The "Close" link was clicked
        $('html, body').animate( { scrollTop: element.offset().top }, {duration: 10 } );
      }
    }
  });

  $('.show-more-details2').readmore({
    speed: 1000,
    moreLink: '<a class="show-more" href="#">Show More <i class="fas fa-angle-down"></i></a>',
    lessLink: '<a class="less-more" href="#">Less More <i class="fas fa-angle-up"></i></a>',
    collapsedHeight: 720,
    afterToggle: function(trigger, element, expanded) {
      if(! expanded) { // The "Close" link was clicked
        $('html, body').animate( { scrollTop: element.offset().top }, {duration: 10 } );
      }
    }
  });
  // Team Member Area
  $('.team-view').readmore({
    speed: 100,
    moreLink: '<a class="view-all" href="#">View Full Team</a>',
    lessLink: '<a class="less-all" href="#">View Less</a>',
    collapsedHeight: 270,
    afterToggle: function(trigger, element, expanded) {
      if(! expanded) { // The "Close" link was clicked
        $('html, body').animate( { scrollTop: element.offset().top } );
      }
    }
  });


  // $('.single-testimonial p').readmore({
  //   speed: 1000,
  //   moreLink: '<a class="show-more" href="#">Show More <i class="fas fa-angle-down"></i></a>',
  //   lessLink: '<a class="less-more" href="#">Less More <i class="fas fa-angle-up"></i></a>',
  //   collapsedHeight: 100
  // });
    
    

   /*======================================= 
                 Sticky Header
   =======================================*/

 $(window).scroll(function () {
    if ($(window).scrollTop() >= 600) {
        $('.header-area').addClass('fixed-header');
    } else {
        $('.header-area').removeClass('fixed-header');
    }
});

    /*========= Smooth Scroll =========*/ 

  $(".menu-area ul li a").on('click', function(event) {

    if (this.hash !== "") {

      event.preventDefault();

      var hash = this.hash;
      var nav = $( '.header-area' ).outerHeight();

      $('html, body').animate({
        scrollTop: $(hash).offset().top - nav
      }, 2000)
    }});

  // Active link switching
  $(window).scroll(function() {
    var scrollbarLocation = $(this).scrollTop();
    
    $('.menu-area ul li a').each(function() {
      
      var sectionOffset = jQuery(this.hash).offset().top -500;
      
      if ( sectionOffset <= scrollbarLocation ) {
        $(this).parent().addClass('active');
        $(this).parent().siblings().removeClass('active');
      }
    })
    
  });
  
});


//*********************************************//
//*********** JQuery Load Function ************//                 
//********************************************* //   
              
jQuery(window).on('load', function ($) {
  'use strict';
  
  //===== Page Loader =====//
  jQuery('.preloader').fadeOut('slow');

});