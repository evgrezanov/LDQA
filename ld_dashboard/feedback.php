<?php

add_action( 'admin_menu', 'ld_register_laywer_feedback_page' );
function ld_register_laywer_feedback_page(){
	add_menu_page(
		'Отзывы обо мне',
		'Отзывы обо мне',
		'lawyer',
		'feedback',
		'lawyer_feedback_menu_page',
		'dashicons-admin-comments',
		4
	);
}

function lawyer_feedback_menu_page(){
?>
	<div class="wrap about-wrap">
		<div class="about-text">
		<?php _e('Здесь будут выведены отзывы о вас.<br>'); ?>
		</div>
	</div>
<?php
}

?>