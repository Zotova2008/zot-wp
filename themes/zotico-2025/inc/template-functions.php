<?php

if ( ! function_exists( 'pre_r' ) ) {
	/**
	 * Красивый вывод print_r в теге <pre>
	 *
	 * @param mixed $data - переменная для отладки
	 * @param bool $return - если true, возвращает строку вместо вывода
	 *
	 * @return string|void
	 */
	function pre_r( $data, $return = false ) {
		$output = '<pre>' . print_r( $data, true ) . '</pre>';

		if ( $return ) {
			return $output;
		}

		echo $output;
	}
}

/**
 * Собрать массив элементов с учетом ACF "Родственные связи"
 */
require get_template_directory() . '/inc/render-faq-block.php';
