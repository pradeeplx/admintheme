$(window).scroll(function(){
    if ($(this).scrollTop() > 500) {
       $('.search-header-form').addClass('show');
       $('.popup-search').addClass('show');
    } else {
       $('.search-header-form').removeClass('show');
       $('.popup-search').removeClass('show');
    }
});


$(document).ready(function () {
    // Shoping Cart
    $('.header-login ul li a.cart-notify').click(function () {
        $('.add-to-cart-area').addClass('cart_show');
        return false;
    });
    $('.cartClose').click(function () {
        $('.add-to-cart-area').removeClass('cart_show');
        return false;
    });


    // Canvas menu
    $('.header-login ul li a.bars').click(function () {
        $('.canvas-menu-area').addClass('canvas-show');
        return false;
    });

    $('.canvas-menu-area .canvas-close').click(function () {
        $('.canvas-menu-area').removeClass('canvas-show');
        return false;
    });

    $(window).click(function () {
        $('.canvas-menu-area').removeClass('canvas-show');
    });


    // Promo Offer Show
    $('.promocode').click(function () {
        $('.promocode-area').addClass('promo-show');
        return false;
    });

    $('.promocode-area .promo-close').click(function () {
        $('.promocode-area').removeClass('promo-show');
        return false;
    });


    // Promocode Area
    $('.promocode-area .row').owlCarousel({
        loop: true,
        dots: false,
        nav: false,
        items: 4,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 3
            },
            992: {
                items: 4
            }
        }
    });

    // TESTIMONIAL
    $('.testimonial-area .testimonial').owlCarousel({
        loop: true,
        dots: true,
        nav: false,
        items: 3,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 2
            },
            992: {
                items: 3
            }
        }
    });

    // OFFER
    $('.offer-area .offer').owlCarousel({
        loop: true,
        dots: true,
        nav: true,
        items: 1,
        responsive: {
            0: {
                nav: false
            },
            768: {
                nav: true
            }
        }
    });


    // BLOG AREA
    $('.bolg-area .row').owlCarousel({
        loop: false,
        dots: false,
        nav: false,
        items: 3,
        responsive: {
            0: {
                items: 1,
                loop: true
            },
            768: {
                items: 2
            },
            992: {
                items: 3
            }
        }
    });



    /*========Cart Multi Step ===========*/
    $('#cart').click(function () {
        $('.cart-menu ul li').removeClass('active');
        $(this).addClass('active done');
        return false;
    });
    $('li#login').click(function () {
        $('.cart-menu ul li').removeClass('active');
        $(this).addClass('active done');
        return false;
    });
    $('li#pay').click(function () {
        $('.cart-menu ul li').removeClass('active');
        $(this).addClass('active done');
        return false;
    });
    $('li#booking').click(function () {
        $('.cart-menu ul li').removeClass('active');
        $(this).addClass('active done');
        return false;
    });

    $('#login-tap').click(function () {
        $('.staps').hide();
        $('.login-steps').show();
        $('.cart-menu ul li').removeClass('active');
        $('#login').addClass('active done');
        return false;
    });

    $('#logincheck').click(function () {
        $('.staps').hide();
        $('.user-login-checkout').show();
        return false;
    });

    $('#signupcheck').click(function () {
        $('.staps').hide();
        $('.user-signup-checkout').show();
        return false;
    });

    $('#guestSignup').click(function () {
        $('.staps').hide();
        $('.user-guest-checkout').show();
        return false;
    });

    $('#confirmCheck').click(function () {
        $('.staps').hide();
        $('.payment-steps').show();
        $('.cart-menu ul li').removeClass('active');
        $('#pay').addClass('active done');
        return false;
    });

    $('#paymentDone').click(function () {
        $('.staps').hide();
        $('.booking-status').show();
        $('.cart-menu ul li').removeClass('active');
        $('#booking').addClass('active done');
        return false;
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

    /*======== Profile Page ===========*/
    $('.profile-tab-menu ul li a').click(function () {
        $('.profile-tab-menu ul li').removeClass('active');
        $(this).parent('li').addClass('active');
        return false;
    });

    $('#myPro').click(function () {
        $('.pro-content').hide();
        $('.myProfileContent.pro-content').fadeIn();
    });
    $('#myAddr').click(function () {
        $('.pro-content').hide();
        $('.saveContentArea.pro-content').fadeIn();
    });
    $('#myOrd').click(function () {
        $('.pro-content').hide();
        $('.history-content-area.pro-content').fadeIn();
    });

    /*========Login Page ===========*/

    $('#si-btn').click(function () {
        $('.login-tabs-area button').removeClass('active');
        $(this).addClass('active');
        $('.singup-tab-content').hide();
        $('.login-tab-content').show();
    });
    $('#su-btn').click(function () {
        $('.login-tabs-area button').removeClass('active');
        $(this).addClass('active');
        $('.login-tab-content').hide();
        $('.singup-tab-content').show();
    });

    // About Journey
    $('.journey-tab .row.responsive-caro').owlCarousel({
        loop: true,
        dots: true,
        nav: true,
        items: 1
    });



    // Lipid Profile Tab
    $('.lipid-profile-tab ul li').click(function () {
        $('.lipid-profile-tab ul li').removeClass('active');
        $(this).addClass('active');
        return false;
    });

    $('.lipid-profile-tab ul li#patient').click(function () {
        $('.lipid-profile-content').hide();
        $('.patients').fadeIn(1000);
        return false;
    });

    $('.lipid-profile-tab ul li#doctor').click(function () {
        $('.lipid-profile-content').hide();
        $('.doctors').fadeIn(1000);
        return false;
    });

    // Date PICKER
    $('#DOB').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $('#preference').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    // Show More Details

    

    // Show More Details


    // Team Member Area
    $('.team-view').readmore({
        speed: 100,
        moreLink: '<a class="view-all" href="#">View Full Team</a>',
        lessLink: '<a class="less-all" href="#">View Less</a>',
        collapsedHeight: 270,
        afterToggle: function (trigger, element, expanded) {
            if (!expanded) { // The "Close" link was clicked
                $('html, body').animate({
                    scrollTop: element.offset().top
                });
            }
        }
    });





});