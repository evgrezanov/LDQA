<?php
// Страница Ваши данные

// подключим скрипты и стили
// to do сделать проверку что в url есть your_information
function ld_admin_enqueue_mask_js() {
  // tabs activity
  wp_register_script( 'tabsjs', plugins_url('/js/tabs.js', __FILE__));
  wp_enqueue_script( 'tabsjs' );

  //http://i-leon.ru/udobnoe-pole-input-dlya-telefona/
  //wp_register_script( 'maskedinputminjs', plugins_url('/js/jquery.maskedinput.min.js', __FILE__), array('jquery'), '1.0.0' );
  wp_register_script( 'maskedinputminjs', plugins_url('/js/jquery.maskedinput.min.js', __FILE__));
  wp_enqueue_script( 'maskedinputminjs' );

  // dashboard style
  wp_register_style('dashboardcss', plugins_url('/css/dashboard.css', __FILE__) );
  wp_enqueue_style ('dashboardcss');


}
add_action('admin_enqueue_scripts', 'ld_admin_enqueue_mask_js');


// todo $capability изменить при необходимости
// http://codex.wordpress.org/Roles_and_Capabilities#Capability_vs._Role_Table
// добавить проверку на роль юрист
add_action( 'admin_menu', 'register_my_custom_menu_page' );
function register_my_custom_menu_page(){
	$page = add_menu_page(
		'Ваши данные',
		'Ваши данные',
		'read',
		'your_information',
		'lawyer_information_menu_page',
		'dashicons-admin-users',
		1
	);
}


