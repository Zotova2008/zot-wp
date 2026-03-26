<?php
/**
 * Склеивает массив объектов WP_Post:
 * сначала выбранные (из ACF), затем остальные.
 *
 * @param WP_Post[] $acfSelectedPosts Выбранные записи (объекты WP_Post из ACF)
 * @param WP_Post[] $allPosts Все записи (объекты WP_Post)
 * @param array $opts {
 *
 * @type 'title'|'none'|callable $others_sort Сортировка "остальных". По умолчанию 'title'.
 * @type 'ASC'|'DESC' $others_order Порядок сортировки по заголовку. По умолчанию 'ASC'.
 * }
 * @return WP_Post[]  Сначала выбранные, затем остальные.
 */

if ( ! function_exists( 'z_merge_selected_then_others_posts' ) ) {
	function wp_merge_selected_then_others_posts( array $acfSelectedPosts, array $allPosts, array $opts = [] ): array {
		$o = array_merge( [
			'others_sort'  => 'title',
			'others_order' => 'ASC',
		], $opts );

		// Карта ID => объект
		$map = [];
		foreach ( $allPosts as $p ) {
			$map[ $p->ID ] = $p;
		}

		// Сначала выбранные
		$chosen = [];
		foreach ( $acfSelectedPosts as $p ) {
			if ( isset( $map[ $p->ID ] ) ) {
				$chosen[] = $map[ $p->ID ];
				unset( $map[ $p->ID ] );
			}
		}

		// Потом остальные
		$others = array_values( $map );

		// Сортировка остальных
		if ( $o['others_sort'] === 'title' ) {
			usort( $others, function ( WP_Post $a, WP_Post $b ) use ( $o ) {
				$res = strcasecmp( $a->post_title, $b->post_title );

				return $o['others_order'] === 'DESC' ? - $res : $res;
			} );
		} elseif ( is_callable( $o['others_sort'] ) ) {
			usort( $others, $o['others_sort'] );
		}

		// 'none' — без сортировки, остаётся исходный порядок из $allPosts

		return array_merge( $chosen, $others );
	}
}
