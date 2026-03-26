<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package zotico
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php

	$intro = array(
		'titlePage' => get_the_title(),
		'title'     => get_the_title(),
		'imgArr'    => get_field( 'intro-main-bg' ),
	);

	get_template_part( 'template-parts/hero', 'screen', $intro );
	?>

  <div class="entry-content section">
    <div class="container">
		<?php
		the_content();
		?>
    </div>
  </div>
</article><!-- #post-<?php the_ID(); ?> -->
