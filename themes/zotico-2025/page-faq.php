<?php
/**
 * Template name: FAQ
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
		  'addClass'  => 'intro--faq',
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

	  <?php

	  if ( get_field( 'faq-boolean' ) ) {
		  $faq = array(
			  'title'            => get_field( 'faq-title' ),
			  'faqSelectedPosts' => get_field( 'faq-questions' )
		  );

		  get_template_part( 'template-parts/content', 'faq', $faq );
	  }
	  ?>

  </main><!-- #main -->

<?php
get_footer();
