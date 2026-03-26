<?php
/**
 * Template name: Все товары
 */

get_header();
?>

  <main id="primary" class="site-main">

    <section class="intro intro--goods">
      <div class="intro__container container">
        <div class="intro__content">
          <h1 class="intro__title title"><?php the_title(); ?></h1>
          <p class="intro__text"><?php the_content(); ?></p>
        </div>
        <div class="intro__img">
          <picture>
            <source type="image/webp" srcset="<?php echo get_template_directory_uri(); ?>/assets/images/<?php the_field( 'about-intro-img' ); ?>@1x.webp 1x, <?php echo get_template_directory_uri(); ?>/assets/images/<?php the_field( 'about-intro-img' ); ?>@2x.webp 2x">
            <source type="image/avif" srcset="<?php echo get_template_directory_uri(); ?>/assets/images/<?php the_field( 'about-intro-img' ); ?>@1x.avif 1x, <?php echo get_template_directory_uri(); ?>/assets/images/<?php the_field( 'about-intro-img' ); ?>@2x.avif 2x">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/<?php the_field( 'about-intro-img' ); ?>@1x.jpg" srcset="<?php echo get_template_directory_uri(); ?>/assets/images/<?php the_field( 'about-intro-img' ); ?>@2x.jpg 2x" width="1000" height="400" alt="<?php the_title(); ?>" loading="lazy">
          </picture>
        </div>
      </div>
    </section>

	  <?php
	  get_template_part( 'template-parts/content', 'catalog' );
	  get_template_part( 'template-parts/content', 'store' );
	  get_template_part( 'template-parts/content', 'certification' );
	  get_template_part( 'template-parts/content', 'pets' );
	  ?>
  </main><!-- #main -->

<?php
get_footer();
