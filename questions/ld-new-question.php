<?php


//подключение скриптов и стилей для формы
function wptuts_scripts_basic()
{
    // Register the script like this for a plugin:
    wp_register_script( 'formstyler', plugins_url( '/scripts/jquery.formstyler.min.js', __FILE__ ), array( 'jquery' ) );
    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'formstyler' );

        // Register the style like this for a plugin:
    wp_register_style( 'styleform', plugins_url( '/styles/styleform.css', __FILE__ ), array(), '20120208', 'all' );

    // For either a plugin or a theme, you can then enqueue the style:
    wp_enqueue_style( 'styleform' );
}
add_action( 'wp_enqueue_scripts', 'wptuts_scripts_basic' );

///шорткод вывода первой формы для вопроса.
// [pre_question action="page-id"]
function pre_question_func( $atts ) {
	extract( shortcode_atts( array(
		'action' => '',
	), $atts ) );
	ob_start(); ?>
		<div class="request">
			<div class="title"><span>Задайте вопрос эксперту-юристу бесплатно</span> и получите квалифицированный ответ и консультацию по телефону!</div>
			<form action="<?php echo get_page_link($action); ?>" method="post">
				<textarea cols="70" rows="10" name="ld_question_pod" placeholder="Опишите вашу ситуацию. Мы постараемся помочь вам."></textarea>
				<input type="submit" value="Задать вопрос" class="btn_green" />
			</form>
		</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'pre_question', 'pre_question_func' );