function lawyer_information_menu_page(){

	$user_id = get_current_user_id();

	if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) &&  $_POST['action'] == "ld_law_info") {
		//записываем все данные в мета поля пользователя

		// Личные данные
	    update_user_meta( $user_id,'ld_secondname', sanitize_text_field( $_POST['ld_secondname'] ) );
	    update_user_meta( $user_id,'ld_name', sanitize_text_field( $_POST['ld_name'] ) );
	    update_user_meta( $user_id,'ld_patronymic', sanitize_text_field( $_POST['ld_patronymic'] ) );
	    update_user_meta( $user_id,'ld_city', sanitize_text_field( $_POST['ld_city'] ) );
	    update_user_meta( $user_id,'ld_status', sanitize_text_field( $_POST['ld_status'] ) );
	    update_user_meta( $user_id,'ld_experience', sanitize_text_field( $_POST['ld_experience'] ) );

	    // todo написать обработку проверки картинки
	    if ($_FILES['img'] AND $_FILES['img']['name'] !='') {
   			if ($_FILES['img']['error']) $errors .= "Ошибка загрузки: " . $_FILES['img']['error'].". (".$_FILES['img']['name'].") ";
    			$type = $_FILES['img']['type'];
					if (($type != "image/jpg") && ($type != "image/jpeg") && ($type != "image/png")) $errors .= "Формат файла может быть только jpg или png. (".$_FILES['img']['name'].")";
		}

		// todo проверка размера файла

			if ($_FILES['img'] AND $_FILES['img']['name'] !='' AND !$errors) {
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
	   			// добавляем картинку в медиабиблиотеку и получаем её id
	   			$attach_id_img = media_handle_upload( 'img', 0 );
	   			update_user_meta($user_id,'ld_photo',$attach_id_img);
	   			//error_log(print_r( get_user_meta($user_id, 'ld_photo', true) ));
			}

	    // Контактная информация
	    update_user_meta( $user_id,'ld_phone', sanitize_text_field( $_POST['ld_phone'] ) );
	    update_user_meta( $user_id,'ld_website', sanitize_text_field( $_POST['ld_website'] ) );
	    update_user_meta( $user_id,'ld_skype', sanitize_text_field( $_POST['ld_skype'] ) );
	    update_user_meta( $user_id,'ld_email', sanitize_text_field( $_POST['ld_email'] ) );

	    // О себе
	    update_user_meta( $user_id,'ld_about', sanitize_text_field( nl2br($_POST['ld_about'] )) );
	    update_user_meta( $user_id,'ld_byline', sanitize_text_field( nl2br($_POST['ld_byline'] )) );

	    // Специализация
	    // todo добавить функцию записи в цикле
	    update_user_meta( $user_id,'ld_specialization', $_POST['ld_specialization'] );
	    // Образование
	    // todo добавить функцию записи в цикле
	    update_user_meta( $user_id,'ld_ed_country', sanitize_text_field( $_POST['ld_ed_country'] ) );
	    update_user_meta( $user_id,'ld_ed_city', sanitize_text_field( $_POST['ld_ed_city'] ) );
	    update_user_meta( $user_id,'ld_ed_university', sanitize_text_field( $_POST['ld_ed_university'] ) );
	    update_user_meta( $user_id,'ld_ed_faculty', sanitize_text_field( $_POST['ld_ed_faculty'] ) );
	    update_user_meta( $user_id,'ld_ed_mode', sanitize_text_field( $_POST['ld_ed_mode'] ) );
	    update_user_meta( $user_id,'ld_ed_status', sanitize_text_field( $_POST['ld_ed_status'] ) );
	    update_user_meta( $user_id,'ld_ed_year_of_issue', sanitize_text_field( $_POST['ld_ed_year_of_issue'] ) );
	    //образование сохраняем массив

	    $result_ed = array_merge($_POST['ld_ed_array']);
		foreach ($result_ed as $key => $value) {
			if ($value['ld_ed_country'] =='' AND $value['ld_ed_city'] =='' AND $value['ld_ed_university'] =='' AND $value['ld_ed_faculty'] =='' AND $value['ld_ed_mode'] =='' AND $value['ld_ed_status'] =='' AND $value['ld_ed_year_of_issue'] =='' ) {
				unset($result_ed[$key]);
			}
		}
		$result_ed = array_merge($result_ed);
		update_user_meta( $user_id,'ld_ed_array', $result_ed);

	    // Карьера
	    // todo добавить функцию записи в цикле
	    update_user_meta( $user_id,'ld_job_company', sanitize_text_field( $_POST['ld_job_company'] ) );
	    update_user_meta( $user_id,'ld_job_country', sanitize_text_field( $_POST['ld_job_country'] ) );
	    update_user_meta( $user_id,'ld_job_city', sanitize_text_field( $_POST['ld_job_city'] ) );
	    update_user_meta( $user_id,'ld_job_start_year', sanitize_text_field( $_POST['ld_job_start_year'] ) );
	    update_user_meta( $user_id,'ld_job_end_year', sanitize_text_field( $_POST['ld_job_end_year'] ) );
	    update_user_meta( $user_id,'ld_job_position', sanitize_text_field( $_POST['ld_job_position'] ) );
	    //ld_job_array
	    $result_job = array_merge($_POST['ld_job_array']);
		foreach ($result_job as $key => $value) {
			if ($value['ld_job_company'] =='' AND $value['ld_job_country'] =='' AND $value['ld_job_city'] =='' AND $value['ld_job_start_year'] =='' AND $value['ld_job_end_year'] =='' AND $value['ld_job_position'] =='') {
				unset($result_job[$key]);
			}
		}
		$result_job = array_merge($result_job);
		update_user_meta( $user_id,'ld_job_array', $result_job);

	    // Успешные дела
	    // todo добавить функцию записи в цикле
	    update_user_meta( $user_id,'ld_case_name', sanitize_text_field( $_POST['ld_case_name'] ) );
	    update_user_meta( $user_id,'ld_case_desc', sanitize_text_field( $_POST['ld_case_desc'] ) );
	}

	// считаем количетво учреждений для скрипта
	if (get_user_meta($user_id, 'ld_ed_array', true)) {
		$n_ed = count(get_user_meta($user_id, 'ld_ed_array', true)) + 1;
	} else {
		$n_ed = 0;
	}
	// считаем количетво рабочих мест для скрипта
	if (get_user_meta($user_id, 'ld_job_array', true)) {
		$n_job = count(get_user_meta($user_id, 'ld_job_array', true)) + 1;
	} else {
		$n_job = 0;
	}
