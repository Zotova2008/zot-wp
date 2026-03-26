<?php

/**
 * Регистрация меню wp_nav_menu()
 */
register_nav_menus( array(
	'menu-header' => esc_html__( 'Меню в шапке', 'zotico' ),
	'menu-footer-goods' => esc_html__( 'Футер раздел: Товары', 'zotico' ),
	'menu-footer-read' => esc_html__( 'Футер раздел: Читать', 'zotico' ),
	'menu-footer-help' => esc_html__( 'Футер раздел: Помощь', 'zotico' ),
) );

/**
 * Walker Nav Menu
 */
require get_template_directory() . '/inc/custom-class/menu-header-walker.php';
require get_template_directory() . '/inc/custom-class/menu-footer-walker.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Кастомный список меню
 * вывести в нужном месте " echo customMenu( 'menu-directions' ); "
 */
function customMenu( $customMenuSlug ) {
	$menu_name = $customMenuSlug;
	$locations = get_nav_menu_locations();

	if ( $locations && isset( $locations[ $menu_name ] ) ) {

		// получаем элементы меню
		$menu_items = wp_get_nav_menu_items( $locations[ $menu_name ] );

		// создаем список
		$menu_list = '<ul id="menu-' . $menu_name . '">';

		foreach ( (array) $menu_items as $key => $menu_item ) {
			$menu_list .= '<li><a href="' . $menu_item->url . '">' . $menu_item->title . '</a></li>';
		}

		$menu_list .= '</ul>';
	} else {
		$menu_list = '<ul><li>Меню "' . $menu_name . '" не определено.</li></ul>';
	}

	return $menu_list;
}
