// import Swiper JS
// import Swiper from 'swiper';
// import { Navigation, Pagination } from 'swiper/modules';
// import Swiper from '../../../../node_modules/swiper/swiper-bundle';

export const initProductSlider = function() {
  const sliderProduct = document.querySelector('.product__swiper');
  if (!sliderProduct) {
    return;
  }

  const swiperProduct = function() {
    new Swiper('.swiper', {
      // modules: [Navigation, Pagination],
      slidesPerView: 1,
      effect: 'fade',
      navigation: {
        nextEl: '.product__arrow--next',
        prevEl: '.product__arrow--prev'
      },
      pagination: {
        el: '.product__pagination',
        type: 'fraction',
        renderFraction: function(currentClass, totalClass) {
          return `<span class="slider__pagination-num ${currentClass}"></span>` +
            ` из ` +
            `<span class="slider__pagination-num ${totalClass}"></span>`;
        }
      }
    });
  }

  swiperProduct();
};
