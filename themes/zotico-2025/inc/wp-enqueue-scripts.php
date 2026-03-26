<?php
/**
 * Подключение скриптов и стилей
 */
function zotico_scripts(): void {
	// Основной стиль темы (style.css)
	wp_enqueue_style( 'zotico-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'zotico-style', 'rtl', 'replace' );

	// Стили библиотек
	wp_enqueue_style( 'zotico-fancybox-css', get_template_directory_uri() . '/assets/lib/fancybox/fancybox.css', array(), _S_VERSION, 'all' );
	wp_enqueue_style( 'zotico-swiper-css', get_template_directory_uri() . '/assets/lib/swiper/swiper-bundle.min.css', array(), _S_VERSION, 'all' );

	// Основной CSS
	wp_enqueue_style( 'zotico-main', get_template_directory_uri() . '/assets/css/styles.min.css', array(
		'zotico-style',
		'zotico-swiper-css',
		'zotico-fancybox-css'
	), _S_VERSION, 'all' );

	// Скрипты
	wp_enqueue_script( 'zotico-navigation-js', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'zotico-swiper-js', get_template_directory_uri() . '/assets/lib/swiper/swiper-bundle.min.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'zotico-fancybox-js', get_template_directory_uri() . '/assets/lib/fancybox/fancybox.umd.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'zotico-mask-js', get_template_directory_uri() . '/assets/lib/phoneinput.js', array(), _S_VERSION, true );

	wp_enqueue_script( 'zotico-main', get_template_directory_uri() . '/assets/js/main.min.js', array(
		'zotico-fancybox-js',
		'zotico-swiper-js',
		'zotico-navigation-js',
		'zotico-mask-js'
	), _S_VERSION, true );

	// Ответы на комментарии (только для одиночных записей/страниц)
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'zotico_scripts' );
