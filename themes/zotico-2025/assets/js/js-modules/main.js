import { iosVhFix } from './utils/ios-vh-fix.js';
import { initSliders } from './modules/slider/init-slider.js';
import { initAnimate } from './modules/animate/animate.js';
import { initBurger } from './modules/header/init-burger.js';
import { initFancy } from './modules/initFancy.js';
// ---------------------------------

window.addEventListener('DOMContentLoaded', () => {

  iosVhFix();

  window.addEventListener('load', () => {
    initBurger();
    initFancy();
    initSliders();
    initAnimate();
  });
});