///шорткод вывода основной формы для вопроса.
// [question]
function question_func() {
	ob_start(); ?>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="answer_form" id="answer_form_ld">

			<div class="item">
				<span>Ваш вопрос: <b>*</b></span>
				<p>
					<input type="text" name="ld_question" required="required" value="<?php echo ( isset( $_POST['ld_question']) ? $ld_question : null ); ?>" />
					<b>Например: «Как подать в суд, если неизвестен адрес ответчика?», «Можно ли будет мне получить налоговый вычет за обучение в автошколе?»</b>
				</p>
			</div>

			<div class="item">
				<span>Подробное описание ситуации: </span>
				<p>
					<textarea cols="50" rows="5" name="ld_question_pod"><?php echo $_POST['ld_question_pod']; ?></textarea>
					<b>Чем подробнее вы разъясните ситуацию, тем более полными и точными будут ответы юристов</b>
				</p>
			</div>

			<div class="item">
				<span>Ваше имя:</span>
				<p>
					<input type="text" name="ld_name" value="<?php echo ( isset( $_POST['ld_name']) ? $ld_name : null ); ?>" />
					<b>Нигде не публикуется</b>
				</p>
			</div>

			<div class="item">
				<span>Регион: <b>*</b></span>
				<p>
					<?php ld_city_selectbox(); ?>
				</p>
				<script type="text/javascript">
					jQuery(function(){
						jQuery('select').styler({
							selectSearch: true
						});
					});
				</script>
			</div>

			<div class="item">
				<span>Телефон: <b>*</b></span>
				<p>
					<input type="text" id="ld_phone" name="ld_phone" required="required" placeholder="+7(___)-___-__-__" value="<?php echo ( isset( $_POST['ld_phone']) ? $ld_phone : null ); ?>" />
					<b>Не публикуется и может быть использован юристом для уточнения сути вопроса</b>
				</p>
				<script type="text/javascript">
					jQuery(function($){
							$("#ld_phone").mask("+7 (999) 999-99-99");
					});
				</script>
			</div>

			<div class="item">
				<span>E-mail: </span>
				<p>
					<input type="email" name="ld_email" value="<?php echo ( isset( $_POST['ld_email']) ? $ld_email : null ); ?>">
					<b>Не публикуется и может быть использован юристом для уточнения сути вопроса</b>
				</p>
			</div>

			<div class="form_bottom">

				<div id="output" class="captha"></div>

				<input type="submit" id="sub" value="Получить консультацию" class="btn_green" />

			</div>

			<div class="note">“В соответствии с Федеральным законом Российской Федерации от 27 июля 2006 г. N 152 "О персональных данных" - мы гарантируем полную анонимность всех консультаций“</div>
		</form>
		<script type="text/javascript">
			function ajax_go_ld(data, jqForm, options) { //ф-я перед отправкой запроса
			  	jQuery('#output').html('Идет отправка вашего вопроса...'); // в див для ответа напишем "отправляем.."
			  	jQuery('#sub').attr("disabled", "disabled"); // кнопку выключим
			}
			jQuery(document).ready(function(){ // после загрузки страницы
			  	add_form = jQuery('#answer_form_ld'); // запишем форму в переменную
			  	var options = { // опции для отправки формы с помощью jquery form
			  		data: { // дополнительные параметры для отправки вместе с данными формы
			  			action : 'add_question_ajax', // этот параметр будет указывать wp какой экшн запустить, у нас это wp_ajax_nopriv_add_object_ajax
			        	nonce: ajaxdata.nonce // строка для проверки, что форма отправлена откуда надо
			    	},
			      	dataType:  'json', // ответ ждем в json формате
			      	beforeSubmit: ajax_go_ld, // перед отправкой вызовем функцию ajax_go()
			      	success: function(data){ // сoбытиe пoслe удaчнoгo oбрaщeния к сeрвeру и пoлучeния oтвeтa
					       		if (data['success']) { // eсли oбрaбoтчик вeрнул oшибку
					       			jQuery('#sub').prop("disabled", false); // кнопку включим
									jQuery('#output').html(' '); //
									jQuery('#output').append('<!-- модальное окно --><div id="modal" style="display: block;position: fixed;"></div><div id="popup_3" style="display: block;position: fixed;" class="popup"><span id="close_md" class="close" ></span><div class="content"><div class="head">Спасибо! Ваш вопрос принят и ему присвоен номер '+data.data+'!</div><p>Мы гарантируем ответы только на объективно интересные, нужные, полно и корректно заданные вопросы. Вы получите уведомление на почту о поступивших ответах, либо юрист проконсультирует вас по телефону.</p><p>Вопрос будет находиться на рассмотрении 7 дней, если к концу этого периода ответ не поступит, то вопрос больше не будет рассматриваться юристами. Полные условия, сроки, правила и ограничения указаны в пользовательском соглашении.</p><p>Не на все вопросы возможно получить ответ в бесплатном режиме. В этом случае вы можете связаться с одним из экспертов и договориться о платном оказании услуг.</p></div><div class="aligncenter"><a href="/" class="btn_green">Вернуться на сайт</a></div></div>');

					       			console.log(data);
					       		} else { // eсли всe прoшлo oк
					       			jQuery('#sub').prop("disabled", false); // кнопку включим
									jQuery('#output').html(' '); //
									jQuery('#output').append('<!-- модальное окно --><div id="modal" onclick="jQuery(\'#output\').html(\'\');" style="display: block;position: fixed;"></div><div id="popup_3" style="display: block;position: fixed;" class="popup"><span id="close_md" onclick="jQuery(\'#output\').html(\'\');" class="close"></span><div class="content"><div class="head">'+data.data+'!</div></div><div class="aligncenter"></div></div>');

					       			console.log(data);
					       		}
					         }, // после получении ответа вызовем response_go()
			      	error: function(request, status, error) { // в случае ошибки
			        	console.log(arguments); // напишем все в консоль
			      	},
			      	url: ajaxdata.url // куда слать форму, переменную с url мы определили вывели в нулевом шаге
			  };
			  add_form.ajaxForm(options); // подрубаем плагин jquery form с опциями на нашу форму
			});
		</script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'question', 'question_func' );


