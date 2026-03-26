<?php
/**
 * Регистрируем все кастомные типы и таксономии здесь
 */

add_action( 'init', 'register_post_types' );
function register_post_types() {
	/** ===== Таксономия: Категории вопросов ===== */
	$tax_labels = array(
		'name'              => 'Категории вопросов',
		'singular_name'     => 'Категория',
		'search_items'      => 'Искать категории',
		'all_items'         => 'Все категории',
		'parent_item'       => 'Родительская категория',
		'parent_item_colon' => 'Родительская категория:',
		'edit_item'         => 'Редактировать категорию',
		'update_item'       => 'Обновить категорию',
		'add_new_item'      => 'Добавить категорию',
		'new_item_name'     => 'Название новой категории',
		'menu_name'         => 'Категории вопросов',
	);
	register_taxonomy( 'questions_category', array( 'questions' ), array(
		'labels'            => $tax_labels,
		'public'            => false,                 // без публичных URL
		'show_ui'           => true,
		'show_admin_column' => true,                  // колонка в списке FAQ
		'hierarchical'      => true,                  // как рубрики
		'show_in_rest'      => true,
		'rewrite'           => false,
	) );

	/** ===== CPT: FAQ ===== */
	$faq_labels = array(
		'name'               => 'Ваши вопросы',
		'singular_name'      => 'Вопрос',
		'menu_name'          => 'FAQ',
		'add_new'            => 'Добавить вопрос',
		'add_new_item'       => 'Добавить новый вопрос',
		'edit_item'          => 'Редактировать вопрос',
		'new_item'           => 'Новый вопрос',
		'view_item'          => 'Просмотр вопроса',
		'search_items'       => 'Искать вопросы',
		'not_found'          => 'Вопросов не найдено',
		'not_found_in_trash' => 'В корзине вопросов нет',
	);
	register_post_type( 'questions', array(
		'labels'        => $faq_labels,
		'public'        => false,                 // без публичных URL
		'show_ui'       => true,                  // видно в админке
		'show_in_menu'  => true,
		'menu_position' => 20,
		'menu_icon'     => 'dashicons-editor-help',
		'show_in_rest'  => true,                  // удобнее редактировать
		'supports'      => array( 'title', 'editor' ),
		'has_archive'   => false,
		'rewrite'       => false,
	) );
}
