'use strict';

var play = function play(el, e) {
  e.preventDefault();
  var iframe = el.parentNode.querySelector('iframe');
  if (el) {
    el.classList.add('hide');
  }
  if (iframe) {
    iframe.setAttribute('src', iframe.getAttribute('data-src'));
  }
};(function () {
  var embeds = document.querySelectorAll('.t-oembed-thumbnail');

  var i = -1;
  while (++i < embeds.length) {
    embeds[i].addEventListener('click', play.bind(null, embeds[i]), true);
  }
})();
