requirejs.config({
    paths: {
        Backbone: '../utils/backbone',
        jquery: '../utils/jquery',
        bxslider: '../utils/jquery.bxslider'
    },
    shim: {
        'Backbone': {
            deps: ['../utils/lodash', 'jquery'], // load dependencies
            exports: 'Backbone' // use the global 'Backbone' as the module value
        }
    }
});

require(['../views/Validation','../views/MobileNav','carousel'], function(Validation,MobileNav) {

    var validate = new Validation(),
        MobileNav = new MobileNav();

});

