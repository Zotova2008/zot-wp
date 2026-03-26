<?php
/**
 * Template name: Главная
 */

get_header();
// ID страницы
$posts_page_id = (int) get_option( 'page_for_posts' );
?>

  <main id="primary" class="site-main 111-home">

	  <?php
	  $intro = array(
		  'titlePage' => get_the_title( $posts_page_id ),
		  'title'     => get_field( 'intro-main-title', $posts_page_id ),
		  'content'   => apply_filters( 'the_content', get_post_field( 'post_content', $posts_page_id ) ),
		  'imgArr'    => get_field( 'intro-main-bg', $posts_page_id ),
		  'addClass'  => 'intro--about',
	  );

	  get_template_part( 'template-parts/hero', 'screen', $intro );
	  ?>

	  <?php
	  if ( get_field( 'about-mission-boolean' ) ) {
		  $mission = array(
			  'text' => get_field( 'about-mission' ),
		  );

		  get_template_part( 'template-parts/content', 'mission', $mission );
	  }
	  ?>

    <!--  Блок "Преимущества"    -->
	  <?php
	  if ( get_field( 'adv-boolean' ) ) {
		  $adv = array(
			  'title'   => get_field( 'adv-title' ),
			  'advLoop' => get_field( 'adv-blocks' ),
		  );
		  get_template_part( 'template-parts/content', 'adv', $adv );
	  }
	  ?>

  </main><!-- #main -->

<?php
get_footer();
