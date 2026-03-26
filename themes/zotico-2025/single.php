<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package zotico
 */

get_header();
?>

  <main id="primary" class="site-main 111-single">

	  <?php
	  while ( have_posts() ) {
		  the_post(); ?>

        <!-- Блок "Первый экран" -->
		  <?php
		  $intro = array(
			  'titlePage' => get_the_title(),
			  'title'     => get_field( 'intro-main-title' ),
			  'imgArr'    => get_field( 'intro-main-bg' ),
		  );

		  get_template_part( 'template-parts/hero', 'screen', $intro );
		  ?>

		  <?php
		  if ( get_field( 'goods-slider-boolean' ) ) {
			  $goods = [
				  'main-title'              => get_the_title(),
				  'slider-boolean'          => get_field( 'goods-slider-boolean' ),
				  'slider-img'              => get_field( 'goods-slider' ),
				  'goods-equipment-boolean' => get_field( 'goods-equipment-boolean' ),
				  'goods-equipment-title'   => get_field( 'goods-equipment-title' ),
				  'goods-equipment-loop'    => get_field( 'goods-equipment-loop' ),
				  'instruction-boolean'     => get_field( 'instruction-boolean' ),
				  'instruction-title'       => get_field( 'instruction-title' ),
				  'instruction-loop'        => get_field( 'instruction-loop' ),
				  'storeBeforeDescr'        => get_field( 'link-before-description' ),
				  'storeAfterDescr'         => get_field( 'link-after-description' ),
				  'storeAfterCatalog'       => get_field( 'link-after-catalog' ),
				  'link-wb'                 => get_field( 'link-wb' ),
				  'link-ozon'               => get_field( 'link-ozon' ),
				  'link-ya'                 => get_field( 'link-ya' ),
			  ];
			  get_template_part( 'template-parts/content', 'goods', $goods );
		  }
	  }

	  ?>
  </main><!-- #main -->

<?php
get_footer();
