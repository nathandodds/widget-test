define(['Backbone'], function(){

    return Backbone.View.extend({

        initialize: function(){

        },
        
        el: $('body'),
        
        events: {
            'click .js-toggle-nav'      : 'show_nav',
            'click .js-toggle-subnav'   : 'show_subnav'
        },

        show_nav: function(e) {

            $(e.target).parent().next().slideToggle('normal');

            e.preventDefault();
        },

        show_subnav: function(e) {

            if ($(window).width() > 569) {
                var nav = $('.nav');
                var subnav = $('.nav__sublinks');
                var nav_height = nav.height();
                var subnav_height = subnav.height();

                subnav.hide();

                $(e.target).next().slideToggle('normal');

                if (nav.css('position') == 'fixed') {
                    $('.content').animate({marginTop: nav_height + subnav_height + 'px'}, 500);
                } else {
                    $('.content').animate({marginTop: subnav_height + 'px'}, 500);
                }

            } else {
                $(e.target).next().slideToggle('normal');
            }

            e.preventDefault();
        }
    })
});