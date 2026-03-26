<?php
/**
 * Шаблон: Первый экран (фон через <picture>)
 * @var $args
 * Ожидаемые данные:
 *  - $args['title'] — заголовок
 *  - $args['imgArr'] — ACF-картинка (массив)
 *        - массив самой картинки
 */

$text     = $args['text'];
$addClass = $args['addClass'] ?? null;
?>

<section class="mission <?php echo $addClass ?>">
  <div class="mission__container container">
    <h2 class="mission__title title"><?php echo $text ?></h2>
  </div>
</section>
