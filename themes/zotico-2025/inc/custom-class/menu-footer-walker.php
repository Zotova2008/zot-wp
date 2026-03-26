<?php

class menu_footer extends Walker_Nav_Menu {
	public $classMenu = 'footer__menu';
	public $classSubUl = array( 'drop__list' );

	public $classLi = 'footer__menu-item';
	public $classSubLi = 'drop__item';

	public $classA = 'footer__menu-link';
	public $classA2 = 'link';
	public $classSubA = 'drop__link';

	public $classLiParent = 'drop';
	public $classLiParentA = 'drop__main-link link';

	public $classSubContainer = '<div class="main-nav__drop drop__container">';

	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );
	public $db_fields = array(
		'parent' => 'menu_item_parent',
		'id' => 'db_id',
	);

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );

		// Класс сабменю ul $this->classSubUl;
		$classes = $this->classSubUl;

		$class_names = implode( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );

		$atts          = array();
		$atts['class'] = !empty( $class_names ) ? $class_names : '';

		$atts       = apply_filters( 'nav_menu_submenu_attributes', $atts, $args, $depth );
		$attributes = $this->build_atts( $atts );
		// Добавляем обвертку для сабменю {$this->classSubContainer}
		$output .= "{$n}{$indent}{$this->classSubContainer}{$n}{$indent}<ul{$attributes}>";
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth );
		$output .= "{$n}{$indent}</ul>{$n}{$indent}</div>{$n}";
	}

	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		$menu_item = $data_object;

		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		/*
		* Некоторые из параметров объекта $item
		* ID - ID самого элемента меню, а не объекта на который он ссылается
		* menu_item_parent - ID родительского элемента меню
		* classes - массив классов элемента меню
		* post_date - дата добавления
		* post_modified - дата последнего изменения
		* post_author - ID пользователя, добавившего этот элемент меню
		* title - заголовок элемента меню
		* url - ссылка
		* attr_title - HTML-атрибут title ссылки
		* xfn - атрибут rel
		* target - атрибут target
		* current - равен 1, если является текущим элементом
		* current_item_ancestor - равен 1, если текущим (открытым на сайте) является вложенный элемент данного
		* current_item_parent - равен 1, если текущим (открытым на сайте) является родительский элемент данного
		* menu_order - порядок в меню
		* object_id - ID объекта меню
		* type - тип объекта меню (таксономия, пост, произвольно)
		* object - какая это таксономия / какой тип поста (page /category / post_tag и т д)
		* type_label - название данного типа с локализацией (Рубрика, Страница)
		* post_parent - ID родительского поста / категории
		* post_title - заголовок, который был у поста, когда он был добавлен в меню
		* post_name - ярлык, который был у поста при его добавлении в меню
		*/
		$itemClass = ( $depth > 0 ) ? $this->classSubLi : $this->classLi;

		$classes[] = $itemClass;
		// Классы для li внутри которого сабменю
		if ( $args->walker->has_children ) {
			$classes[] = $this->classLiParent;
		}
		if ( $menu_item->current ) {
			$classes[] = "{$itemClass}--current";
		}
//		if ( $menu_item->current_item_ancestor ) {
//			$classes[] = "{$itemClass}--current-ancestor";
//		}
//		if ( $menu_item->current_item_parent ) {
//			$classes[] = "{$itemClass}--current-parent";
//		}
//		if ( $menu_item->object ) {
//			$classes[] = "{$itemClass}--{$menu_item->object}";
//		}

		if ( is_single() ) {
			$category  = get_the_category();
			$cat_id    = $category[0]->cat_ID;
			$ancestors = get_ancestors( $cat_id, 'category' );
			if ( in_array( $menu_item->object_id, $ancestors ) ) {
				$classes[] = "{$itemClass}--active-ancestor";
			}
		}

		$args = apply_filters( 'nav_menu_item_args', $args, $menu_item, $depth );

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $menu_item, $args, $depth ) );

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $menu_item->ID, $menu_item, $args, $depth );

		$li_atts          = array();
		$li_atts['id']    = !empty( $id ) ? $id : '';
		$li_atts['class'] = !empty( $class_names ) ? $class_names : '';

		$li_atts       = apply_filters( 'nav_menu_item_attributes', $li_atts, $menu_item, $args, $depth );
		$li_attributes = $this->build_atts( $li_atts );

		$output .= "{$n}{$indent}<li{$li_attributes}>";

		$linkClass   = ( $depth > 0 ) ? $this->classSubA : $this->classA;
		$currentLink = $menu_item->current ? "{$linkClass}--current" : '';

		$linkClassArray = $linkClass . ' ' . $this->classA2 . ' ' . $currentLink;
		$linkClassArray = trim( $linkClassArray );

		$atts           = array();
		$atts['class']  = $linkClassArray;
		$atts['title']  = !empty( $menu_item->attr_title ) ? $menu_item->attr_title : '';
		$atts['target'] = !empty( $menu_item->target ) ? $menu_item->target : '';
		if ( '_blank' === $menu_item->target && empty( $menu_item->xfn ) ) {
			$atts['rel'] = 'noopener';
		} else {
			$atts['rel'] = $menu_item->xfn;
		}

		if ( !empty( $menu_item->url ) ) {
			if ( get_privacy_policy_url() === $menu_item->url ) {
				$atts['rel'] = empty( $atts['rel'] ) ? 'privacy-policy' : $atts['rel'] . ' privacy-policy';
			}

			$atts['href'] = $menu_item->current ? '' : $menu_item->url;
		} else {
			$atts['href'] = '';
		}

		$atts['aria-current'] = $menu_item->current ? 'page' : '';

		$atts       = apply_filters( 'nav_menu_link_attributes', $atts, $menu_item, $args, $depth );
		$attributes = $this->build_atts( $atts );

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $menu_item->title, $menu_item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $menu_item, $args, $depth );

		$item_output = $args->before;
		$item_output .= "{$n}{$indent}<a{$attributes}><span>";
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= "</span></a>{$n}";
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $menu_item, $depth, $args );
	}
}
