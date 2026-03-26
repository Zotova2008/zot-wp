<?php
/**
 * Шаблон: Первый экран (фон через <picture>)
 * @var $args
 * Ожидаемые данные:
 *  - $args['title'] — заголовок
 *  - $args['imgArr'] — ACF-картинка (массив)
 *        - массив самой картинки
 */

$introTitle       = $args['title'] ?? '';
$introTitlePage   = $args['titlePage'] ?? 'Zotico - игрушки для животных';
$introContentPage = $args['content'] ?? '';
$imgArr           = $args['imgArr'];
$altRaw           = trim( (string) ( $imgArr['alt'] ?? '' ) );
$name             = trim( (string) ( $imgArr['name'] ?? '' ) );
$alt              = ( $altRaw !== '' && $altRaw !== $name ) ? $altRaw : $introTitlePage;
$addClass         = $args['addClass'] ?? '';
?>

<section class="intro <?php echo $addClass ?>">
  <div class="intro__container container">

    <div class="intro__content">
      <?php if ( $introTitle ) { ?>
        <h1 class="intro__title title"><?php echo $introTitle; ?></h1>
      <?php } ?>
      <?php if ( ! empty( $introContentPage ) ) {
        echo $introContentPage;
      } ?>
    </div>

    <?php if ( $imgArr ): ?>
      <?php
      // Фон: мобилки до 499px — 350px; остальное — 1920px
      zci_picture( $imgArr['ID'], [
        'class'         => 'intro__img',        // обёртка
        'img_class'     => 'intro__img-el no-lazy',     // сам <img>
        'altImg'        => $alt,
        'fancybox'      => false,               // fancy не нужен
        'img_size'      => 'bg-1920',           // дефолт для >=500px
        'media_sources' => [
          [ 'media' => '(max-width: 499px)', 'size' => 'thumb-350' ],
        ],
      ] );
      ?>
    <?php endif; ?>
  </div>
</section>
