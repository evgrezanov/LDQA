<?php

add_action( 'admin_menu', 'ld_register_laywer_questions_page' );
function ld_register_laywer_questions_page(){
	add_menu_page(
		'Новые вопросы',
		'Новые вопросы',
		'lawyer',
		'new_questions',
		'lawyer_questions_menu_page',
		'dashicons-admin-media',
		2
	);
}

function lawyer_questions_menu_page(){
?>
	<div class="wrap about-wrap">
		<div class="about-text">
		<?php _e('Новые вопросы<br>');
		ld_view_questions_form(); ?>
		</div>
	</div>
<?php
}

?>