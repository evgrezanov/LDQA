<?php
// здесь будут функции добавления успешных дел.

//готовим ВП для работы с Аяксом
add_action('wp_print_scripts','include_scripts'); // действие в котором прикрепим необходимые js скрипты и передадим данные
function include_scripts(){
        wp_enqueue_script('jquery'); // добавим основную библиотеку jQuery
        wp_enqueue_script('jquery-form'); // добавим плагин jQuery forms, встроен в WP

        wp_localize_script( 'jquery', 'ajaxdata', // функция для передачи глобальных js переменных на страницу, первый аргумент означет перед каким скриптом вставить переменные, второй это название глобального js объекта в котором эти переменные будут храниться, последний аргумент это массив с самими переменными
			array(
   				'url' => admin_url('admin-ajax.php'), // передадим путь до нативного обработчика аякс запросов в wp, в js можно будет обратиться к ней так: ajaxdata.url
   				'nonce' => wp_create_nonce('add_object') // передадим уникальную строку для механизма проверки аякс запроса, ajaxdata.nonce
			)
		);
}

// Выводим форму
function ld_new_case_form(){
	ob_start();?>
		<form method="post" enctype="multipart/form-data" id="add_object">
		<tr>
	    	<th><label for="post_title_case">Заголовок дела</label></th>
	    	<td><input type="text" name="post_title_case" required/></td>
	    </tr>

	    <tr>
	    	<th><label for="post_content_case">Описание дела</label></th>
	    	<td><textarea name="post_content_case" id="post_content_case_id" required/></textarea></td>
	    </tr>

	    <tr>
	    	<th><label for="doc_case">Документ</label></th>
	    	<td><input type="file" name="doc_case"/></td>
	    </tr>

	    <tr>
	    	<th><input type="submit" name="button" value="Добавить" id="sub"/></th>
	    </tr>

	    <tr id="output_s" >
	    	<th id="output"></th><?php // сюда будем выводить ответ ?>
	    </tr>

		</form>
		<script type="text/javascript">
			function ajax_go(data, jqForm, options) { //ф-я перед отправкой запроса
			  	jQuery('#output').html('Отправляем...'); // в див для ответа напишем "отправляем.."
			  	jQuery('#sub').attr("disabled", "disabled"); // кнопку выключим
			}
			function response_go(out)  { // ф-я обработки ответа от wp, в out будет элемент success(bool), который зависит от ф-и вывода которую мы использовали в обработке(wp_send_json_error() или wp_send_json_success()), и элемент data в котором будет все что мы передали аргументом к ф-и wp_send_json_success() или wp_send_json_error()
				console.log(out); // для дебага
				jQuery('#sub').prop("disabled", false); // кнопку включим
				jQuery('#output').html(" "); // выведем результат
				jQuery('#output_s').after("<tr><th>"+out.data+"</th></tr>");
			}
			jQuery(document).ready(function(){ // после загрузки страницы
			  	add_form = jQuery('#add_object'); // запишем форму в переменную
			  	var options = { // опции для отправки формы с помощью jquery form
			  		data: { // дополнительные параметры для отправки вместе с данными формы
			  			action : 'add_object_ajax', // этот параметр будет указывать wp какой экшн запустить, у нас это wp_ajax_nopriv_add_object_ajax
			        	nonce: ajaxdata.nonce // строка для проверки, что форма отправлена откуда надо
			    	},
			      	dataType:  'json', // ответ ждем в json формате
			      	beforeSubmit: ajax_go, // перед отправкой вызовем функцию ajax_go()
			      	success: response_go, // после получении ответа вызовем response_go()
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

add_action( 'wp_ajax_nopriv_add_object_ajax', 'add_object' ); // крепим на событие wp_ajax_nopriv_add_object_ajax, где add_object_ajax это параметр action, который мы добавили в перехвате отправки формы, add_object - ф-я которую надо запустить
add_action('wp_ajax_add_object_ajax', 'add_object'); // если нужно чтобы вся бадяга работала для админов
function add_object() {
	$errors = ''; // сначала ошибок нет

	$nonce = $_POST['nonce']; // берем переданную формой строку проверки
	if (!wp_verify_nonce($nonce, 'add_object')) { // проверяем nonce код, второй параметр это аргумент из wp_create_nonce
		$errors .= 'Данные отправлены с левой страницы '; // пишим ошибку
	}

	// запишем все поля
	$title = strip_tags($_POST['post_title_case']); // запишем название поста
	$content = wp_kses_post($_POST['post_content_case']); // контент

	// проверим заполненность, если пусто добавим в $errors строку
    if (!$title) $errors .= 'Не заполнено поле "Заголовок Дела"';
    if (!$content) $errors .= 'Не заполнено поле "Описание дела"';

    // далее проверим все ли нормально с картинками которые нам отправили
    if ($_FILES['doc_case']) { // если была передана миниатюра
   		if ($_FILES['doc_case']['error']) $errors .= "Ошибка загрузки: " . $_FILES['doc_case']['error'].". (".$_FILES['doc_case']['name'].") "; // серверная ошибка загрузки
    	$type = $_FILES['doc_case']['type'];
		if (($type != "application/msword") && ($type != "application/pdf") && ($type != "text/plain") && ($type != "application/vnd.openxmlformats-officedocument.wordprocessingml.document")) $errors .= "Формат файла может быть только doc, docx, pdf и txt. (".$_FILES['doc_case']['name'].")"; // неверный формат
	}


	if (!$errors) { // если с полями все ок, значит можем добавлять пост

		$user_id = get_current_user_id();

		$fields = array( // подготовим массив с полями поста, ключ это название поля, значение - его значение
			'post_type' => 'cases', // нужно указать какой тип постов добавляем, у нас это my_custom_post_type
	    	'post_title'   => $title, // заголовок поста
	        'post_content' => $content, // контент
	        'post_author'   => $user_id,
	    );
	    $post_id = wp_insert_post($fields); // добавляем пост в базу и получаем его id

	    if ($_FILES['doc_case']) {
   			$attach_id_doc = media_handle_upload( 'doc_case', $post_id ); // добавляем doc в медиабиблиотеку и получаем его id
   			update_post_meta($post_id,'cases_doc_id',$attach_id_doc); // привязываем doc к посту
		}
	}

	if ($errors) wp_send_json_error($errors); // если были ошибки, выводим ответ в формате json с success = false и умираем
	else wp_send_json_success('Добавлено и ожидает утверждения дело: '.get_the_title($post_id)); // если все ок, выводим ответ в формате json с success = true и умираем

	die(); // умрем еще раз на всяк случ
}

function ld_view_case() {
	$user_id = get_current_user_id();

	$args = array(
		'numberposts'     => -1, // тоже самое что posts_per_page
		'orderby'         => 'post_date',
		'order'           => 'DESC',
		'post_type'       => 'cases',
		'post_status'     => 'any',
		'author'          => $user_id,
	);
	$posts = get_posts($args);

	foreach( $posts as $post ){?>
		<tr>
	    	<th><a href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_title($post->ID);?></a> <?php if ($post->post_status == 'draft') {echo '(Ожидает утверждения)';} ?></th>
	    </tr>
	    <?php
	}
}