export const initHeroSlider = function() {
  const sliderHero = document.querySelector('.hero__swiper');
  if (!sliderHero) {
    return;
  }

  new Swiper('.swiper', {
    slidesPerView: 1,
    effect: 'fade',
    navigation: {
      nextEl: '.hero__arrow--next',
      prevEl: '.hero__arrow--prev'
    },
    pagination: {
      el: '.hero__pagination',
      type: 'fraction',
      renderFraction: function(currentClass, totalClass) {
        return `<span class="slider__pagination-num ${currentClass}"></span>` +
          ' из ' +
          `<span class="slider__pagination-num ${totalClass}"></span>`;
      }
    }
  });
};
