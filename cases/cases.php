<?php

//регистрируем новый тип поста
add_action('init', 'ld_register_cases_post_types');
function ld_register_cases_post_types(){
	$args = array(
		'label'  => 'cases',
		'labels' => array(
			'name'               => 'Успешные дела', // основное название для типа записи
			'singular_name'      => 'Успешное дело', // название для одной записи этого типа
			'add_new'            => 'Добавить новое', // для добавления новой записи
			'add_new_item'       => 'Добавить новое дело', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактировать дело', // для редактирования типа записи
			'new_item'           => 'Новое дело', // текст новой записи
			'view_item'          => 'Просмотреть дело', // для просмотра записи этого типа.
			'search_items'       => 'Найти дело', // для поиска по этим типам записи
			'not_found'          => 'Дел не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'В корзине дел не найдено', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родительских типов. для древовидных типов
			'menu_name'          => 'Успешные дела', // название меню
		),
		'description'         => '',
		'public'              => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 2,
		'menu_icon'           => 'dashicons-admin-media',
		//'capability_type'   => 'post', // to do отредактировать http://wp-kama.ru/function/register_post_type#capability_type-stroka-massiv
		'capabilities'     	  => 'manage_options', // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => array('title','editor', 'author'),
		//'taxonomies'          => array('specialization'),
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
		'show_in_nav_menus'   => null,
	);

	register_post_type('cases', $args );
}


?>
