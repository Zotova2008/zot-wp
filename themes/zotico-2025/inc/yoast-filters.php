<?php
/**
 * Yoast filters
 * Вывести в нужном месте
 * if ( function_exists( 'yoast_breadcrumb' ) ) {yoast_breadcrumb();}
 */

if ( function_exists( 'yoast_breadcrumb' ) ) {
	function wpseo_custom_breadcrumb_output_wrapper( $wrapper ) {
		$wrapper = 'ul';

		return $wrapper;
	}

	function add_breadcrumb_class( $class ): string {
		return 'breadcrumbs__list';
	}

	function wpseo_custom_breadcrumb_single_link_wrapper( $wrapper ) {
		$wrapper = 'li class=breadcrumbs__item';

		return $wrapper;
	}

	add_filter( 'wpseo_breadcrumb_output_wrapper', 'wpseo_custom_breadcrumb_output_wrapper' );
	add_filter( 'wpseo_breadcrumb_output_class', 'add_breadcrumb_class' );
	add_filter( 'wpseo_breadcrumb_single_link_wrapper', 'wpseo_custom_breadcrumb_single_link_wrapper' );
}
