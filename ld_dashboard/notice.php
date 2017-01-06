<?php

add_action( 'admin_menu', 'ld_register_laywer_notice_page' );
function ld_register_laywer_notice_page(){
	add_menu_page(
		'Настройка уведомлений',
		'Настройка уведомлений',
		'lawyer',
		'notice',
		'lawyer_notice_menu_page',
		'dashicons-email',
		5
	);
}

function lawyer_notice_menu_page(){
?>
	<div class="wrap about-wrap">
		<div class="about-text">
		<?php _e('Подписка на уведомления.<br>'); ?>
		</div>
		<input type="checkbox">Комментарии на мои ответы</input></br>
		<input type="checkbox">Оставили отзыв об ответе</input></br>
		<input type="checkbox">Новые вопросы на сайте</input></br>
	</div>
<?php
}

?>