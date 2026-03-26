<?php
/**
 * @var array $args
 */

$title     = ! empty( $args['title'] ) ? $args['title'] : 'Купить товары в магазинах';
$linkWB    = $args['linkWB'];
$linkOzon  = $args['linkOzon'];
$linkYa    = $args['linkYa'];
$className = ! empty( $args['className'] ) ? $args['className'] : null;
$showId    = $args['showId'] ?? null;
?>

<section class="store section <?php if ( $className ) {
  echo $className;
} ?>" <?php if ( $showId ) { ?>id="store"<?php }; ?>>
  <div class="store__container container">
    <h2 class="store__title title-sub"><?php echo $title ?></h2>
    <div class="store__inner">
      <?php if ( ! empty( $linkWB ) ) { ?>
        <a class="store__link store__link--wb button" href="<?php echo $linkWB; ?>" rel="noreferrer noopener"
          target="_blank">
          <svg width="100" height="20" aria-label="Логотип компании Wildberries" focusable="false">
            <use
              xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/sprite.svg#logo-wildberries"></use>
          </svg>
        </a>
      <?php } ?>
      <?php if ( ! empty( $linkOzon ) ) { ?>
        <a class="store__link store__link--ozon button" href="<?php echo $linkOzon; ?>" rel="noreferrer noopener"
          target="_blank">
          <svg width="100" height="20" aria-label="Логотип компании OZON" focusable="false">
            <use xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/sprite.svg#logo-ozon"></use>
          </svg>
        </a>
      <?php } ?>
      <?php if ( ! empty( $linkYa ) ) { ?>
        <a class="store__link store__link--yandex button" href="<?php echo $linkYa; ?>" rel="noreferrer noopener"
          target="_blank">
          <svg width="100" height="20" aria-label="Логотип компании Yandex Маркет" focusable="false">
            <use xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/sprite.svg#logo-yandex"></use>
          </svg>
        </a>
      <?php } ?>
    </div>
  </div>
</section>
