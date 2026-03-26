<?php
/**
 * Слайдер с описанием для товара
 * @var array $args
 */
$optionPage         = get_page_by_path( 'options-page', '', 'page' );
$optionPageID       = $optionPage->ID;
$mainTitle          = $args['main-title'];
$slidesBoolean      = $args['slider-boolean'];
$slides             = $args['slider-img'];
$equipmentBoolean   = $args['goods-equipment-boolean'];
$equipmentTitle     = $args['goods-equipment-title'];
$equipmentLoop      = $args['goods-equipment-loop'];
$instructionBoolean = $args['instruction-boolean'];
$instructionTitle   = $args['instruction-title'];
$instructionLoop    = $args['instruction-loop'];
$storeBeforeDescr   = $args['storeBeforeDescr'];
$storeAfterDescr    = $args['storeAfterDescr'];
$storeAfterCatalog  = $args['storeAfterCatalog'];
$linkWbGoods        = $args['link-wb'];
$linkOzonGoods      = $args['link-ozon'];
$linkYaGoods        = $args['link-ya'];
?>

<?php
if ( $storeBeforeDescr['boolean'] ) {
  $store = array(
    'linkWB'    => $linkWbGoods,
    'linkOzon'  => $linkOzonGoods,
    'linkYa'    => $linkYaGoods,
    'title'     => $storeBeforeDescr['title'],
    'className' => 'store--bg'
  );
  get_template_part( 'template-parts/content', 'store', $store );
} ?>
<article class="product-article">
  <section class="product section">
    <div class="product__container container">

      <div class="product__inner">
        <?php if ( $slidesBoolean ) { ?>
          <?php if ( is_array( $slides ) && ! empty( $slides ) ) { ?>
            <div class="product__swiper-inner">
              <div class="product__swiper swiper slider">
                <!-- Additional required wrapper-->
                <div class="product__wrapper swiper-wrapper slider__wrapper">
                  <!-- Slides-->
                  <?php foreach ( $slides as $row ) {
                    $show_image = $row['btn-image'];
                    $imageArr   = $row['image'];
                    $videoArr   = $row['video'];

                    $baseArr = $show_image ? $imageArr : $videoArr;
                    $altRaw  = trim( (string) ( $baseArr['alt'] ?? '' ) );
                    $name    = trim( (string) ( $baseArr['name'] ?? '' ) );
                    $alt     = ( $altRaw !== '' && $altRaw !== $name ) ? $altRaw : $mainTitle;

                    $group = 'goods-' . get_the_ID();
                    ?>
                    <div class="product__slide swiper-slide slider__slide">
                      <?php
                      zci_picture( $baseArr['ID'], [
                        'class'           => 'product__slide-img',
                        'img_class'       => 'product__slide-img-el',
                        'altImg'          => $alt,
                        'fancybox'        => true,
                        'fancybox_size'   => 'full',
                        'fancybox_prefix' => $group,
                        'fancy_attrs'     => [ 'data-aspect-ratio' => '3/4' ],
                        'img_size'        => 'thumb-350',
                      ] );
                      ?>
                    </div>
                  <?php }; ?>

                </div>
              </div>
              <?php if ( count( $slides ) > 1 ) { ?>
                <div class="product__controls slider-controls container">
                  <button
                    class="product__arrow product__arrow--prev slider-controls__arrow slider-controls__arrow--prev"
                    type="button"
                    aria-label="Предыдущий слайд."></button>
                  <div class="product__pagination slider-controls__pagination"></div>
                  <button
                    class="product__arrow product__arrow--next slider-controls__arrow slider-controls__arrow--next"
                    type="button"
                    aria-label="Следующий слайд."></button>
                </div>
              <?php } ?>
            </div>
          <?php } ?>
        <?php } ?>
        <div class="product__content">
          <div class="product__description">
            <?php the_content(); ?>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php
  if ( $storeAfterDescr['boolean'] ) {
    $store = array(
      'linkWB'    => $linkWbGoods,
      'linkOzon'  => $linkOzonGoods,
      'linkYa'    => $linkYaGoods,
      'title'     => $storeAfterDescr['title'],
      'className' => 'store--bg store--indent'
    );
    get_template_part( 'template-parts/content', 'store', $store );
  } ?>

  <?php if ( $instructionBoolean ) { ?>
    <?php if ( is_array( $instructionLoop ) && ! empty( $instructionLoop ) ) { ?>
      <section class="instruction">
        <div class="instruction__container container">
          <?php if ( ! empty( $instructionTitle ) ) { ?>
            <h2 class="instruction__title title-sub"><?php echo $instructionTitle; ?></h2>
          <?php } ?>
          <ul class="instruction__list">
            <?php foreach ( $instructionLoop as $row ) { ?>
              <li class="instruction__item"><?php echo $row['item']; ?></li>
            <?php } ?>
          </ul>
        </div>
      </section>
    <?php } ?>
  <?php } ?>

  <?php if ( $equipmentBoolean ) { ?>
    <?php if ( is_array( $equipmentLoop ) && ! empty( $equipmentLoop ) ) { ?>
      <section class="composition">
        <div class="composition__container container">
          <?php if ( ! empty( $equipmentTitle ) ) { ?>
            <h2 class="composition__title title-sub"><?php echo $equipmentTitle; ?></h2>
          <?php } ?>
          <ul class="composition__list">
            <?php foreach ( $equipmentLoop as $row ) { ?>
              <li class="composition__item">
                <?php if ( ! empty( $row['title'] ) ) { ?>
                  <h3 class="composition__subtitle title-sub"><?php echo $row['title']; ?></h3>
                <?php } ?>
                <?php if ( ! empty( $row['text'] ) ) { ?>
                  <p class="composition__text"><?php echo $row['text']; ?></p>
                <?php } ?>
              </li>
            <?php } ?>
          </ul>
        </div>
      </section>
    <?php } ?>
  <?php } ?>
</article><!-- #post-<?php the_ID(); ?> -->
