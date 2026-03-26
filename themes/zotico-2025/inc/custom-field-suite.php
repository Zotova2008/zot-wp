<?php
/**
 * Для Custom Field Suite сортировка для поля "Связи"
 */

add_filter( 'cfs_field_relationship_query_args', 'zotico_cfs_field_relationship_bydate' );
function zotico_cfs_field_relationship_bydate( $args ) {
	$args['orderby'] = 'date';
	$args['order']   = 'DESC';

	return $args;
}
