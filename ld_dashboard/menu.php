<?php

// to do причесать функции в этом файле

add_action( 'admin_menu', 'ld_remove_menu_items' );
function ld_remove_menu_items() {
    if( is_user_role( 'lawyer' ) ) {
    	// Удаляем пункт "Консоль"
    	remove_menu_page('index.php'); 
    	// Удаляем пункт "Профиль"
    	remove_menu_page('profile.php'); 
    	// Комментарии
    	remove_menu_page('edit-comments.php');
		//Инструменты
    	remove_menu_page('tools.php');
    	// Записи
    	remove_menu_page('edit.php');
    	// Добавление записи
    	remove_menu_page('post-new.php');
    	// Контактная форма
    	remove_menu_page( 'wpcf7' );
    }
}


// http://web-programming.com.ua/kak-pomenyat-privetstvie-v-adminke-wordpress/
// http://www.wphook.ru/menu/wp-toolbar.html
add_filter( 'admin_bar_menu', 'ld_replace_admintext', 25);
add_action( 'wp_before_admin_bar_render', 'ld_replace_admintext');
function ld_replace_admintext( $wp_admin_bar ) {
	
	if( is_user_role( 'lawyer' ) ) {
		
		global $wp_admin_bar;

		// удалим ненужные
		$wp_admin_bar->remove_menu('wp-logo');
    	$wp_admin_bar->remove_menu('about');
    	$wp_admin_bar->remove_menu('wporg');
    	$wp_admin_bar->remove_menu('documentation');
    	$wp_admin_bar->remove_menu('support-forums');
    	$wp_admin_bar->remove_menu('feedback');
    	$wp_admin_bar->remove_menu('view-site');
    	//action
    	$wp_admin_bar->remove_menu('comments');
    	//$wp_admin_bar->remove_menu('site-name');
    	$wp_admin_bar->remove_menu('new-content');
    	$wp_admin_bar->remove_menu('wpseo-menu');

		$user_id = get_current_user_id();

		// изменение приветствия
		$name = get_user_meta($user_id, 'ld_name', true);
		$ld_patronymic = get_user_meta($user_id, 'ld_patronymic', true);
		
		$my_account=$wp_admin_bar->get_node('my-account');
		$newtitle = $name.' '.$ld_patronymic.', Ваш кабинет здесь';
		/*
		$url = admin_url( 'edit-tags.php?taxonomy=category', 'https' );
		echo $url;
		// выведет: https://www.example.com/wp-admin/edit-tags.php?taxonomy=category
		*/
		$newlink = admin_url('admin.php?page=your_information');

		// подмена ссылки на прояиль
		$wp_admin_bar->add_node( array(
		        'id' 	=> 'my-account',
		        'title' => $newtitle,
		        'href'	=>$newlink,
			) );

		$wp_admin_bar->add_node( array(
		        'id' 	=> 'edit-profile',
		        'href'	=>$newlink,
			) );

		//изменение аватарки
		/*если картинка была загружена ранее то выведем ее*/
		$ld_photo = get_user_meta($user_id,'ld_photo',true);
		if (!empty($ld_photo)) {
			$image_attributes = wp_get_attachment_image_src( $ld_photo );
			$image_url = $image_attributes[0];
			$image_width = $image_attributes[1];
			$image_height = $image_attributes[2];

			// соберем title

			$img = '<img alt="" src="'.$image_url.'" class="avatar avatar-64 photo" height="64" width="64">';

			$wp_admin_bar->add_node( array(
				'id' => 'user-info',
				'title' => $img,
				'href'	=>$newlink,
				));
		}
		//print_r($wp_admin_bar);
	}
}

?>