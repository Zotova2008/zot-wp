<?php
/**
 * Template name: Форма обратной связи
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
			  'text'     => get_field( 'about-mission' ),
			  'addClass' => 'mission--white',
		  );

		  get_template_part( 'template-parts/content', 'mission', $mission );
	  }
	  ?>
    <section class="form-page section">
      <div class="form-page__container container">
        <div class="form-page__title title">Напишите нам</div>
        <div class="form">
			<?php
			$form_id = get_field( 'form-contacts' );
			if ( $form_id ) {
				$title = get_the_title( $form_id );
				echo do_shortcode( '[contact-form-7 id="' . $form_id . '" title="' . esc_attr( $title ) . '"]' );
			}; ?>

        </div>
      </div>
    </section>

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
