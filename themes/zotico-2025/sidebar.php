<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package zotico
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area form-page section">
  <div class="form-page__container container">
    <div class="form-page__title title">Напишите нам</div>
    <div class="form">
		<?php if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
          <div id="sidebar">
			  <?php dynamic_sidebar( 'sidebar-footer' ); ?>
          </div>
		<?php endif; ?>
    </div>
  </div>
</aside><!-- #secondary -->
