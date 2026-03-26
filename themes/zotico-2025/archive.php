<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package zotico
 */

get_header();

?>

  <main id="primary" class="site-main 111-archive">

	  <?php
	  $term_id = get_queried_object_id();
	  $imgArr  = get_field( 'intro-main-bg', 'category_' . $term_id );

	  $intro = array(
		  'title'   => single_cat_title( '', false ),
		  'content' => category_description(),
		  'imgArr'  => $imgArr,
	  );

	  get_template_part( 'template-parts/hero', 'screen', $intro );
	  ?>

  </main><!-- #main -->

<?php
get_footer();
