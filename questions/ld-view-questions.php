<?php
//функция вывода новых вопросов
add_action( 'wp_ajax_nopriv_ld_view_questions', 'ld_view_questions' ); // крепим на событие wp_ajax_nopriv_add_object_ajax, где add_object_ajax это параметр action, который мы добавили в перехвате отправки формы, add_object - ф-я которую надо запустить
add_action('wp_ajax_ld_view_questions', 'ld_view_questions'); // если нужно чтобы вся бадяга работала для админов
function ld_view_questions(){
	ob_start();
	$nonce = $_POST['nonce']; // берем переданную формой строку проверки
	if (!wp_verify_nonce($nonce, 'add_object')) { // проверяем nonce код, второй параметр это аргумент из wp_create_nonce
		die();
	}

	$args = array(
		'numberposts'     => -1, // тоже самое что posts_per_page
		'orderby'         => 'post_date',
		'order'           => 'DESC',
		'post_type'       => 'questions',
		'post_status'     => 'publish',
		'date_query' => array(
							'after' => '1 week ago',
						),
	);
	if ($_POST['cat'] != '0') {
		$args['tax_query'][] = array(
						      'taxonomy'  => 'specialization', // слаг таксономии
						       'field'     => 'slug', // по полю slug
						       'terms' => $_POST['cat'], // слаг термина
							);
	}

	$posts = get_posts($args);

	foreach( $posts as $post ){?>
		<div><a target="_blank" href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_title($post->ID);?></a></div>
	    <?php
	}
	$rtr = ob_get_clean();
	wp_send_json_success($rtr);
	die();
}

function ld_view_questions_form(){ ?>
	<form id="ld_questions_form" method="post">
		<div>
			<?php
			wp_dropdown_categories('show_option_all=Все специализации&show_count=0&orderby=name&value_field=slug&taxonomy=specialization&hierarchical=1&hide_empty=0');
			?>

			<input id="sub_questions" type="submit" name="submit" value="Показать" />
		</div>
	</form>
	<div id="questions_output"> <?php echo ld_view_questions_noajax(); ?></div>
	<script type="text/javascript">
			function ajax_go_q(data, jqForm, options) { //ф-я перед отправкой запроса
			  	jQuery('#questions_output').html('Получаем вопросы...'); // в див для ответа напишем
			  	jQuery('#sub_questions').attr("disabled", "disabled"); // кнопку выключим
			}
			function response_go_q(out)  { // ф-я обработки ответа от wp, в out будет элемент success(bool), который зависит от ф-и вывода которую мы использовали в обработке(wp_send_json_error() или wp_send_json_success()), и элемент data в котором будет все что мы передали аргументом к ф-и wp_send_json_success() или wp_send_json_error()
				console.log(out); // для дебага
				jQuery('#sub_questions').prop("disabled", false); // кнопку включим
				jQuery('#questions_output').html(out.data); // выведем результат
			}
			jQuery(document).ready(function(){ // после загрузки страницы
			  	add_form_q = jQuery('#ld_questions_form'); // запишем форму в переменную
			  	var options = { // опции для отправки формы с помощью jquery form
			  		data: { // дополнительные параметры для отправки вместе с данными формы
			  			action : 'ld_view_questions', // этот параметр будет указывать wp какой экшн запустить, у нас это wp_ajax_nopriv_add_object_ajax
			        	nonce: ajaxdata.nonce // строка для проверки, что форма отправлена откуда надо
			    	},
			      	beforeSubmit: ajax_go_q, // перед отправкой вызовем функцию ajax_go()
			      	success: response_go_q, // после получении ответа вызовем response_go()
			      	error: function(request, status, error) { // в случае ошибки
			        	console.log(arguments); // напишем все в консоль
			      	},
			      	url: ajaxdata.url // куда слать форму, переменную с url мы определили вывели в нулевом шаге
			  };
			  add_form_q.ajaxForm(options); // подрубаем плагин jquery form с опциями на нашу форму
			});
		</script>

	<?php
}

function ld_view_questions_noajax(){
	ob_start();

	$args = array(
		'numberposts'     => -1, // тоже самое что posts_per_page
		'orderby'         => 'post_date',
		'order'           => 'DESC',
		'post_type'       => 'questions',
		'post_status'     => 'publish',
		'date_query' => array(
							'after' => '1 week ago',
						),
	);

	$posts = get_posts($args);

	foreach( $posts as $post ){?>
		<div><a target="_blank" href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_title($post->ID);?></a></div>
	    <?php
	}
	$rtr = ob_get_clean();
	return $rtr;
}