?>
<div class="wrap about-wrap">

	<?php
		$ld_secondname = get_user_meta($user_id, 'ld_secondname', true);
		$ld_name = get_user_meta($user_id, 'ld_name', true);
		$ld_patronymic = get_user_meta($user_id, 'ld_patronymic', true);
		$ld_profile_link = get_author_posts_url($user_id);
	?>


	<h1><?php echo $ld_secondname.' '.$ld_name.' '.$ld_patronymic; ?></h1>

	<div class="about-text">
		<?php _e('Заполните информацию и расскажите о себе потенциальным клиентам!<br>'); ?>
		<?php _e('<br>Ваш профиль на сайте <a href="'.$ld_profile_link.'">'.$ld_profile_link ); ?>
	</div>

    <?php
    // проверим подтвердил ли email
	$confirm = get_user_meta( $user_id, 'ld_mail_confirm', true );
	if ($confirm) {

    ?>
    <form method="post" action="" id="info_form" name="info_form" enctype="multipart/form-data">
		<h2 class="nav-tab-wrapper">
			<a href="#" id="personal_data_s" class="nav-tab personal_data nav-tab-active"><?php _e( 'Личные данные' ); ?></a>
			<a href="#" id="specialization_s" class="nav-tab specialization"><?php _e( 'Специализация' ); ?></a>
			<a href="#" id="education_s" class="nav-tab education"><?php _e( 'Образование' ); ?></a>
			<a href="#" id="career_s" class="nav-tab career"><?php _e( 'Карьера' ); ?></a>
			<a href="#" id="successful_cases_s" class="nav-tab successful_cases"><?php _e( 'Успешные дела' ); ?></a>
		</h2>

		<div id="personal_data" class="ldinfo active">


				<h3><?php _e( 'Личные данные' ); ?></h3>

					<table class="form-table">
			            <tr>
			                <th><label for="ld_secondname">Фамилия</label></th>
			                <td><input type="text" name="ld_secondname" value="<?php echo esc_attr(get_user_meta($user_id, 'ld_secondname', true)); ?>" class="regular-text" /></td>
			            </tr>

			            <tr>
			                <th><label for="ld_name">Имя</label></th>
			                <td><input type="text" name="ld_name" value="<?php echo esc_attr(get_user_meta($user_id, 'ld_name', true)); ?>" class="regular-text" /></td>
			            </tr>

			            <tr>
			                <th><label for="ld_patronymic">Отчество</label></th>
			                <td><input type="text" name="ld_patronymic" value="<?php echo esc_attr(get_user_meta($user_id, 'ld_patronymic', true)); ?>" class="regular-text" /></td>
			            </tr>

			            <tr>
			            	<th><label for="ld_city">Город</label></th>
			            	<?php $ld_city=get_user_meta($user_id, 'ld_city', true); ?>
			            	<td><?php ld_city_selectbox_dashboard($ld_city); ?></td>
			            </tr>

			            <tr>
			            	<th><label for="ld_status">Статус</label></th>
			            	<?php $ld_status=get_user_meta($user_id, 'ld_status', true); ?>

			            	<td>
			            		<select required="required" name="ld_status">
				    					<option <?php if ($ld_status=="lawyer") echo 'selected'; ?> value="lawyer">Юрист</option>
				    					<option <?php if ($ld_status=="advocate") echo 'selected'; ?> value="advocate">Адвокат</option>
				    					<option <?php if ($ld_status=="notary") echo 'selected'; ?> value="notary">Нотариус</option>
				    					<option <?php if ($ld_status=="bankruptcy_commissioner") echo 'selected'; ?> value="bankruptcy_commissioner">Арбитражный управляющий</option>
			    				</select>
			    			</td>
			            </tr>

			            <tr>
			            	<th><label for="ld_experience">Опыт работы (кол-во лет)</label></th>
			            	<td><input type="number" min="1" max="99" step="1" name="ld_experience" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_experience', true )); ?>" class="regular-text" /></td>
			            </tr>

			            <?php
			            //если картинка была загружена ранее то выведем ее
						$ld_photo = get_user_meta($user_id,'ld_photo',true);
						if (!empty($ld_photo)) {
							$image_attributes = wp_get_attachment_image_src( $ld_photo );
						}
						?>
							<td><img src="<?php echo $image_attributes[0] ?>" width="<?php echo $image_attributes[1] ?>" height="<?php echo $image_attributes[2] ?>"><td>


	            		<tr>
				            <th>Аватарка пользователя (картинка с расширением jpg, jpeg, png не более 1 мб)<br> <?php echo $errors;?></th>
				            <td><input type="file" name="img" id="i_file" /></td>
        				</tr>


	        		</table>

				<h3><?php _e( 'Контактная информация' ); ?></h3>

					<table class="form-table">
			            <tr>
			            	<th><label for="ld_phone">Телефон</label></th>
			            	<td><input id="ld_phone" placeholder="+7(___)-___-__-__" type="text" name="ld_phone" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_phone', true )); ?>" class="regular-text" /></td>
			            </tr>
			            <script type="text/javascript">
							jQuery(function($){
   								$("#ld_phone").mask("+7 (999) 999-99-99");
							});
						</script>
			            <tr>
			            	<th><label for="ld_website">Сайт</label></th>
			            	<td><input type="text" name="ld_website" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_website', true )); ?>" class="regular-text" /></td>
			            </tr>

			            <tr>
			            	<th><label for="ld_skype">Скайп</label></th>
			            	<td><input type="text" name="ld_skype" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_skype', true )); ?>" class="regular-text" /></td>
			            </tr>

			            <tr>
			            	<th><label for="ld_email">Email</label></th>
			            	<?php $user_info = get_userdata($user_id); ?>
			            	<td><input type="text" name="ld_email" value="<?php echo esc_attr($user_info->user_email); ?>" class="regular-text" /></td>
			            </tr>
	        		</table>

	        	<h3><?php _e( 'О себе' ); ?></h3>
	        		<table class="form-table">

	        			<tr>
							<th><label for="description">Расскажите о себе</label></th>
							<td><textarea placeholder="Например:'Имею большой опыт адвокатской практики по ведению в судах уголовных и гражданских дел любой категории и сложности с положительным результатом для клиента'" name="ld_about" id="ld_about" maxlength="2000"><?php echo esc_attr(get_user_meta( $user_id, 'ld_about', true )); ?></textarea>
							<p class="description">Напишите о себе (до 2000 символов без пробелов). Эта информацию увидят на сайте ваши потенциальные клиенты.</p></td>
						</tr>

						<tr>
							<th><label for="description">Подпись</label></th>
							<td><textarea placeholder="Например: 'С уважением, Юрий Николаевич! Пожалуйста, оцените мой ответ.' или 'Юридическое сопровождение сделок. Разводы. Алименты.'" name="ld_byline" id="ld_byline" maxlength="150"><?php echo esc_attr(get_user_meta( $user_id, 'ld_byline', true )); ?></textarea>
							<p class="description">Ваша информация будет отображаться в ответах к вопросам. Здесь вы сможете указать любую информацию. Ограничение до 150 символов без пробелов.</p></td>
						</tr>

	        		</table>
		</div>

		<div id="specialization" class="ldinfo">
			<h3>Укажите вашу специализацию</h3>
	        <table class="form-table">
	        	<?php ld_view_chekbox_lk(get_user_meta(get_current_user_id(), 'ld_specialization', true)); ?>
	       	</table>
		</div>

		<div id="education" class="ldinfo">
			<h3>Образование</h3>

	        <table id="education_ld_s" class="form-table">
	        	<tr>
	            	<th><label for="ld_ed_country">Страна</label></th>
	            	<td><input type="text" name="ld_ed_country" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_ed_country', true )); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_ed_city">Город</label></th>
	            	<td><input type="text" name="ld_ed_city" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_ed_city', true )); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_ed_university">ВУЗ</label></th>
	            	<td><input type="text" name="ld_ed_university" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_ed_university', true )); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_ed_faculty">Факультет</label></th>
	            	<td><input type="text" name="ld_ed_faculty" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_ed_faculty', true )); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_ed_mode">Форма обучения</label></th>
	            	<td><input type="text" name="ld_ed_mode" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_ed_mode', true )); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_ed_status">Статус</label></th>
	            	<td><input type="text" name="ld_ed_status" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_ed_status', true )); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_ed_year_of_issue">Год выпуска</label></th>
	            	<td><input type="text" name="ld_ed_year_of_issue" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_ed_year_of_issue', true )); ?>" class="regular-text" /></td>
	            </tr>
	            <?php
	            	/*выводим дополнительное образование*/
	            	view_ed($user_id);
	            ?>
            </table>
            <table class="form-table">
            	<tr>
	            	<th><input type="button" value="+ Добавить еще одно учебное заведение" id="add" onclick="return add_new_ed();"></th>
	            	<td></td>
            	</tr>
	       	</table>
		</div>

		<div id="career" class="ldinfo">
			<h3>Карьера</h3>

	        <table id="career_ld_s" class="form-table">
	        	<!--todo Вынести в отдельную функцию. выводим в цикле-->
	        	<tr>
	            	<th><label for="ld_job_company">Место работы</label></th>
	            	<td><input type="text" name="ld_job_company" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_job_company', true )); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_job_country">Страна</label></th>
	            	<td><input type="text" name="ld_job_country" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_job_country', true )); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_job_city">Город</label></th>
	            	<td><input type="text" name="ld_job_city" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_job_city', true )); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_job_start_year">Год начала работы</label></th>
	            	<td><input type="text" name="ld_job_start_year" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_job_start_year', true )); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_job_end_year">Год окончания работы</label></th>
	            	<td><input type="text" name="ld_job_end_year" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_job_end_year', true )); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_job_position">Должность</label></th>
	            	<td><input type="text" name="ld_job_position" value="<?php echo esc_attr(get_user_meta( $user_id, 'ld_job_position', true )); ?>" class="regular-text" /></td>
	            </tr>
	             <?php /*выводим дополнительные места работы*/ view_job($user_id); ?>

	       	</table>
	       	<table class="form-table">
            	<tr>
	            	<th><input type="button" value="+ Добавить еще одно место работы" id="add" onclick="return add_new_job();"></th>
	            	<td></td>
            	</tr>
	       	</table>
		</div>
		<input type="hidden" name="action" value="ld_law_info"/>
	</form>

		<div id="successful_cases" class="ldinfo">
			<h3>Успешные дела</h3>

	        <table class="form-table">
	        <?php
	        echo ld_new_case_form();
	        ld_view_case();
	         ?>

	       	</table>
		</div>

		<input id="i_submit" type="button" class="btn-primary" name="submit_button" value="Сохранить"/>
		<script type="text/javascript">
			jQuery('#i_submit').click( function() {
				var f = jQuery('#i_file')[0].files[0];
				if (f) {
			    //check whether browser fully supports all File API
				    if (window.File && window.FileReader && window.FileList && window.Blob)
				    {
				        //get the file size and file type from file input field
				        var fsize = jQuery('#i_file')[0].files[0].size;

				        if(fsize>1048576) //do something if file size more than 1 mb (1048576)
				        {
				            alert("Размер аватарки более 1 мб!");
				        }else{
		            		jQuery('#info_form').submit();
				        }
				    }else{
				        alert("Please upgrade your browser, because your current browser lacks some new features we need!");
				    }
		    	}else{
		    		jQuery('#info_form').submit();
		    	}
	    	});
		</script>
</div>

<?php } else { ?>

	<div class="about-text">
		<?php _e('Для того чтобы активировать профиль подтвердите email!'); ?>
	</div>

<?php

}
?>


	<!--to do отрефакторить по возможности в цикл-->
	<script>
			jQuery('#personal_data_s').click(function(e) {
				e.preventDefault();
				jQuery(this).addClass('nav-tab-active');
				jQuery('#specialization_s').removeClass('nav-tab-active');
				jQuery('#education_s').removeClass('nav-tab-active');
				jQuery('#career_s').removeClass('nav-tab-active');
				jQuery('#successful_cases_s').removeClass('nav-tab-active');

				jQuery('#personal_data').addClass('active');
				jQuery('#specialization').removeClass('active');
				jQuery('#education').removeClass('active');
				jQuery('#career').removeClass('active');
				jQuery('#successful_cases').removeClass('active');

			});

			jQuery('#specialization_s').click(function(e) {
				e.preventDefault();
				jQuery(this).addClass('nav-tab-active');
				jQuery('#personal_data_s').removeClass('nav-tab-active');
				jQuery('#education_s').removeClass('nav-tab-active');
				jQuery('#career_s').removeClass('nav-tab-active');
				jQuery('#successful_cases_s').removeClass('nav-tab-active');

				jQuery('#specialization').addClass('active');
				jQuery('#personal_data').removeClass('active');
				jQuery('#education').removeClass('active');
				jQuery('#career').removeClass('active');
				jQuery('#successful_cases').removeClass('active');

			});

			jQuery('#education_s').click(function(e) {
				e.preventDefault();
				jQuery(this).addClass('nav-tab-active');
				jQuery('#personal_data_s').removeClass('nav-tab-active');
				jQuery('#career_s').removeClass('nav-tab-active');
				jQuery('#successful_cases_s').removeClass('nav-tab-active');
				jQuery('#specialization_s').removeClass('nav-tab-active');

				jQuery('#education').addClass('active');
				jQuery('#specialization').removeClass('active');
				jQuery('#personal_data').removeClass('active');
				jQuery('#career').removeClass('active');
				jQuery('#successful_cases').removeClass('active');

			});

			jQuery('#career_s').click(function(e) {
				e.preventDefault();
				jQuery(this).addClass('nav-tab-active');
				jQuery('#personal_data_s').removeClass('nav-tab-active');
				jQuery('#successful_cases_s').removeClass('nav-tab-active');
				jQuery('#specialization_s').removeClass('nav-tab-active');
				jQuery('#education_s').removeClass('nav-tab-active');

				jQuery('#career').addClass('active');
				jQuery('#education').removeClass('active');
				jQuery('#specialization').removeClass('active');
				jQuery('#personal_data').removeClass('active');
				jQuery('#successful_cases').removeClass('active');

			});

			jQuery('#successful_cases_s').click(function(e) {
				e.preventDefault();
				jQuery(this).addClass('nav-tab-active');
				jQuery('#personal_data_s').removeClass('nav-tab-active');
				jQuery('#specialization_s').removeClass('nav-tab-active');
				jQuery('#education_s').removeClass('nav-tab-active');
				jQuery('#career_s').removeClass('nav-tab-active');

				jQuery('#successful_cases').addClass('active');
				jQuery('#career').removeClass('active');
				jQuery('#education').removeClass('active');
				jQuery('#specialization').removeClass('active');
				jQuery('#personal_data').removeClass('active');

			});
	</script>


	<?php /// скрипт и стили для чекбоксов специализации ?>
	<script>
		var t = document.forms.info_form;
		[].forEach.call(t.querySelectorAll('fieldset'), function(eFieldset) {
		  var main = [].filter.call(t.querySelectorAll('[type="checkbox"]'), function(element) {return element.parentNode.nextElementSibling == eFieldset;});
		  main.forEach(function(eMain) {
		    var l = [].filter.call(eFieldset.querySelectorAll('legend'), function(e) {return e.parentNode == eFieldset;});
		    l.forEach(function(eL) {
		      var all = eFieldset.querySelectorAll('[type="checkbox"]');
		      eL.onclick = Razvernut;
		      eFieldset.onchange = Razvernut;
		      function Razvernut() {
		        var allChecked = eFieldset.querySelectorAll('[type="checkbox"]:checked').length;
		        eMain.checked = allChecked == all.length;
		        eMain.indeterminate = allChecked > 0 && allChecked < all.length;
		        if (eMain.indeterminate||eMain.checked||((eFieldset.className == '') && (allChecked == "0"))) {
		          eFieldset.className = 'razvernut';
		        } else {
		          eFieldset.className = '';
		        }
		      }
		      eMain.onclick = function() {
		        for(var i=0; i<all.length; i++)
		          all[i].checked = this.checked;
		          if (this.checked) {
		            eFieldset.className = 'razvernut';
		          } else {
		            eFieldset.className = '';
		          }
		      }
		    });
		  });
		});
	</script>
	<!--
	<style>
	#specialization
		#info_form { /* вся форма */
		  line-height: normal;
		}
		#specialization  label.chekbox { /* пункты и соединяющие их линии */
		  position: relative;
		  display: block;
		  padding: 0 0 0 1.2em;
		}
		#specialization  label.chekbox:not(:nth-last-of-type(1)) {
		  border-left: 1px solid #94a5bd;
		}
		#specialization  label.chekbox:before {
		  content: "";
		  position: absolute;
		  top: 0;
		  left: 0;
		  width: 1.1em;
		  height: .5em;
		  border-bottom: 1px solid #94a5bd;
		}
		#specialization  label.chekbox:nth-last-of-type(1):before {
		  border-left: 1px solid #94a5bd;
		}
		#specialization  fieldset,
		#specialization  fieldset[class=""] .razvernut { /* списки */
		  position: absolute;
		  visibility: hidden;
		  margin: 0;
		  padding: 0 0 0 2em;
		  border: none;
		}
		#specialization  fieldset:not(:last-child) {
		  border-left: 1px solid #94a5bd;
		}
		#specialization .razvernut {
		  position: relative;
		  visibility: visible;
		}
		#specialization > fieldset > legend,
		#specialization .razvernut  > fieldset > legend { /* плюс */
		  position: absolute;
		  left: -5px;
		  top: 0;
		  height: 7px;
		  width: 7px;
		  margin-top: -1em;
		  padding: 0;
		  border: 1px solid #94a5bd;
		  border-radius: 2px;
		  background-repeat: no-repeat;
		  background-position: 50% 50%;
		  background-color: #fff;
		  background-image: linear-gradient(to left, #1b4964, #1b4964), linear-gradient(#1b4964, #1b4964), linear-gradient(315deg, #a0b6d8, #e8f3ff 60%, #fff 60%);
		  background-size: 1px 5px, 5px 1px, 100% 100%;
		  visibility: visible;
		  cursor: pointer;
		}
		#specialization fieldset[class=""] .razvernut fieldset legend {
		  visibility: hidden;
		}
		#specialization .razvernut > legend { /* минус */
		  background-image: linear-gradient(#1b4964, #1b4964) !important;
		  background-size: 5px 1px !important;
		}
	</style>
