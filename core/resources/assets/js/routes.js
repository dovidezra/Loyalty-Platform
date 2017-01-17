$(function() {
  if (window.location.pathname == '/platform') {
    
    /*
     * Called with every route change
     */

    var allRoutes = function() {
      //
    };

    /*
     * Called before every route change
     */

    var beforeRoute = function() {
      // Loader
      $('#view').html('<div class="container"><div class="row"><div class="col-xs-12"><div class="loader" id="throbber"></div></div></div>');
      $('#throbber').css('margin', (parseInt($(window).outerHeight()) / 2) - 115 + 'px auto 0 auto');
    };

    /*
     * Called after every route change
     */

    var afterRoute = function() {
      $('.navigation-menu li').removeClass('active');

      initNavbarMenuActive();
    };

    /*
     * Routes
     */

    var router = Router({
      '/': function () { loadRoute('platform/dashboard'); },
      '/media': function () { loadRoute('platform/media/browser'); },
      '/profile': function () { loadRoute('platform/profile'); },

      '/coupon/new': function () { loadRoute('platform/coupon/new'); },
      '/coupon/edit': function () { loadRoute('platform/coupon/edit'); },
      '/coupon/edit/:sl': function (sl) { loadRoute('platform/coupon/edit?sl=' + sl); },

      '/analytics': function () { loadRoute('platform/analytics'); },
      '/analytics/:sl': function (sl) { loadRoute('platform/analytics?sl=' + sl); },
      '/analytics/:start/:end': function (start, end) { loadRoute('platform/analytics?start=' + start + '&end=' + end); },
      '/analytics/:start/:end/:sl': function (start, end, sl) { loadRoute('platform/analytics?sl=' + sl + '&start=' + start + '&end=' + end); },

      '/members': function () { loadRoute('platform/members'); },
      '/member/:sl': function (sl) { loadRoute('platform/member/edit?sl=' + sl); },

      '/admin/users': function () { loadRoute('platform/admin/users'); },
      '/admin/user': function () { loadRoute('platform/admin/user/new'); },
      '/admin/user/:sl': function (sl) { loadRoute('platform/admin/user/edit?sl=' + sl); }
    });

    /*
     * Route configuration
     */

    router.configure({
      on: allRoutes,
      before: beforeRoute,
      after: afterRoute
    });

    router.init('#/');

    function loadRoute(url) {
      $('#view').load(url, function() {
        onPartialLoaded();
      });
    }
  }
});