///////////////////////
add_action( 'wp_ajax_nopriv_add_question_ajax', 'add_question' ); // крепим на событие wp_ajax_nopriv_add_object_ajax, где add_object_ajax это параметр action, который мы добавили в перехвате отправки формы, add_object - ф-я которую надо запустить
add_action('wp_ajax_add_question_ajax', 'add_question'); // если нужно чтобы вся бадяга работала для админов
function add_question() {

	$reg_errors = new WP_Error;

	$nonce = $_POST['nonce']; // берем переданную формой строку проверки

	if (!wp_verify_nonce($nonce, 'add_object')) { // проверяем nonce код, второй параметр это аргумент из wp_create_nonce
		$reg_errors->add('nonce_none', 'Данные отправлены с левой страницы ');
	}

	// запишем все поля
	$ld_question      =   sanitize_text_field($_POST['ld_question']);
    $ld_email            =   sanitize_email( $_POST['ld_email'] );
    $ld_question_pod  =   sanitize_text_field($_POST['ld_question_pod']);
    $ld_name          =   sanitize_text_field($_POST['ld_name']);
    $ld_city          =   sanitize_text_field($_POST['ld_city']);
    $ld_phone         =   sanitize_text_field($_POST['ld_phone']);

	/*почта*/
	if ( !empty( $ld_email ) ) {
    	if ( !is_email( $ld_email ) ) {
    		$reg_errors->add( 'email_invalid', 'Вы ввели недопустимый адрес электронной почты!' );
		}
	}

	/*ld_question*/
	if ( empty( $ld_question ) ) {
    	$reg_errors->add('ld_question_none', 'Вопрос обязателен для заполнения!');
	}


	/*ld_city*/
	if ( empty( $ld_city ) ) {
    	$reg_errors->add('ld_city_none', 'Регион обязателен для заполнения!');
	}
	/*ld_phone*/
	if ( empty( $ld_phone ) ) {
    	$reg_errors->add('ld_phone_none', 'Телефон обязателен для заполнения!');
	}

	if ( is_wp_error( $reg_errors ) ) {

		$er = '0';

	    foreach ( $reg_errors->get_error_messages() as $error ) {

	    	if( $er >= '1') continue;
	    	$er = $er + 1;

	        $errors_ld = $error;
	    }
	}

	if (!$errors_ld) { // если с полями все ок, значит можем добавлять пост
		$fields = array( // подготовим массив с полями поста, ключ это название поля, значение - его значение
			'post_type' => 'questions', // нужно указать какой тип постов добавляем
	    	'post_title'   => $ld_question, // заголовок поста
	        'post_content' => $ld_question_pod, // контент
	        'post_status'  => 'draft',
	        'post_excerpt' => $ld_question_pod,
	        'comment_status' => 'open'
	    );
	    $post_id = wp_insert_post($fields); // добавляем пост в базу и получаем его id

	    update_post_meta($post_id, 'ld_city', $ld_city);
	    update_post_meta($post_id, 'ld_phone', $ld_phone);
	    update_post_meta($post_id, 'ld_email', $ld_email);
	    update_post_meta($post_id, 'ld_name', $ld_name);
	    if ( !empty( $ld_email ) ) {
	    	sendmail_ldquestion($post_id, $ld_email);
	    }
	}

	if ($errors_ld){
		wp_send_json_error($errors_ld); // если были ошибки, выводим ответ в формате json с success = false и умираем
	}else{
		wp_send_json_success($post_id); // если все ок, выводим ответ в формате json с success = true и умираем
	}

	die(); // умрем еще раз на всяк случ
}

function sendmail_ldquestion($id, $email){
	// удалим фильтры, которые могут изменять заголовок $headers
		remove_all_filters( 'wp_mail_from' );
		remove_all_filters( 'wp_mail_from_name' );

		$post = get_post($id);

		$title = $post->post_title;

		ob_start();
		?>
Добрый день! Вы задали вопрос №<?php echo $id; ?>: “<?php echo $title; ?>”<br>

Ссылка на вопрос <a target="_blank" href="<?php echo get_permalink($id, true); ?>"><?php echo get_permalink($id, true); ?></a> . Здесь вы можете просмотреть поступающие на на него ответы юристов.<br>

Мы гарантируем ответы только на объективно интересные, полно и корректно заданные вопросы. Вы получите уведомление на эту почту о поступивших ответах, либо юрист проконсультирует вас по телефону.<br>
Вопрос будет находиться на рассмотрении 7 дней, если к концу этого периода ответ от юристов не поступит, то вопрос больше не будет рассматриваться юристами. Полные условия, сроки, правила и ограничения указаныв пользовательском соглашении.<br>
Не на все вопросы возможно получить ответ в бесплатном режиме. В этом случае вы можете связаться с одним из экспертов с помощью онлайн-чата или формы и договориться о платном оказании услуг.<br>
Вы получили это сообщение, так как указали свой адрес email при размещении вопроса на law-divorce.ru. Если Вы не размещали вопрос и письмо попало к вам по ошибке - просто проигнорируйте данное письмо.<br>
		<?php

		$ld_mail = ob_get_clean();

		$headers[] = 'From: law-divorce.ru <s@law-divorce.ru>';  ////// заголовки поправить
		$headers[] = 'content-type: text/html';

		wp_mail( $email, 'Ваш вопрос №'.$id.' на сайте Law-divorce.ru принят', $ld_mail, $headers);
}