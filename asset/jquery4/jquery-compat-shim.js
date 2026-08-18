/**
 * A handful of jQuery 3.x utilities were never public API but got used
 * directly anyway by third-party plugins bundled in this project (Owl
 * Carousel, jQuery UI, jquery-switch-button, jquery.inputmask,
 * jquery.datetimepicker, fancyBox). jQuery 4.0 removed all of them,
 * which throws inside those plugins' init code and leaves them
 * silently non-functional (no console-visible error on the page itself
 * in most cases, since the throw happens inside a jQuery plugin
 * wrapper). Restore each one exactly as jQuery 3.x implemented it.
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

  if (typeof $.isFunction !== 'function') {
    $.isFunction = function (obj) {
      return typeof obj === 'function';
    };
  }

  if (typeof $.isArray !== 'function') {
    $.isArray = Array.isArray;
  }

  if (typeof $.isWindow !== 'function') {
    $.isWindow = function (obj) {
      return obj != null && obj === obj.window;
    };
  }

  if (typeof $.trim !== 'function') {
    $.trim = function (text) {
      return text == null ? '' : (text + '').trim();
    };
  }

  if (typeof $.parseJSON !== 'function') {
    $.parseJSON = JSON.parse;
  }

  /**
   * Bootstrap 3/4's jQuery plugins ($(...).tooltip()/.popover()/.modal())
   * don't exist in Bootstrap 5 - it's vanilla JS classes instead
   * (bootstrap.Tooltip, bootstrap.Modal, ...). custom.js calls
   * $(...).tooltip() unconditionally on every page load as the first
   * statement inside $(document).ready(), so without a bridge that
   * throws and silently aborts every statement after it in the same
   * handler (toastr setup, fancybox init, iCheck bindings, tab
   * animation). bootstrap.bundle.min.js loads after this shim, so
   * these only touch window.bootstrap lazily, at call time.
   */
  if (typeof $.fn.tooltip !== 'function') {
    $.fn.tooltip = function (options) {
      return this.each(function () {
        if (!window.bootstrap || !window.bootstrap.Tooltip) {
          return;
        }
        if (!window.bootstrap.Tooltip.getInstance(this)) {
          new window.bootstrap.Tooltip(this, typeof options === 'object' ? options : {});
        }
      });
    };
  }
  if (typeof $.fn.popover !== 'function') {
    $.fn.popover = function (options) {
      return this.each(function () {
        if (!window.bootstrap || !window.bootstrap.Popover) {
          return;
        }
        if (!window.bootstrap.Popover.getInstance(this)) {
          new window.bootstrap.Popover(this, typeof options === 'object' ? options : {});
        }
      });
    };
  }
  if (typeof $.fn.modal !== 'function') {
    $.fn.modal = function (action) {
      return this.each(function () {
        if (!window.bootstrap || !window.bootstrap.Modal) {
          return;
        }
        var instance = window.bootstrap.Modal.getOrCreateInstance(this);
        if (action === 'show' || action === 'hide' || action === 'toggle' || action === 'dispose') {
          instance[action]();
        }
      });
    };
  }

  /**
   * Bootstrap 5 only recognizes data-bs-toggle/data-bs-target/etc, not
   * the old Bootstrap 3/4 data-toggle/data-target/data-dismiss this
   * project's views (and JS-generated markup like cc-page-element.js's
   * tab links) still use throughout. Mirroring the old attributes onto
   * their bs-prefixed equivalents lets Bootstrap 5's own JS drive them
   * without having to track down every occurrence individually.
   */
  function mirrorBsAttrs(root) {
    var attrs = ['toggle', 'target', 'dismiss', 'parent', 'ride', 'slide', 'slide-to', 'spy'];
    var scope = root && root.querySelectorAll ? root : document;
    attrs.forEach(function (name) {
      var nodes = scope.querySelectorAll('[data-' + name + ']:not([data-bs-' + name + '])');
      for (var i = 0; i < nodes.length; i++) {
        nodes[i].setAttribute('data-bs-' + name, nodes[i].getAttribute('data-' + name));
      }
    });
  }
  mirrorBsAttrs(document);
  if (typeof MutationObserver === 'function') {
    new MutationObserver(function (mutations) {
      for (var i = 0; i < mutations.length; i++) {
        for (var j = 0; j < mutations[i].addedNodes.length; j++) {
          var node = mutations[i].addedNodes[j];
          if (node.nodeType === 1) {
            mirrorBsAttrs(node);
          }
        }
      }
    }).observe(document.documentElement, { childList: true, subtree: true });
  }
})(window.jQuery);
