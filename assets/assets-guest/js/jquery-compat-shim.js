/**
 * jQuery.camelCase() and jQuery.type() were internal utilities in
 * jQuery 3.x that Owl Carousel 2.3.4 calls directly even though
 * neither was ever public API. jQuery 4.0 dropped both, which throws
 * inside Owl Carousel's init and silently leaves it un-initialized
 * (owl-carousel's CSS keeps the element at display:none until Owl's
 * JS adds the "owl-loaded" class). Restore both exactly as jQuery
 * 3.x implemented them.
 */
(function ($) {
  if (!$) {
    return;
  }

  if (typeof $.camelCase !== 'function') {
    var rmsPrefix = /^-ms-/;
    var rdashAlpha = /-([a-z])/g;
    $.camelCase = function (string) {
      return string
        .replace(rmsPrefix, 'ms-')
        .replace(rdashAlpha, function (all, letter) {
          return letter.toUpperCase();
        });
    };
  }

  if (typeof $.type !== 'function') {
    var class2type = {};
    var toString = class2type.toString;
    'Boolean Number String Function Array Date RegExp Object Error Symbol'
      .split(' ')
      .forEach(function (name) {
        class2type['[object ' + name + ']'] = name.toLowerCase();
      });
    $.type = function (obj) {
      if (obj == null) {
        return obj + '';
      }
      return typeof obj === 'object' || typeof obj === 'function'
        ? class2type[toString.call(obj)] || 'object'
        : typeof obj;
    };
  }
})(window.jQuery);