-->
	<?php /// конец скриптов и стилей для специализации.
			/// начало скрипта по выводу доп образования и мест работы
				/// Это конечно пздц - надо чото с этим говнокодом делать
	?>

	<script type="text/javascript">
		var total = <?php echo $n_ed; ?>;
		function add_new_ed(){
			total++;
			jQuery('<tr>')

			.append (
				jQuery('<th>')
				.append(
					jQuery('<h3>Образование №'+total+'</h3>')
				)

			)

			.append (
				jQuery('<td>')
			)
			.appendTo('#education_ld_s');


			jQuery('<tr>')

			.append (
				jQuery('<th>')
				.append(
					jQuery('<label>Страна</label>')
					.attr('for','ld_ed_array['+total+'][ld_ed_country]')
				)

			)

			.append (
				jQuery('<td>')

				.append (
					jQuery('<input type="text" name="ld_ed_array['+total+'][ld_ed_country]" value="" class="regular-text" />')
				)
			)
			.appendTo('#education_ld_s');
			jQuery('<tr>')


				.append (
					jQuery('<th>')
					.append(
						jQuery('<label>Город</label>')
						.attr('for','ld_ed_array['+total+'][ld_ed_city]')
					)

				)

				.append (
					jQuery('<td>')

					.append (
						jQuery('<input type="text" name="ld_ed_array['+total+'][ld_ed_city]" value="" class="regular-text" />')
					)
				)

			.appendTo('#education_ld_s');
			jQuery('<tr>')


				.append (
					jQuery('<th>')
					.append(
						jQuery('<label>ВУЗ</label>')
						.attr('for','ld_ed_array['+total+'][ld_ed_university]')
					)

				)

				.append (
					jQuery('<td>')

					.append (
						jQuery('<input type="text" name="ld_ed_array['+total+'][ld_ed_university]" value="" class="regular-text" />')
					)
				)

			.appendTo('#education_ld_s');
			jQuery('<tr>')


				.append (
					jQuery('<th>')
					.append(
						jQuery('<label>Факультет</label>')
						.attr('for','ld_ed_array['+total+'][ld_ed_faculty]')
					)

				)

				.append (
					jQuery('<td>')

					.append (
						jQuery('<input type="text" name="ld_ed_array['+total+'][ld_ed_faculty]" value="" class="regular-text" />')
					)
				)

			.appendTo('#education_ld_s');
			jQuery('<tr>')


				.append (
					jQuery('<th>')
					.append(
						jQuery('<label>Форма обучения</label>')
						.attr('for','ld_ed_array['+total+'][ld_ed_mode]')
					)

				)

				.append (
					jQuery('<td>')

					.append (
						jQuery('<input type="text" name="ld_ed_array['+total+'][ld_ed_mode]" value="" class="regular-text" />')
					)
				)

			.appendTo('#education_ld_s');
			jQuery('<tr>')


				.append (
					jQuery('<th>')
					.append(
						jQuery('<label>Статус</label>')
						.attr('for','ld_ed_array['+total+'][ld_ed_status]')
					)

				)

				.append (
					jQuery('<td>')

					.append (
						jQuery('<input type="text" name="ld_ed_array['+total+'][ld_ed_status]" value="" class="regular-text" />')
					)
				)

			.appendTo('#education_ld_s');
			jQuery('<tr>')


				.append (
					jQuery('<th>')
					.append(
						jQuery('<label>Год выпуска</label>')
						.attr('for','ld_ed_array['+total+'][ld_ed_year_of_issue]')
					)

				)

				.append (
					jQuery('<td>')

					.append (
						jQuery('<input type="text" name="ld_ed_array['+total+'][ld_ed_year_of_issue]" value="" class="regular-text" />')
					)
				)

			.appendTo('#education_ld_s');
		}
		//работа
		var total_job = <?php echo $n_job; ?>;
		function add_new_job(){
			total_job++;
			jQuery('<tr>')

			.append (
				jQuery('<th>')
				.append(
					jQuery('<h3>Место работы №'+total_job+'</h3>')
				)

			)

			.append (
				jQuery('<td>')
			)
			.appendTo('#career_ld_s');

			jQuery('<tr>')

			.append (
				jQuery('<th>')
				.append(
					jQuery('<label>Место работы</label>')
					.attr('for','ld_job_array['+total_job+'][ld_job_company]')
				)

			)

			.append (
				jQuery('<td>')

				.append (
					jQuery('<input type="text" name="ld_job_array['+total_job+'][ld_job_company]" value="" class="regular-text" />')
				)
			)
			.appendTo('#career_ld_s');
			jQuery('<tr>')


				.append (
					jQuery('<th>')
					.append(
						jQuery('<label>Страна</label>')
						.attr('for','ld_job_array['+total_job+'][ld_job_country]')
					)

				)

				.append (
					jQuery('<td>')

					.append (
						jQuery('<input type="text" name="ld_job_array['+total_job+'][ld_job_country]" value="" class="regular-text" />')
					)
				)

			.appendTo('#career_ld_s');
			jQuery('<tr>')


				.append (
					jQuery('<th>')
					.append(
						jQuery('<label>Город</label>')
						.attr('for','ld_job_array['+total_job+'][ld_job_city]')
					)

				)

				.append (
					jQuery('<td>')

					.append (
						jQuery('<input type="text" name="ld_job_array['+total_job+'][ld_job_city]" value="" class="regular-text" />')
					)
				)

			.appendTo('#career_ld_s');
			jQuery('<tr>')


				.append (
					jQuery('<th>')
					.append(
						jQuery('<label>Год начала работы</label>')
						.attr('for','ld_job_array['+total_job+'][ld_job_start_year]')
					)

				)

				.append (
					jQuery('<td>')

					.append (
						jQuery('<input type="text" name="ld_job_array['+total_job+'][ld_job_start_year]" value="" class="regular-text" />')
					)
				)

			.appendTo('#career_ld_s');
			jQuery('<tr>')


				.append (
					jQuery('<th>')
					.append(
						jQuery('<label>Год окончания работы</label>')
						.attr('for','ld_job_array['+total_job+'][ld_job_end_year]')
					)

				)

				.append (
					jQuery('<td>')

					.append (
						jQuery('<input type="text" name="ld_job_array['+total_job+'][ld_job_end_year]" value="" class="regular-text" />')
					)
				)

			.appendTo('#career_ld_s');
			jQuery('<tr>')


				.append (
					jQuery('<th>')
					.append(
						jQuery('<label>Должность</label>')
						.attr('for','ld_job_array['+total_job+'][ld_job_position]')
					)

				)

				.append (
					jQuery('<td>')

					.append (
						jQuery('<input type="text" name="ld_job_array['+total_job+'][ld_job_position]" value="" class="regular-text" />')
					)
				)

			.appendTo('#career_ld_s');
		}
	</script>

