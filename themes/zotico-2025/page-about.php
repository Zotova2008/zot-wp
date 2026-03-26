<?php
/**
 * Template name: О нас
 */

get_header();
?>

  <main id="primary" class="site-main">

	  <?php
	  $intro = array(
		  'titlePage' => get_the_title(),
		  'title'     => get_field( 'intro-main-title' ),
		  'content'   => apply_filters( 'the_content', get_post_field( 'post_content', get_the_ID() ) ),
		  'imgArr'    => get_field( 'intro-main-bg' ),
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
