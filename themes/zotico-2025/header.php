<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package zotico
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>

  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/adigiana/adigiana-ultra-400.woff2"
    as="font" crossorigin="">
  <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/lazydog/lazydog-400.woff2"
    as="font" crossorigin="">
  <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/proxima-nova/proxima-400.woff2"
    as="font" crossorigin="">
  <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/proxima-nova/proxima-400.woff"
    as="font" crossorigin="">
  <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/proxima-nova/proxima-700.woff2"
    as="font" crossorigin="">
  <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/proxima-nova/proxima-700.woff"
    as="font" crossorigin="">
  <link rel="icon" type="image/png" sizes="32x32"
    href="<?php echo get_template_directory_uri(); ?>/assets/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16"
    href="<?php echo get_template_directory_uri(); ?>/assets/favicon/favicon-16x16.png">
  <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/favicon.ico" type="image/x-icon">

  <?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site wrapper">
  <header class="header header--sticky" data-header="sticky" data-hide-point="1" data-fix-block>
    <div class="header__container container">

      <?php if ( is_front_page() ) { ?>
        <span class="header__logo logo" aria-current="page">
              <svg width="178" height="45" aria-label="Логотип компании Zotico" focusable="false">
                <use
                  xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/sprite.svg#logo-all-sprite"></use>
              </svg>
            </span>
      <?php } else { ?>
        <a class="header__logo logo" href="/">
          <svg width="178" height="45" aria-label="Логотип компании Zotico" focusable="false">
            <use
              xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/sprite.svg#logo-all-sprite"></use>
          </svg>
        </a>
      <?php }; ?>

      <nav class="main-nav-container header__nav">
        <?php
        wp_nav_menu( array(
          'theme_location' => 'menu-header',
          'container'      => false,
          'menu_id'        => 'menu-header',
          'menu_class'     => 'main-nav header__nav-list',
          //			  'add_li_class_has_child' => 'main-nav__item main-nav__item--drop drop',
          'walker'         => new menu_header(),
        ) );
        ?>
      </nav>

      <a class="header__shops button" href="#store">Найти в магазинах</a>
      <button class="header__burger" type="button" aria-label="Переключатель отображения меню" aria-pressed="false"
        data-burger>
        <span class="header__burger-icon"></span></button>
    </div>
  </header>
