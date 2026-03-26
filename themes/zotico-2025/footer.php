<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package zotico
 */

$optionPage    = get_page_by_path( 'options-page', '', 'page' );
$optionPageID  = $optionPage->ID;
$linkWbGoods   = get_field( 'link-wb', $optionPageID );
$linkOzonGoods = get_field( 'link-ozon', $optionPageID );
$linkYaGoods   = get_field( 'link-ya', $optionPageID );
?>

<div class="before-footer">
  <!-- Блок "Каталог"     -->
	<?php
	$catalog = array(
		'title'        => get_field( 'catalog-title' ),
		// 'posts_per_page'   => 6,
		'post__not_in' => is_single() ? array( get_the_ID() ) : array(),
	);
	get_template_part( 'template-parts/content', 'catalog', $catalog );
	?>
  <!-- Купить в магазинах -->
	<?php
	$store = array(
		'linkWB'   => $linkWbGoods,
		'linkOzon' => $linkOzonGoods,
		'linkYa'   => $linkYaGoods,
		'title'    => 'Наши товары в магазинах',
		'showId'   => true,
	);
	get_template_part( 'template-parts/content', 'store', $store );
	?>
	<?php
	get_template_part( 'template-parts/content', 'certification' );
	get_template_part( 'template-parts/content', 'pets' );
	?>
</div>

<footer id="colophon" class="footer site-footer">
  <div class="footer__wrapper footer__wrapper--contacts" id="contacts">
    <div class="footer__container container">
      <section class="footer__contacts">
        <h2 class="footer__title title">Нужна помощь? Мы здесь.</h2>
        <a class="footer__form-button button" href="/form" data-fancybox data-src="#modal-callback">Написать нам</a>

        <address class="footer__address">
          <p class="footer__contacts-text">
            E-mail:&nbsp;<a class="link" href="mailto:<?php the_field( 'main-mail', $optionPageID ); ?>">
              <span><?php the_field( 'main-mail', $optionPageID ); ?></span>
            </a>
          </p>
        </address>
        <!--<ul class="footer__social social">-->
        <!--<li class="social__item">-->
        <!--<a class="social__link" href="#" target="_blank" rel="noopener noreferrer" aria-label="Мы ВКонтакте">-->
        <!--<svg width="30" height="30" aria-hidden="true" focusable="false">-->
        <!--<use xlink:href="images/sprite.svg#social-vk"></use>-->
        <!--</svg>-->
        <!--</a>-->
        <!--</li>-->
        <!--<li class="social__item">-->
        <!--<a class="social__link" href="#" target="_blank" rel="noopener noreferrer" aria-label="Мы в Телеграм">-->
        <!--<svg width="30" height="30" aria-hidden="true" focusable="false">-->
        <!--<use xlink:href="images/sprite.svg#social-telegram"></use>-->
        <!--</svg>-->
        <!--</a>-->
        <!--</li>-->
        <!--</ul>-->
      </section>
      <section class="footer__menus">
        <div class="footer__menus-item footer__menus-item--menu">
          <h2 class="footer__title">Товары</h2>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-footer-goods',
				'container'      => false,
				'menu_id'        => 'menu-footer-goods',
				'menu_class'     => 'footer__menu',
				'walker'         => new menu_footer(),
			) );
			?>
        </div>
        <div class="footer__menus-item footer__menus-item--learn">
          <h2 class="footer__title">Читать</h2>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-footer-read',
				'container'      => false,
				'menu_id'        => 'menu-footer-read',
				'menu_class'     => 'footer__menu',
				'walker'         => new menu_footer(),
			) );
			?>
        </div>
        <div class="footer__menus-item footer__menus-item--help">
          <h2 class="footer__title">Помощь</h2>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'menu-footer-help',
				'container'      => false,
				'menu_id'        => 'menu-footer-help',
				'menu_class'     => 'footer__menu',
				'walker'         => new menu_footer(),
			) );
			?>
        </div>
      </section>
    </div>
  </div>
  <div class="footer__wrapper footer__wrapper--copyright">
    <div class="footer__wrapper-container container">
      <p class="footer__copyright">&copy; Zotico 2017 - <?php echo date( 'Y' ); ?></p>
      <!-- <a class="footer__policy link" href="policy.html">Политика конфеденциальности</a>-->
    </div>
  </div>
</footer>
<div class="modal modal--callback" id="modal-callback">
  <div class="modal__content">
    <p class="modal__title">Напишите нам</p>
    <div class="form">
		<?php if ( is_active_sidebar( 'sidebar-footer' ) ) : ?>
          <div id="sidebar">
			  <?php dynamic_sidebar( 'sidebar-footer' ); ?>
          </div>
		<?php endif; ?>
    </div>
  </div>
</div>

<?php wp_footer(); ?>

<div class="modal form-message" id="modal-success" data-message-success>
  <div class="form-message__inner">
    <p class="form-message__title">Спасибо!</p>
    <p class="form-message__text">Ваше сообщение успешно отправлено.</p>
  </div>
  <button class="f-button is-close-btn" data-fancybox-close title="Close">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" tabindex="-1">
      <path d="M20 20L4 4m16 0L4 20"></path>
    </svg>
  </button>
</div>
<div class="modal form-message" id="modal-error" data-message-error>
  <div class="form-message__inner">
    <p class="form-message__title">Упс! <br>Что-то пошло не так.</p>
    <p class="form-message__text">Пожалуйста, перезагрузите страницу и&nbsp;попробуйте отправить сообщение еще раз.</p>
  </div>
  <button class="f-button is-close-btn" data-fancybox-close title="Close">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" tabindex="-1">
      <path d="M20 20L4 4m16 0L4 20"></path>
    </svg>
  </button>
</div>

</div>
</body>
</html>
