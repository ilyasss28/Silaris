(function () {
  'use strict';

  var contentSelector = '.app-main .app-content .container-fluid';
  var requestController = null;
  var currentUrl = window.location.href;
  var blockedPath = /\/(?:logout|delete|remove|export|download|activation|deactivation|save|add_save|edit_save)(?:\/|$)/i;
  var legacyScript = /\/(?:ckeditor|fine-upload|ace-master|json-editor)\//i;
  var legacyPagePath = /\/administrator\/(?:crud\/(?:add|edit)|rest\/(?:add|edit|tool)|(?:user|group|permission|blog|menu)\/(?:add|edit|edit_profile)|menu_type\/add)(?:\/|$)/i;

  function sameDocumentUrl(url) {
    return url.origin === window.location.origin && url.pathname === window.location.pathname && url.search === window.location.search;
  }

  function canNavigate(anchor, event) {
    if (!anchor || event.defaultPrevented || event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
      return false;
    }

    if (anchor.target && anchor.target !== '_self') return false;
    if (anchor.hasAttribute('download') || anchor.hasAttribute('data-no-ajax')) return false;
    if (anchor.classList.contains('remove-data') || anchor.classList.contains('fancybox')) return false;
    if (anchor.getAttribute('href') === '#' || anchor.getAttribute('href').indexOf('javascript:') === 0) return false;
    if (anchor.hasAttribute('data-bs-toggle') || anchor.hasAttribute('data-lte-toggle')) return false;

    var url;
    try {
      url = new URL(anchor.href, window.location.href);
    } catch (error) {
      return false;
    }

    return url.origin === window.location.origin &&
      !blockedPath.test(url.pathname) &&
      !legacyPagePath.test(url.pathname) &&
      !sameDocumentUrl(url);
  }

  function setLoading(loading) {
    document.body.classList.toggle('admin-navigating', loading);
    document.querySelector('.app-main')?.setAttribute('aria-busy', loading ? 'true' : 'false');
  }

  function updateCsrf(documentNode) {
    var name = documentNode.querySelector('meta[name="csrf-name"]')?.content;
    var value = documentNode.querySelector('meta[name="csrf-token"]')?.content;

    if (!name || !value) return;

    document.querySelector('meta[name="csrf-name"]').content = name;
    document.querySelector('meta[name="csrf-token"]').content = value;
    window.csrf = name;
    window.token = value;
  }

  function normalizePath(path) {
    var normalized = (path || '/').replace(/\/{2,}/g, '/').replace(/\/+$/, '') || '/';
    return normalized.toLowerCase();
  }

  function sidebarTargetPaths(url) {
    var targetPath = normalizePath(url.pathname);
    var paths = [targetPath];

    // Profil akun memakai controller tersendiri, tetapi secara navigasi tetap
    // berada di dalam manajemen pengguna.
    if (/^\/administrator\/profile(?:\/|$)/.test(targetPath)) {
      paths.push('/administrator/user');
    }

    return paths;
  }

  function updateSidebar(url) {
    var targetPaths = sidebarTargetPaths(url);
    var links = Array.from(document.querySelectorAll('.sidebar-menu a.nav-link[href]'));
    var bestMatch = null;

    document.querySelectorAll('.sidebar-menu .nav-item').forEach(function (item) {
      item.classList.remove('active', 'menu-open');
    });

    links.forEach(function (link) {
      link.classList.remove('active');
      link.removeAttribute('aria-current');

      var rawHref = (link.getAttribute('href') || '').trim();
      if (!rawHref || rawHref === '#' || /^javascript:/i.test(rawHref)) return;

      var linkUrl;
      try {
        linkUrl = new URL(link.href, window.location.href);
      } catch (error) {
        return;
      }
      var linkPath = normalizePath(linkUrl.pathname);
      if (linkUrl.origin !== window.location.origin || linkPath === '/') return;

      var exact = targetPaths.some(function (targetPath) {
        return targetPath === linkPath;
      });
      var matches = exact || targetPaths.some(function (targetPath) {
        return targetPath.indexOf(linkPath + '/') === 0;
      });
      var score = (exact ? 10000 : 0) + linkPath.length;

      if (matches && (!bestMatch || score > bestMatch.score)) {
        bestMatch = { link: link, path: linkPath, score: score };
      }
    });

    if (!bestMatch) return;

    bestMatch.link.classList.add('active');
    bestMatch.link.setAttribute('aria-current', 'page');
    var parent = bestMatch.link.closest('.nav-treeview');
    while (parent) {
      var item = parent.closest('.nav-item');
      if (!item) break;
      item.classList.add('menu-open');
      parent = item.parentElement.closest('.nav-treeview');
    }
  }

  function syncPageResources(sourceDocument) {
    document.querySelectorAll('[data-ajax-page-style]').forEach(function (node) {
      node.remove();
    });

    sourceDocument.querySelectorAll(contentSelector + ' link[rel="stylesheet"]').forEach(function (link) {
      var href = new URL(link.getAttribute('href'), window.location.href).href;
      var alreadyLoaded = Array.from(document.head.querySelectorAll('link[rel="stylesheet"]')).some(function (currentLink) {
        return currentLink.href === href;
      });
      if (alreadyLoaded) return;

      var clone = document.createElement('link');
      clone.rel = 'stylesheet';
      clone.href = href;
      clone.setAttribute('data-ajax-page-style', 'true');
      document.head.appendChild(clone);
    });
  }

  function requiresFullReload(content) {
    return Array.from(content.querySelectorAll('script[src]')).some(function (script) {
      return legacyScript.test(new URL(script.getAttribute('src'), window.location.href).pathname);
    });
  }

  function runPageScripts(sourceContent) {
    var scripts = Array.from(sourceContent.querySelectorAll('script'));
    var loadedSources = new Set(Array.from(document.scripts).map(function (script) { return script.src; }).filter(Boolean));
    var sequence = Promise.resolve();

    scripts.forEach(function (sourceScript) {
      sourceScript.remove();
      sequence = sequence.then(function () {
        return new Promise(function (resolve, reject) {
          var script = document.createElement('script');

          if (sourceScript.src) {
            var src = new URL(sourceScript.getAttribute('src'), window.location.href).href;
            if (loadedSources.has(src)) {
              resolve();
              return;
            }
            loadedSources.add(src);
            script.src = src;
            script.onload = resolve;
            script.onerror = function () { reject(new Error('Unable to load page script: ' + src)); };
          } else {
            script.textContent = sourceScript.textContent;
          }

          document.body.appendChild(script);
          if (!sourceScript.src) resolve();
        });
      });
    });

    return sequence;
  }

  async function navigate(href, options) {
    options = options || {};
    var url = new URL(href, window.location.href);

    if (requestController) requestController.abort();
    requestController = new AbortController();
    setLoading(true);

    try {
      var response = await fetch(url.href, {
        method: 'GET',
        credentials: 'same-origin',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-SILARIS-Navigation': 'partial'
        },
        signal: requestController.signal
      });

      if (response.status === 409 || response.headers.get('X-SILARIS-Full-Reload') === '1') {
        window.location.replace(url.href);
        return;
      }

      if (!response.ok) throw new Error('HTTP ' + response.status);

      var html = await response.text();
      var nextDocument = new DOMParser().parseFromString(html, 'text/html');
      var nextContent = nextDocument.querySelector(contentSelector);
      var currentContent = document.querySelector(contentSelector);

      if (!nextContent || !currentContent || nextDocument.querySelector('.login-wrapper, .login-page')) {
        throw new Error('Invalid partial response');
      }

      // Legacy editors use document.write while booting. Loading them into an
      // existing document can erase the admin shell, so these pages use a
      // normal navigation while ordinary list/detail pages remain instant.
      if (requiresFullReload(nextContent)) {
        window.location.assign(url.href);
        return;
      }

      syncPageResources(nextDocument);
      var contentClone = nextContent.cloneNode(true);
      var scriptPromise = runPageScripts(contentClone);
      currentContent.replaceChildren.apply(currentContent, Array.from(contentClone.childNodes));

      document.title = nextDocument.title || document.title;
      updateCsrf(nextDocument);
      updateSidebar(url);

      if (!options.popstate) history.pushState({ ajaxNavigation: true }, '', url.href);
      currentUrl = url.href;
      window.scrollTo({ top: 0, behavior: 'auto' });
      await scriptPromise;
      document.dispatchEvent(new CustomEvent('silaris:page-loaded', { detail: { url: url.href } }));
    } catch (error) {
      if (error.name === 'AbortError') return;
      window.location.assign(url.href);
    } finally {
      requestController = null;
      setLoading(false);
    }
  }

  // Server-rendered module URLs such as /Laporan do not live below the
  // /administrator prefix. Synchronize the menu on the first load as well as
  // after partial navigation so Dashboard is never highlighted by mistake.
  updateSidebar(new URL(window.location.href));

  document.addEventListener('click', function (event) {
    var anchor = event.target.closest('a[href]');
    if (!canNavigate(anchor, event)) return;

    event.preventDefault();
    navigate(anchor.href);
  }, true);

  document.addEventListener('submit', function (event) {
    var form = event.target;
    if (!(form instanceof HTMLFormElement) || event.defaultPrevented) return;
    if ((form.method || 'get').toLowerCase() !== 'get' || form.hasAttribute('data-no-ajax')) return;

    var action = new URL(form.action || window.location.href, window.location.href);
    if (action.origin !== window.location.origin || blockedPath.test(action.pathname)) return;

    event.preventDefault();
    var params = new URLSearchParams(new FormData(form));
    action.search = params.toString();
    navigate(action.href);
  });

  window.addEventListener('popstate', function () {
    if (window.location.href === currentUrl) return;
    navigate(window.location.href, { popstate: true });
  });
})();
