<?php
/**
 * Для полей Contact Form (убирает br,p,span)
 * Для того что бы выводилось сообщении об ошибке, нужно добавить обвертке поля класс wpcf7-form-control-wrap
 */

add_action( 'wpcf7_autop_or_not', '__return_false' );

add_filter( 'wpcf7_form_elements', function ( $content ) {
	$content = preg_replace( '/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content );

	return $content;
} );
