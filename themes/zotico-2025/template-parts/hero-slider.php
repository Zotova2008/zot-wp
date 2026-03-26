<?php
/**
 * Шаблон: Главный слайдер
 * @var $args
 * ОЖИДАЕМЫЕ ДАННЫЕ ($args['slides']):
 *  - image-1x    (строка URL)
 *  - image-2x    (строка URL)
 *  - text-slide  (строка)
 *  - btn-text    (строка)
 *  - btn-link    (строка)
 */

$slides = $args['slider'];
?>

<?php if ( is_array( $slides ) && ! empty( $slides ) ) { ?>
  <section class="hero">
    <div class="hero__wrapper">
      <div class="hero__swiper swiper slider">
        <div class="hero__swiper-wrapper swiper-wrapper slider__wrapper">

          <?php foreach ( $slides as $row ) {
            $slideTitle   = $row['text-slide'];
            $slideBtnText = $row['btn-text'] ?? 'Подробнее';
            $slideBtnLink = $row['btn-link'] ?? '/category/goods/';
            $slideImg     = $row['image'];

            $altRaw = trim( (string) ( $slideImg['alt'] ?? '' ) );
            $name   = trim( (string) ( $slideImg['name'] ?? '' ) );
            $alt    = ( $altRaw !== '' && $altRaw !== $name ) ? $altRaw : $slideTitle;
            ?>
            <div class="hero__swiper-slide swiper-slide slider__slide">
              <div class="hero__container container">
                <div class="hero__inner">
                  <?php if ( ! empty( $slideTitle ) ) { ?>
                    <h2 class="hero__title title"><?php echo $slideTitle; ?></h2>
                  <?php } ?>
                  <a class="button" href="<?php echo $slideBtnLink; ?>"><?php echo $slideBtnText ?></a>
                </div>
              </div>
              <?php

              zci_picture( $slideImg['ID'], [
                'class'         => 'hero__img',        // обёртка
                'img_class'     => 'no-lazy',     // сам <img>
                'altImg'        => $alt,
                'fancybox'      => false,               // fancy не нужен
                'img_size'      => 'bg-1920',           // дефолт для >=500px
                'media_sources' => [
                  [ 'media' => '(max-width: 499px)', 'size' => 'thumb-350' ],
                ],
              ] );
              ?>
            </div>
          <?php } ?>

        </div>

        <?php if ( count( $slides ) > 1 ) { ?>
          <div class="hero__controls slider-controls container">
            <button class="hero__arrow hero__arrow--prev slider-controls__arrow slider-controls__arrow--prev"
              type="button"
              aria-label="Предыдущий слайд."></button>
            <div class="hero__pagination slider-controls__pagination"></div>
            <button class="hero__arrow hero__arrow--next slider-controls__arrow slider-controls__arrow--next"
              type="button"
              aria-label="Следующий слайд."></button>
          </div>
        <?php } ?>
      </div>
    </div>
  </section>
<?php } ?>
