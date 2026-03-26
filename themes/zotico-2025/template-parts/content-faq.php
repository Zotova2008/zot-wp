<?php
/**
 * Шаблон: Преимущества
 * @var $args
 * ОЖИДАЕМЫЕ ДАННЫЕ
 * $args['title']
 * $args['advLoop']:
 *  - title
 *  - text
 *  - icon (название иконки для спрайта)
 */

// ACF Relationship возвращает массив ID

$faqTitle    = $args['faqTitle'] ?? 'Ваши вопросы';
$selectedIds = $args['faqSelectedPosts'] ?? [];

$faqArgs  = [
	'post_type'      => 'questions',
	'post_status'    => 'publish',
	'posts_per_page' => - 1,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post__not_in'   => $selectedIds,
];
$faqPosts = get_posts( $faqArgs );

$faqPostsAll = $faqPosts;

if ( is_array( $selectedIds ) && ! empty( $selectedIds ) ) {
	$faqArgsSelected = [
		'post_type'      => 'questions',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
		'post__in'       => $selectedIds,
		'orderby'        => 'post__in', // сохраняем порядок ACF
	];

	$faqPostsSelected = get_posts( $faqArgsSelected );
	$faqPostsAll      = array_merge( $faqPostsSelected, $faqPosts );
}
?>

<section class="faq section">
  <div class="faq__container container">
    <h2 class="faq__title title"><?php echo $faqTitle ?></h2>

	  <?php foreach ( $faqPostsAll as $post ) {
		  setup_postdata( $post );
		  $questions = get_the_title( $post );
		  $answer    = apply_filters( 'the_content', $post->post_content );
		  ?>
        <details class="accordion">
          <summary class="accordion__button button">
			  <?php echo $questions; ?>
          </summary>
          <div class="accordion__wrapper">
			  <?php echo $answer ?>
          </div>
        </details>
	  <?php }
	  wp_reset_postdata(); ?>
  </div>
</section>
