<?php
/**
 * Template name: Главная
 */

get_header();

?>

  <main id="primary" class="site-main 111-front">
    <h1 class="visually-hidden">Zotico - товары для любимых питомцев</h1>

	  <?php
	  //    Главный слайдер
	  $slides = array(
		  'slider' => get_field( 'home-slider' ),
	  );
	  get_template_part( 'template-parts/hero', 'slider', $slides );
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
