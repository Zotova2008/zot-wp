import WOW from './wow.min.js';

export const initAnimate = function() {
  const advItem = document.querySelectorAll('.adv__item');
  const breakpoint = window.matchMedia('(min-width: 1201px)');
  if (breakpoint.matches) {
    advItem.forEach(element => {
      element.classList.add('animate__animated');
      element.classList.add('animate__slideinup');
    });

    new WOW({ live: false }).init();
  } else {
    advItem.forEach(element => {
      element.classList.remove('animate__animated');
      element.classList.remove('animate__slideinup');
    });
  }

}
