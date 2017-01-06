<?php

/**
 * Plugin Name:         LD Questions-Answears
 * Plugin URI:          http://wphire.ru
 * Description:         Функционал вопросов-ответов для law-divorce.ru.
 * Author:              WPhire
 * Author URI:          http://wphire.ru
 * Version:             1.0
 **/


/*to do отрефакторить все что ниже в класс*/


//подключаем роль 'юрист'
function ld_add_roles_on_plugin_activation() {
	$result = add_role('lawyer', 'Юрист', array( 'read' => true) );
	}
register_activation_hook( __FILE__, 'ld_add_roles_on_plugin_activation' );

//функция для проверки роли пользователя
//см. заметки в http://wp-kama.ru/function/current_user_can
function is_user_role( $role, $user_id = null ) {
	$user = is_numeric( $user_id ) ? get_userdata( $user_id ) : wp_get_current_user();

	if( ! $user )
		return false;

	return in_array( $role, (array) $user->roles );
}


function ld_redirect_users_by_role() {

        $current_user   = wp_get_current_user();
        $role_name      = $current_user->roles[0];

        if ( 'lawyer' === $role_name ) {
            wp_redirect(admin_url('/admin.php?page=your_information'));
         	//wp_redirect( 'http://test1.law-divorce.ru/' );
        }

}
add_action( 'load-index.php', 'ld_redirect_users_by_role' );


// подключаем админку юриста

//Ваши данные
include_once('ld_dashboard/information.php');
//Города
// to do города надо привести в один файл, желательно с select2 и optgroup
include_once('ld_dashboard/cityselectbox.php');
//Новые вопросы
//include_once('ld_dashboard/questions.php');
//Мои ответы
include_once('ld_dashboard/answears.php');
//Отзывы обо мне
include_once('ld_dashboard/feedback.php');
//Настройка уведомлений
include_once('ld_dashboard/notice.php');
//удаляем ненужные юристу пункты меню
include_once('ld_dashboard/menu.php');
//подключаем форму для добавления "успешных дел"
include_once('ld_dashboard/ld-cases.php');
// вывод новых вопросов в админке
include_once('questions/ld-view-questions.php');


// шорткод формы вопросов
include_once('questions/ld-new-question.php');


// подключаем вывод ответов на вопросы (вывод коментариев к вопросам)
include_once('questions/ld-view-answers.php');


//подключаем форму регистрации
include_once('regform/ld-regform.php');
include_once('regform/recaptcha.php');
include_once('regform/cityselectbox_for_form.php');


//подключаем функцию вывода чекбоксов
include_once('regform/ld-specializations.php');


//подключаем api типа поста "вопросы" и таксономию "специализация"
include_once('questions/question.php');
//подключаем api типа поста "успешные дела"
include_once('cases/cases.php');
 ?>