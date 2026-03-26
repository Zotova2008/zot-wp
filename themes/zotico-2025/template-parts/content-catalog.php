<?php
/**
 * Шаблон: Список товаров (записи)
 * @var $args
 * - title (string) - заголовок блока
 * - posts_per_page (int) — кол-во постов, если не указано → выводим все (-1)
 * - post__not_in (int[]) — массив ID постов для исключения (например, текущий пост)
 * - orderby (string) — поле сортировки (date/title/rand/meta_value и т.п.)
 * - order (string) — ASC | DESC
 */

$params = ( isset( $args ) && is_array( $args ) ) ? $args : [];

$posts_per_page = isset( $params['posts_per_page'] ) ? (int) $params['posts_per_page'] : - 1; // -1 = все записи
$post__not_in   = ( isset( $params['post__not_in'] ) && is_array( $params['post__not_in'] ) ) ? $params['post__not_in'] : [];
$orderBy        = $params['orderby'] ?? 'date';
$order          = $params['order'] ?? 'DESC';

$qArgs = array(
  'post_type'      => 'post',
  'posts_per_page' => $posts_per_page,
  'post__not_in'   => $post__not_in,
  'orderby'        => $orderBy,
  'order'          => $order,
);

$query = new WP_Query( $qArgs );
?>

<section class="catalog section">
  <div class="catalog__container container">
    <h2 class="catalog__title title">Наши товары</h2>
    <ul class="catalog__list">
      <?php while ( $query->have_posts() ) {
        $query->the_post();

        $cardTitle = get_field( 'card-title', get_the_ID() ) ?: get_the_title();
        $cardFront = get_field( 'card-img-front', $post->ID );
        $cardBack  = get_field( 'card-img-back', $post->ID );

        $altRawFront = trim( (string) ( $cardFront['alt'] ?? '' ) );
        $nameFront   = trim( (string) ( $cardFront['name'] ?? '' ) );
        $altFront    = ( $altRawFront !== '' && $altRawFront !== $nameFront ) ? $altRawFront : $cardTitle;

        $altRawBack = trim( (string) ( $cardFront['alt'] ?? '' ) );
        $nameBack   = trim( (string) ( $cardFront['name'] ?? '' ) );
        $altBack    = ( $altRawBack !== '' && $altRawBack !== $nameBack ) ? $altRawBack : $cardTitle;
        ?>
        <li class="catalog__item">
          <div class="catalog__img-container">
            <div class="catalog__img catalog__img--front">
              <?php
              zci_picture( $cardFront['ID'], [
                'class'           => '',        // обёртка
                'img_class'       => '',     // сам <img>
                'altImg'          => $altFront,
                'fancybox'        => true,
                'img_size'        => 'thumb-350',           // дефолт для >=500px
                'fancybox_prefix' => 'goods-' . get_the_ID()
              ] );
              ?>
            </div>

            <div class="catalog__img catalog__img--back">

              <?php
              zci_picture( $cardBack['ID'], [
                'class'           => '',        // обёртка
                'img_class'       => '',     // сам <img>
                'altImg'          => $altBack,
                'fancybox'        => true,
                'img_size'        => 'thumb-350',           // дефолт для >=500px
                'fancybox_prefix' => 'goods-' . get_the_ID()
              ] );
              ?>
            </div>
          </div>

          <div class="catalog__content">
            <a class="catalog__subtitle title-sub link" href="<?php the_permalink(); ?>">
              <span><?php echo $cardTitle; ?></span>
            </a>
            <?php the_excerpt(); ?>
            <a class="catalog__link button" href="<?php the_permalink(); ?>">Подробнее</a>
          </div>
        </li>
      <?php }
      wp_reset_postdata(); ?>
    </ul>
  </div>
</section>
