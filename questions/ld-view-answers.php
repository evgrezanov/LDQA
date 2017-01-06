<?php

 /* древовидные комментарии, подключаем стили что бы форма прыгала к нужному коменту при клике на ссылку  "ответить" */
function scripts_styles() {
	global $wp_styles;
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
	wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'scripts_styles' );

//выводим закрывающий див после формы комментирования
function ld_echo_div(){
	echo '</div>';
}
// привяжем функции к хуку (событию)
add_action('comment_form_after', 'ld_echo_div' );


/// функция вывода комментариев
function view_comment_begin($comment, $args, $depth){
   $GLOBALS['comment'] = $comment;
    $args_count = array(
		'user_id' => $comment->user_id,
			'count' => true
	);
	$comments_count = get_comments($args_count);
	$ld_photo = get_user_meta($comment->user_id,'ld_photo',true);
	if (!empty($ld_photo)) {
		$image_attributes = wp_get_attachment_image_src( $ld_photo );
	}
	if (!empty($comment->comment_parent)) { ?>

		<div class="item">
			<?php if (!empty($comment->user_id)) { ?><a href="<?php echo get_author_posts_url($comment->user_id); ?>" class="thumb"><img src="<?php echo $image_attributes[0] ?>" width="<?php echo $image_attributes[1] ?>" height="<?php echo $image_attributes[2] ?>"></a> <?php } else { ?>  <a href="#" class="thumb"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/ava.png"></a> <?php } ?>
			<div class="inner">
				<div class="inform"><?php if (!empty($comment->user_id)) { ?><a href="<?php echo get_author_posts_url($comment->user_id); ?>" class="author"><?php echo esc_attr(get_user_meta($comment->user_id, 'ld_secondname', true)); ?> <?php echo esc_attr(get_user_meta($comment->user_id, 'ld_name', true)); ?></a><?php } else { ?> <a href="#" class="author">Автор вопроса</a><?php } ?>
				<?php if ($comment->comment_approved == '0') : ?>
					<em>Ваш комментарий ожидает проверки.</em>
					<br />
				<?php endif; ?> <?php comment_text() ?></div>
				<div class="date"><?php printf( '%1$s в %2$s', get_comment_date(),  get_comment_time()) ?></div>
				<div class="reply">
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>
			</div>
		</div>

	<?php }else{ ?>
	<li <?php comment_class('answer'); ?> id="li-comment-<?php comment_ID(); ?>	">
			<div class="person">
				<div class="person_l">
					<a href="<?php echo get_author_posts_url($comment->user_id); ?>" class="thumb"><img src="<?php echo $image_attributes[0] ?>" width="<?php echo $image_attributes[1] ?>" height="<?php echo $image_attributes[2] ?>"></a>
					<span class="status online">онлайн</span>
				</div>
				<div class="person_r">
					<div class="name"><a href="<?php echo get_author_posts_url($comment->user_id); ?>"><?php echo esc_attr(get_user_meta($comment->user_id, 'ld_secondname', true)); ?> <?php echo esc_attr(get_user_meta($comment->user_id, 'ld_name', true)); ?></a>, <?php echo esc_attr(get_user_meta($comment->user_id, 'ld_city', true)); ?></div>
					<div>Стаж работы: <?php echo esc_attr(get_user_meta( $comment->user_id, 'ld_experience', true )); ?> лет</div>
					<div class="ans">Ответов: <a href="/"><?php echo $comments_count; ?></a></div>
					<div class="com">Отзывы: <a href="/">23</a> / <a href="/" class="minus">2</a></div>
				</div>
			</div>

			<div class="date"><?php printf( '%1$s в %2$s', get_comment_date(),  get_comment_time()) ?></div>

			<div class="text">
				<?php if ($comment->comment_approved == '0') : ?>
					<em>Ваш комментарий ожидает проверки.</em>
					<br />
				<?php endif; ?>
				<?php comment_text() ?>
			</div>
			<div class="caption"><span><?php echo esc_attr(get_user_meta( $comment->user_id, 'ld_byline', true )); ?></span></div>
			<?php if (get_current_user_id() == $comment->user_id or !is_user_logged_in() ) { ?>
			<div class="reply">
				<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</div>
			<?php }
	}
}
// ставим див в конце комента, к нему прыгает форма при нажатии клавиши ответить.
function view_comment_end($comment, $args, $depth){
   $GLOBALS['comment'] = $comment; ?>
   <article id="comment-<?php comment_ID(); ?>" class="comment"></article>
<?php
}



// функция доступа к форме коментирования только автору вопроса.
function answer_form_ld(){
	global $post;
	$ld_phone = sanitize_text_field($_POST['ld_phone']);
	if (!empty($ld_phone)) {
		setcookie( 'ld_phone', $ld_phone, time()+7*24*60*60, COOKIEPATH, COOKIE_DOMAIN );
		wp_redirect($_SERVER['REQUEST_URI']);
		exit;
	}
	if ($_COOKIE['ld_phone'] != get_post_meta($post->ID, 'ld_phone', true) ) {


		ob_start();
		?>	<div id="modal"></div>

			<div id="popup_1" class="popup">

				<span class="close"></span>

				<div class="title">Комментировать этот вопрос может лишь автор вопроса</div>

				<form action="" method="post">

					<div class="item">
						<span>Телефон: <b>*</b></span>
						<p>
							<input type="text" id="ld_phone" name="ld_phone" required="required" placeholder="+7(___)-___-__-__" value="" />
							Введите номер телефона, указанный при добавлении вопроса
							<script type="text/javascript">
								jQuery(function($){
										$("#ld_phone").mask("+7 (999) 999-99-99");
								});
							</script>
						</p>
					</div>

					<div class="aligncenter"><input type="submit" value="Отправить" class="btn_green" /></div>

				</form>

			</div>
			<div id="respond" class="comment-respond">
				<input type="button" id="popup_a" value="Коментировать" class="btn_green" />
				<script type="text/javascript">
					jQuery('#popup_a').click(function(){
						jQuery('#modal').fadeIn();
						jQuery('#popup_1').fadeIn();
					});
				</script>
			</div>
		<?php
		return ob_get_clean();
	}else{
		ob_start();
			comment_form(array(
				'title_reply'          => 'Задайте вопрос',
				'title_reply_before'   => '<div class="question_comments none"><h3 id="reply-title" class="comment-reply-title">',
				'title_reply_after'    => '</h3>',
				'comment_notes_before' => '',
				'comment_notes_after'  => '',
				'cancel_reply_before'  => ' ',
				'cancel_reply_after'   => ' ',

				'logged_in_as' => ' ',
				'class_submit' => 'btn_green',

				'comment_field' => '<div class="inner"><textarea id="comment" name="comment" cols="50" rows="4" placeholder="Остались вопросы? Напишите уточняющий вопрос юристу..." aria-required="true" required="required"></textarea>',

				'label_submit' => __('Отправить', 'bunyad'),

				'cancel_reply_link' => __('Отменить ответ', 'bunyad'),
				'submit_field'         => '<br><div class="form_bottom">%1$s %2$s</div></div>',


				'fields' => array(
					'author' => ' ',

					'email' => ' ',

					'url' => ' '
				),
			));
		return ob_get_clean();
	}
}
add_shortcode( 'answer_form_ld', 'answer_form_ld' );