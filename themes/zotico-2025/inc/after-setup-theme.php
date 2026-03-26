<?php
/**
 * Настройка темы и подключение стандартных возможностей WordPress
 */
function zotico_setup() {
	// Подключение переводов из папки /languages
	load_theme_textdomain( 'zotico', get_template_directory() . '/languages' );

	// RSS-ленты в <head>
	add_theme_support( 'automatic-feed-links' );

	// Автоматическое управление тегом <title>
	add_theme_support( 'title-tag' );

	// Миниатюры записей и страниц
	add_theme_support( 'post-thumbnails' );

	// Поддержка HTML5-разметки
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Фон по умолчанию
	add_theme_support( 'custom-background', apply_filters( 'zotico_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Мгновенное обновление виджетов в кастомайзере
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Логотип (гибкие размеры)
	add_theme_support( 'custom-logo', array(
		'height'      => 250,
		'width'       => 250,
		'flex-width'  => true,
		'flex-height' => true,
	) );
}
add_action( 'after_setup_theme', 'zotico_setup' );