<?php
}

//вывод доп образования
function view_ed($user_id){
	$array_s = get_user_meta($user_id, 'ld_ed_array', true);
	foreach ($array_s as $key => $value) { ?>

				<tr>
					<th>
						<h3>Образование №<?php echo $key + 2; ?></h3>
					</th>
					<td>
					</td>
				</tr>
				<tr>
	            	<th><label for="ld_ed_array[<?php echo $key; ?>][ld_ed_country]">Страна</label></th>
	            	<td><input type="text" name="ld_ed_array[<?php echo $key; ?>][ld_ed_country]" value="<?php echo esc_attr($value['ld_ed_country']); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_ed_array[<?php echo $key; ?>][ld_ed_city]">Город</label></th>
	            	<td><input type="text" name="ld_ed_array[<?php echo $key; ?>][ld_ed_city]" value="<?php echo esc_attr($value['ld_ed_city']); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_ed_array[<?php echo $key; ?>][ld_ed_university]">ВУЗ</label></th>
	            	<td><input type="text" name="ld_ed_array[<?php echo $key; ?>][ld_ed_university]" value="<?php echo esc_attr($value['ld_ed_university']); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_ed_array[<?php echo $key; ?>][ld_ed_faculty]">Факультет</label></th>
	            	<td><input type="text" name="ld_ed_array[<?php echo $key; ?>][ld_ed_faculty]" value="<?php echo esc_attr($value['ld_ed_faculty']); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_ed_array[<?php echo $key; ?>][ld_ed_mode]">Форма обучения</label></th>
	            	<td><input type="text" name="ld_ed_array[<?php echo $key; ?>][ld_ed_mode]" value="<?php echo esc_attr($value['ld_ed_mode']); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_ed_array[<?php echo $key; ?>][ld_ed_status]">Статус</label></th>
	            	<td><input type="text" name="ld_ed_array[<?php echo $key; ?>][ld_ed_status]" value="<?php echo esc_attr($value['ld_ed_status']); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_ed_array[<?php echo $key; ?>][ld_ed_year_of_issue]">Год выпуска</label></th>
	            	<td><input type="text" name="ld_ed_array[<?php echo $key; ?>][ld_ed_year_of_issue]" value="<?php echo esc_attr($value['ld_ed_year_of_issue']); ?>" class="regular-text" /></td>
	            </tr> <?php
	}
}

