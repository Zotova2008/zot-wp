<?php
/**
 * zotico functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package zotico
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

function dump( $el ) {
	echo '<pre>';
	print_r( $el );
	echo '</pre>';
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
require get_template_directory() . '/inc/after-setup-theme.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/wp-enqueue-scripts.php';

/**
 * Menu
 */
require get_template_directory() . '/inc/menu.php';

/**
 * Регистрация нового типа записи
 */
require get_template_directory() . '/inc/register-post-types.php';

/**
 * Регистрация Сайдбара
 */
require get_template_directory() . '/inc/sidebar.php';

/**
 * Для Custom Field Suite сортировка для поля "Связи"
 */
require get_template_directory() . '/inc/custom-field-suite.php';

/**
 * Для полей Contact Form (убераем br,p,span)
 */
require get_template_directory() . '/inc/contacts-form.php';

/**
 * Yoast filters
 */
require get_template_directory() . '/inc/yoast-filters.php';

/**
 * Дополнительные функции
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

require get_template_directory() . '/inc/debug-file.php';

/**
 * Добавляем пункт меню в админку ссылкой на страницу с настройками
 */

function add_site_settings_to_menu() {
	add_menu_page( 'Страница общих данных', 'Страница общих данных', 'manage_options',
		'post.php?post=' . get_page_by_path( "options-page", null, "page" )->ID . '&action=edit', '',
		'dashicons-admin-tools', 2 );
}

add_action( 'admin_menu', 'add_site_settings_to_menu' );

class ACF_Field_For_Contact_Form_7_V5 extends acf_field {
	public array $settings = []; // ← добавьте строку
	// ...
}
