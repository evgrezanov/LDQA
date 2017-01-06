<?php

add_action( 'admin_menu', 'ld_register_laywer_answears_page' );
function ld_register_laywer_answears_page(){
	add_menu_page(
		'Мои ответы',
		'Мои ответы',
		'lawyer',
		'your_answears',
		'lawyer_answears_menu_page',
		'dashicons-admin-page',
		3
	);
}

function lawyer_answears_menu_page(){
?>
	<div class="wrap about-wrap">
		<div class="about-text">
		<?php _e('Здесь будут выведены все ваши ответы.<br>'); ?>
		</div>
	</div>
<?php
}

?>