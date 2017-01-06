<?php

// регистрируем таксономию
add_action('init', 'ld_create_taxonomy');
function ld_create_taxonomy(){
	// заголовки
	$labels = array(
		'name'              => 'Специализации',
		'singular_name'     => 'Специализация',
		'search_items'      => 'Найти специализацию',
		'all_items'         => 'Все специализации',
		'parent_item'       => 'Родительская специализация',
		'parent_item_colon' => 'Родительская специализация:',
		'edit_item'         => 'Редактировать специализацию',
		'update_item'       => 'Обновить специализацию',
		'add_new_item'      => 'Добавить специализацию',
		'new_item_name'     => 'Название новой специализации',
		'menu_name'         => 'Специализация',
	);
	// параметры
	$args = array(
		'label'                 => '', // определяется параметром $labels->name
		'labels'                => $labels,
		'public'                => true,
		'publicly_queryable'    => null, // равен аргументу public
		'show_in_nav_menus'     => true, // равен аргументу public
		'show_ui'               => true, // равен аргументу public
		'show_tagcloud'         => true, // равен аргументу show_ui
		'hierarchical'          => true,
		'update_count_callback' => '',
		'rewrite'               => array(
			'slug'=>'specialization',
			'hierarchical'=>true
			),
		'query_var'             => true, // название параметра запроса
		'capabilities'          => array('manage_options'),
		'meta_box_cb'           => null, // callback функция. Отвечает за html код метабокса (с версии 3.8): post_categories_meta_box или post_tags_meta_box. Если указать false, то метабокс будет отключен вообще
		'show_admin_column'     => false, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
		'_builtin'              => false,
		'show_in_quick_edit'    => 'show_ui', // по умолчанию значение show_ui
	);
	register_taxonomy('specialization', array('questions'), $args );
}


//регистрируем новый тип поста
//to do rewrite переделать для нормального slug
add_action('init', 'ld_register_post_types');
function ld_register_post_types(){
	$args = array(
		'label'  => 'questions',
		'labels' => array(
			'name'               => 'Вопросы', // основное название для типа записи
			'singular_name'      => 'Вопрос', // название для одной записи этого типа
			'add_new'            => 'Добавить новый', // для добавления новой записи
			'add_new_item'       => 'Добавить новый вопрос', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактировать вопрос', // для редактирования типа записи
			'new_item'           => 'Новый вопрос', // текст новой записи
			'view_item'          => 'Просмотреть вопрос', // для просмотра записи этого типа.
			'search_items'       => 'Найти вопрос', // для поиска по этим типам записи
			'not_found'          => 'Вопросов не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'В корзине вопросов не найдено', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родительских типов. для древовидных типов
			'menu_name'          => 'Новые вопросы', // название меню
		),
		'description'         => '',
		'public'              => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 2,
		'menu_icon'           => 'dashicons-format-chat',
		//'capability_type'     => 'page', // to do отредактировать http://wp-kama.ru/function/register_post_type#capability_type-stroka-massiv
		//'capabilities'        => array('manage_options', 'lawyer'), // массив дополнительных прав для этого типа записи
		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,
		'supports'            => array('title','editor','comments'),
		'taxonomies'          => array('specialization'),
		'has_archive'         => true,
		'rewrite'             => true,
		'query_var'           => true,
		'show_in_nav_menus'   => null,
	);

	register_post_type('questions', $args );
}

//https://wpcafe.org/tutorials/custom-post-types-polzovatelskie-taksonomii-filtryi-i-arhivyi-v-wordpress/
add_action( 'restrict_manage_posts', 'ld_filter_list' );
function ld_filter_list() {
    $screen = get_current_screen();
    global $wp_query;
    if ( $screen->post_type == 'questions' ) {
        wp_dropdown_categories( array(
            'show_option_all' => 'Все специализации',
            'taxonomy' => 'specialization',
            'name' => 'specialization',
            'orderby' => 'name',
            'selected' => ( isset( $wp_query->query['specialization'] ) ? $wp_query->query['specialization'] : '' ),
            'hierarchical' => true,
            'depth' => 3,
            'show_count' => false,
            'hide_empty' => true,
        ) );
    }
}
//https://wpcafe.org/tutorials/custom-post-types-polzovatelskie-taksonomii-filtryi-i-arhivyi-v-wordpress/
add_filter( 'parse_query','ld_perform_filtering' );
function ld_perform_filtering( $query ) {
    $qv = &$query->query_vars;
    if ( ( $qv['specialization'] ) && is_numeric( $qv['specialization'] ) ) {
        $term = get_term_by( 'id', $qv['specialization'], 'specialization' );
        $qv['specialization'] = $term->slug;
    }
}