//вывод доп рабочих мест
function view_job($user_id){
	$array_s = get_user_meta($user_id, 'ld_job_array', true);
	foreach ($array_s as $key => $value) { ?>
				<tr>
					<th>
						<h3>Место работы №<?php echo $key + 2; ?></h3>
					</th>
					<td>
					</td>
				</tr>
				<tr>
	            	<th><label for="ld_job_array[<?php echo $key; ?>][ld_job_company]">Место работы</label></th>
	            	<td><input type="text" name="ld_job_array[<?php echo $key; ?>][ld_job_company]" value="<?php echo esc_attr($value['ld_job_company']); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_job_array[<?php echo $key; ?>][ld_job_country]">Страна</label></th>
	            	<td><input type="text" name="ld_job_array[<?php echo $key; ?>][ld_job_country]" value="<?php echo esc_attr($value['ld_job_country']); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_job_array[<?php echo $key; ?>][ld_job_city]">Город</label></th>
	            	<td><input type="text" name="ld_job_array[<?php echo $key; ?>][ld_job_city]" value="<?php echo esc_attr($value['ld_job_city']); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_job_array[<?php echo $key; ?>][ld_job_start_year]">Год начала работы</label></th>
	            	<td><input type="text" name="ld_job_array[<?php echo $key; ?>][ld_job_start_year]" value="<?php echo esc_attr($value['ld_job_start_year']); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_job_array[<?php echo $key; ?>][ld_job_end_year]">Год окончания работы</label></th>
	            	<td><input type="text" name="ld_job_array[<?php echo $key; ?>][ld_job_end_year]" value="<?php echo esc_attr($value['ld_job_end_year']); ?>" class="regular-text" /></td>
	            </tr>

	            <tr>
	            	<th><label for="ld_job_array[<?php echo $key; ?>][ld_job_position]">Должность</label></th>
	            	<td><input type="text" name="ld_job_array[<?php echo $key; ?>][ld_job_position]" value="<?php echo esc_attr($value['ld_job_position']); ?>" class="regular-text" /></td>
	            </tr> <?php
	}
}