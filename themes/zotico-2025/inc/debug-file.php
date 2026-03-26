<?php
// Показать путь к текущему шаблону в левом нижнем углу (только для админов)
//add_action( 'wp_footer', function () {
//	if ( is_admin() || ! current_user_can( 'manage_options' ) ) {
//		return;
//	}
//
//	global $template;
//	if ( ! $template ) {
//		return;
//	}
//
//	// Нормализуем и сделаем путь относительным к корню сайта
//	$path = wp_normalize_path( $template );
//	$rel  = str_replace( wp_normalize_path( ABSPATH ), '/', $path );
//
//	echo '<div style="
//        position:fixed; left:10px; bottom:10px; z-index:99999;
//        padding:6px 10px; background:#111; color:#0f0;
//        font:12px/1.4 monospace; border-radius:6px; opacity:.9;">
//        TPL: ' . esc_html( $rel ) . '
//    </div>';
//} );

// Добавить пункт в админ-бар с путём к шаблону (только для админов на фронте)
add_action( 'admin_bar_menu', function ( $wp_admin_bar ) {
	if ( is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	global $template;
	if ( ! $template ) {
		return;
	}

	$root = wp_normalize_path( get_theme_root() );
	$rel  = str_replace( $root, '', wp_normalize_path( $template ) );

	$wp_admin_bar->add_node( [
		'id'    => 'current-template-path',
		'title' => 'Template: ' . esc_html( $rel ),
		'href'  => '#',
	] );
}, 100 );

// Включите WP_DEBUG_LOG в wp-config.php, затем:
//add_action( 'template_redirect', function () {
//	if ( ! defined( 'WP_DEBUG_LOG' ) || ! WP_DEBUG_LOG ) {
//		return;
//	}
//	global $template;
//	if ( $template ) {
//		error_log( 'Current template: ' . wp_normalize_path( $template ) );
//	}
//} );