// колонка "ID" для постов и страниц в админке
// http://wp-kama.ru/id_995/dopolnitelnyie-sortiruemyie-kolonki-u-postov-v-adminke.html
add_filter('manage_posts_columns', 'ld_posts_add_col');
add_filter('manage_pages_columns', 'ld_posts_add_col');

add_action('manage_pages_custom_column', 'ld_posts_show_id',5,2);
add_action('manage_posts_custom_column', 'ld_posts_show_id',5,2);
function ld_posts_add_col($defaults) {
  	// to do добавить проверку на post_type=questions
  	$my_post_type = array('questions');
    global $post;
    if (!in_array($post->post_type, $my_post_type)) return $defaults;

  	$defaults['wps_post_id'] = __('Номер');
  	$defaults['wps_post_except'] = __('Описание');

  	return $defaults;
}

function ld_posts_show_id($column_name, $id) {
  	// to do добавить проверку на post_type=questions
  	$my_post_type = array('questions');
    global $post;
    if (!in_array($post->post_type, $my_post_type)) return;

  	if ($column_name === 'wps_post_id') echo $id;
  	if ($column_name === 'wps_post_except') echo get_the_excerpt($id);
  	//if ($column_name === 'comments') echo 'комментарий';
}

function ld_posts_id_style() {
  	// to do добавить проверку на post_type=questions
  	$my_post_type = array('questions');
    global $post;
    if (!in_array($post->post_type, $my_post_type)) return;

  	print '<style>#wps_post_id{width:4em}#wps_post_except{width:22em}</style>';
}

function xko_seo_columns_filter( $columns ) {
    unset($columns['wpseo-score']);
    unset($columns['wpseo-title']);
    unset($columns['wpseo-metadesc']);
    unset($columns['wpseo-focuskw']);
    unset($columns['ratings']);
    return $columns;
}

function xko_hide_seo_filter_box(){
        $my_post_type = array('questions');
        global $post;
        if (in_array($post->post_type, $my_post_type)) {
            echo '
                <style type="text/css">
                   #posts-filter .tablenav select[name=seo_filter] {
                        display:none;
                    }
                </style><script type="text/javascript">
jQuery(document).ready(function(){
    jQuery(".post-com-count").click(function() { return false; });
});
</script>
            ';
        }
}



// удаление ссылок со статусами
function mine_pages_only($views) {

unset($views['draft']);
unset($views['all']);
unset($views['draft']);
unset($views['pending']);
unset($views['trash']);
unset($views['publish']);
unset($views['mine']);


return $views;
}

function only_own_pages_parse_query( $wp_query ) {
	if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/edit.php' ) !== false ) {
		global $current_user;
		$wp_query->set( 'post_status', 'publish' );
	}
}
function ld_customview(){
	if (current_user_can('lawyer')) {
		add_filter('views_edit-questions', 'mine_pages_only');
		add_filter('parse_query', 'only_own_pages_parse_query' );

		// убираем кнопку добавить новый
		add_action('admin_menu', 'disable_new_posts');

		add_action('admin_head-edit.php', 'xko_hide_seo_filter_box');

		add_filter( 'manage_edit-questions_columns', 'xko_seo_columns_filter',10, 1 );

		add_action('admin_print_styles-edit.php', 'ld_posts_id_style');
	}
}
add_filter('init', 'ld_customview');



// убираем кнопку добавить новый
function disable_new_posts() {
// Hide sidebar link
global $submenu;
unset($submenu['edit.php?post_type=questions'][10]);

// Hide link on listing page
if (isset($_GET['post_type']) && $_GET['post_type'] == 'questions') {
    echo '<style type="text/css">
    .page-title-action, .alignleft.actions.bulkactions { display:none; }
    #cb-select-all-1, #cb-select-all-2 { display:none; }
    </style>';
}
}
?>