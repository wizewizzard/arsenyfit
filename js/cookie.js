if (!navigator.cookieEnabled) {
  alert( 'Включите cookie для комфортной работы с этим сайтом' );
}
function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
      "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}
function setCookie (name, value, expires, path, domain, secure) {
      document.cookie = name + "=" + encodeURIComponent(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}
function deleteCookie(name) {
  setCookie(name, "", {
    expires: -1
  })
}