function include (filename) {
  // http://kevin.vanzonneveld.net
  // +   original by: mdsjack (http://www.mdsjack.bo.it)
  // +   improved by: Legaev Andrey
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Michael White (http://getsprink.com)
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      bugfixed by: Brett Zamir (http://brett-zamir.me)
  // %        note 1: Force Javascript execution to pause until the file is loaded. Usually causes failure if the file never loads. ( Use sparingly! )
  // %        note 2: The included file does not come available until a second script block, so typically use this in the header.
  // %        note 3: Uses global: php_js to keep track of included files
  // *     example 1: include('http://www.phpjs.org/js/phpjs/_supporters/pj_test_supportfile_2.js');
  // *     returns 1: 1
  var d = this.window.document;
  var isXML = d.documentElement.nodeName !== 'HTML' || !d.write; // Latter is for silly comprehensiveness
  var js = d.createElementNS && isXML ? d.createElementNS('http://www.w3.org/1999/xhtml', 'script') : d.createElement('script');
  js.setAttribute('type', 'text/javascript');
  js.setAttribute('src', filename);
//  js.setAttribute('defer', 'defer');
  d.getElementsByTagNameNS && isXML ? (d.getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'head')[0] ? d.getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'head')[0].appendChild(js) : d.documentElement.insertBefore(js, d.documentElement.firstChild) // in case of XUL
  ) : d.getElementsByTagName('head')[0].appendChild(js);
  // save include state for reference by include_once
  var cur_file = {};
  cur_file[this.window.location.href] = 1;

  // BEGIN REDUNDANT
  php_js_shared = php_js_shared || {};
  // END REDUNDANT
  if (!php_js_shared.includes) {
    php_js_shared.includes = cur_file;
  }
  if (!php_js_shared.includes[filename]) {
    php_js_shared.includes[filename] = 1;
  } else {
    php_js_shared.includes[filename]++;
  }

  return php_js_shared.includes[filename];
}

function include_once (filename) {
  // http://kevin.vanzonneveld.net
  // +   original by: Legaev Andrey
  // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   improved by: Michael White (http://getsprink.com)
  // +      input by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
  // -    depends on: include
  // %        note 1: Uses global: php_js to keep track of included files (though private static variable in namespaced version)
  // *     example 1: include_once('http://www.phpjs.org/js/phpjs/_supporters/pj_test_supportfile_2.js');
  // *     returns 1: true
  var cur_file = {};
  cur_file[this.window.location.href] = 1;

  // BEGIN STATIC
  try { // We can't try to access on window, since it might not exist in some environments, and if we use "this.window"
    //    we risk adding another copy if different window objects are associated with the namespaced object
    php_js_shared; // Will be private static variable in namespaced version or global in non-namespaced
    //   version since we wish to share this across all instances
  } catch (e) {
    php_js_shared = {};
  }
  // END STATIC
  if (!php_js_shared.includes) {
    php_js_shared.includes = cur_file;
  }
  if (!php_js_shared.includes[filename]) {
    if (this.include(filename)) {
      return true;
    }
  } else {
    return true;
  }
  return false;
}

function include_stylesheet (filename) {
  var d = this.window.document;
  var isXML = d.documentElement.nodeName !== 'HTML' || !d.write; // Latter is for silly comprehensiveness
  var css = d.createElementNS && isXML ? d.createElementNS('http://www.w3.org/1999/xhtml', 'link') : d.createElement('link');
  css.setAttribute('rel', 'stylesheet');
  css.setAttribute('type', 'text/css');
  css.setAttribute('href', filename);  
  d.getElementsByTagNameNS && isXML ? (d.getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'head')[0] ? d.getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'head')[0].appendChild(css) : d.documentElement.insertBefore(css, d.documentElement.firstChild) // in case of XUL
  ) : d.getElementsByTagName('head')[0].appendChild(css);
  // save include state for reference by include_once
  var cur_file = {};
  cur_file[this.window.location.href] = 1;

  // BEGIN REDUNDANT
  php_css_shared = php_css_shared || {};
  // END REDUNDANT
  if (!php_css_shared.includes) {
    php_css_shared.includes = cur_file;
  }
  if (!php_css_shared.includes[filename]) {
    php_css_shared.includes[filename] = 1;
  } else {
    php_css_shared.includes[filename]++;
  }

  return php_css_shared.includes[filename];
}

function include_stylesheet_once (filename) {
  var cur_file = {};
  cur_file[this.window.location.href] = 1;

  // BEGIN STATIC
  try { // We can't try to access on window, since it might not exist in some environments, and if we use "this.window"
    //    we risk adding another copy if different window objects are associated with the namespaced object
    php_css_shared; // Will be private static variable in namespaced version or global in non-namespaced
    //   version since we wish to share this across all instances
  } catch (e) {
    php_css_shared = {};
  }
  // END STATIC
  if (!php_css_shared.includes) {
    php_css_shared.includes = cur_file;
  }
  if (!php_css_shared.includes[filename]) {
    if (this.include_stylesheet(filename)) {
      return true;
    }
  } else {
    return true;
  }
  return false;
}