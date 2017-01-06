<?php
function ld_registration_form( $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_city, $ld_specialization_array) {
	?>
    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" id="reg_form" name="reg_form">
    <?php wp_nonce_field('Hn9rU3ek0rG8rb','bH37nfG7ej5G0F3'); ?>
    <div>
	    <label class="zag">E-mail <strong>*</strong>
	    	<br>
	    	<input class="form-control" type="email" name="email" required="required" placeholder="E-mail" value="<?php echo ( isset( $_POST['email']) ? $email : null ); ?>">
    	</label>
    </div>
    <div>
    	<label class="zag">Статус <strong>*</strong>
    		<br>
		    <select required="required" name="ld_status">
			    <option>Выберите статус</option>
			    <option <?php selected( $ld_status, "lawyer" ); ?> value="lawyer">Юрист</option>
			    <option <?php selected( $ld_status, "advocate" ); ?> value="advocate">Адвокат</option>
			    <option <?php selected( $ld_status, "notary" ); ?> value="notary">Нотариус</option>
			    <option <?php selected( $ld_status, "bankruptcy_commissioner" ); ?> value="bankruptcy_commissioner">Арбитражный управляющий</option>
		    </select>
	    </label>
    </div>

    <div>
    	<span class="zag">Ваша специализация <strong>*</strong></span>
    		<br>
	    <?php
	    ld_view_chekbox($ld_specialization_array);
	    ?>
    </div>
    <div>
    	<label class="zag">Город <strong>*</strong>
    		<br>
		    <?php
		    ld_city_selectbox($ld_city);
		    ?>
	    </label>
    </div>
    <div>
	    <label class="zag">Фамилия <strong>*</strong>
	    	<br>
	    	<input class="form-control" type="text" name="ld_secondname" required="required" placeholder="Фамилия" value="<?php echo ( isset( $_POST['ld_secondname']) ? $ld_secondname : null ); ?>">
    	</label>
    </div>
    <div>
	    <label class="zag">Имя <strong>*</strong>
	    	<br>
	    	<input class="form-control" type="text" name="ld_name" required="required" placeholder="Имя" value="<?php echo ( isset( $_POST['ld_name']) ? $ld_name : null ); ?>">
    	</label>
    </div>
    <div>
	    <label class="zag">Отчество <strong>*</strong>
	    	<br>
	    	<input class="form-control" type="text" name="ld_patronymic" required="required" placeholder="Отчество" value="<?php echo ( isset( $_POST['ld_patronymic']) ? $ld_patronymic : null ); ?>">
    	</label>
    </div>
    <br>
    <div class="g-recaptcha" data-sitekey="6LeEMiITAAAAADBf9SEBHOQVZ7ZMHm6bfvYFk8T3"></div>
    <br>
    <input type="submit" class="btn btn_green" name="submit" value="Регистрация"/>
    </form>
    <script src='https://www.google.com/recaptcha/api.js?hl=ru'></script>
    <script>
		var t = document.forms.reg_form;
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
	<style>
		#reg_form { /* вся форма */
		  line-height: normal;
		}
		#reg_form label.chekbox { /* пункты и соединяющие их линии */
		  position: relative;
		  display: block;
		  padding: 0 0 0 1.2em;
		}
		#reg_form label.chekbox:not(:nth-last-of-type(1)) {
		  border-left: 1px solid #94a5bd;
		}
		#reg_form label.chekbox:before {
		  content: "";
		  position: absolute;
		  top: 0;
		  left: 0;
		  width: 1.1em;
		  height: .5em;
		  border-bottom: 1px solid #94a5bd;
		}
		#reg_form label.chekbox:nth-last-of-type(1):before {
		  border-left: 1px solid #94a5bd;
		}
		#reg_form fieldset,
		#reg_form fieldset[class=""] .razvernut { /* списки */
		  position: absolute;
		  visibility: hidden;
		  margin: 0;
		  padding: 0 0 0 2em;
		  border: none;
		}
		#reg_form fieldset:not(:last-child) {
		  border-left: 1px solid #94a5bd;
		}
		#reg_form .razvernut {
		  position: relative;
		  visibility: visible;
		}
		#reg_form > div> fieldset > legend,
		#reg_form .razvernut > div > fieldset > legend { /* плюс */
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
		#reg_form fieldset[class=""] .razvernut fieldset legend {
		  visibility: hidden;
		}
		#reg_form .razvernut > legend { /* минус */
		  background-image: linear-gradient(#1b4964, #1b4964) !important;
		  background-size: 5px 1px !important;
		}

		#reg_form > div > label.zag, #reg_form > div > span.zag {
	    	font-weight: 700;
		}
		#reg_form > div > label.zag > strong, #reg_form > div > span.zag > strong {
			color: red;
		}
		#reg_form > div {
			padding-top: 15px;
		}
		#reg_form > div > label.zag > input, #reg_form > div > label.zag > select {
		    display: block;
		    width: 100%;
		    height: 35px;
		    padding: 0 10px;
		    border: 1px solid #c5c5c5;
		    box-sizing: border-box;
		    background-color: #fff;
		    font-family: 'Open Sans', 'Arial', 'Helvetica', sans-serif;
		    font-size: 16px;
		    color: #333;
		    line-height: 35px;
		    text-align: left;
		}
		#reg_form > div > label.zag > input:focus, #reg_form > div > label.zag > select:focus {
		    box-shadow: 0 0 0 2px #489ad1;
		    border-radius: 1px;
		}
	</style>
<?php
}




function ld_registration_validation( $username, $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_captcha, $ld_city, $ld_specialization_array)  {
	global $reg_errors;

	$reg_errors = new WP_Error;

	// секретный ключ капчи
	$secret = "6LeEMiITAAAAAJGEO6wSN_FnuUfDD5q4g0DbxEbF";

	// пустой ответ
	$response = null;

	// проверка секретного ключа
	$reCaptcha = new ReCaptcha($secret);

	//проверка капчи
	if ($ld_captcha) {
		$response = $reCaptcha->verifyResponse(
	        $_SERVER["REMOTE_ADDR"],
	        $ld_captcha
	    );
	}

	if (!($response != null && $response->success)) {
    	$reg_errors->add('captha_none', 'Капча не введена или введена неверно!');
    }

	/*почта*/
	if ( empty( $email ) ) {
    	$reg_errors->add('email_none', 'Адрес электронной почты обязателен для заполнения!');
	}

	if ( !is_email( $email ) ) {
    	$reg_errors->add( 'email_invalid', 'Вы ввели недопустимый адрес электронной почты!' );
	}

	if ( email_exists( $email ) ) {
    	$reg_errors->add( 'email', 'Данный адрес электронной почты уже используется!' );
	}
	/* статус*/
	if ( empty( $ld_status ) ) {
    	$reg_errors->add('ld_status_none', 'Статус обязателен для заполнения!');
	}

	if ( $ld_status != 'lawyer' and $ld_status != 'advocate' and $ld_status != 'notary' and $ld_status != 'bankruptcy_commissioner' ) {
    	$reg_errors->add('ld_status_noncorrect', 'Статус заполнен некорректно!');
	}
	/*ld_secondname*/
	if ( empty( $ld_secondname ) ) {
    	$reg_errors->add('ld_secondname_none', 'Фамилия обязательна для заполнения!');
	}
	/*ld_name*/
	if ( empty( $ld_name ) ) {
    	$reg_errors->add('ld_name_none', 'Имя обязательно для заполнения!');
	}
	/*ld_patronymic*/
	if ( empty( $ld_patronymic ) ) {
    	$reg_errors->add('ld_patronymic_none', 'Отчество обязательно для заполнения!');
	}
	/*ld_city*/
	if ( empty( $ld_city ) ) {
    	$reg_errors->add('ld_city_none', 'Город обязателен для заполнения!');
	}
	/*ld_specialization_array*/
	if ( empty( $ld_specialization_array ) ) {
    	$reg_errors->add('ld_specialization_array_none', 'Специализация обязательна для заполнения!');
	}

	if ( is_wp_error( $reg_errors ) ) {

		$er = '0';

	    foreach ( $reg_errors->get_error_messages() as $error ) {

	    	if( $er >= '1') continue;
	    	$er = $er + 1;

	        echo '<div class="bs-callout-danger"><h4>Ошибка!</h4><p>';
	        echo $error;
	        echo '</p></div>';

	    }

	}
}

function ld_complete_registration($username, $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_city, $ld_specialization_array) {

    global $reg_errors, $username, $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_city, $ld_specialization_array;

	if ( 1 > count( $reg_errors->get_error_messages() ) ) {

    	$random_password = wp_generate_password(15, false); /// генерируем пароль

		$new_user_id = wp_create_user( $username, $random_password, $email ); // регестрируем нового пользователя

		$userdata = array(
			'ID' => $new_user_id,
			'role' => 'lawyer' // (строка) роль пользвателя
		);

		wp_update_user($userdata); //присваеваем пользователю роль.

		//wp_new_user_notification( $new_user_id, $random_password); //отправляем письмо администратору и пользователю.

		$ld_unique_string = md5($username.$new_user_id);

		update_user_meta( $new_user_id, 'ld_unique_string', $ld_unique_string );  // записываем уникальную строку для активации почты

		update_user_meta( $new_user_id, 'ld_mail_confirm', '0' ); // записываем в мета поле 0, что значит что поста еще не подтвержденна

		update_user_meta( $new_user_id, 'ld_status', sanitize_text_field($ld_status) );

		update_user_meta( $new_user_id, 'ld_secondname', sanitize_text_field($ld_secondname) );

		update_user_meta( $new_user_id, 'ld_name', sanitize_text_field($ld_name) );

		update_user_meta( $new_user_id, 'ld_patronymic', sanitize_text_field($ld_patronymic) );

		update_user_meta( $new_user_id, 'ld_city', sanitize_text_field($ld_city) );

		update_user_meta( $new_user_id, 'ld_specialization', $ld_specialization_array);

		// удалим фильтры, которые могут изменять заголовок $headers
		remove_all_filters( 'wp_mail_from' );
		remove_all_filters( 'wp_mail_from_name' );

		$ld_link_mail = '<a href="'.home_url().'/?do='.$new_user_id.'&code='.$ld_unique_string.'">'.home_url().'/?do='.$new_user_id.'&code='.$ld_unique_string.'</a>';

		ob_start();
		?>
Здравствуйте, <?php echo $ld_secondname.' '.$ld_name.' '.$ld_patronymic; ?>.<br>

Вы зарегистрировались на сайте law-divorce.ru<br><br>

Ваш логин: <?php echo $username; ?><br>

Ваш пароль: <?php echo $random_password; ?><br><br>

Подтвердите свой e-mail, перейдя по ссылке: <?php echo $ld_link_mail; ?><br>

Письмо создано автоматически, отвечать на него не нужно. Если вы получили данное письмо по ошибке, просто проигнорируйте его.
		<?php

		$ld_mail = ob_get_clean();

		$headers[] = 'From: law-divorce.ru <s@law-divorce.ru>';  ////// заголовки поправить
		$headers[] = 'content-type: text/html';

		wp_mail( $email, 'Пожалуйста, подтвердите регистрацию на сайте', $ld_mail, $headers);

		echo '<div class="bs-callout-success"><h4>Регистрация успешно завершена!</h4><p>Спасибо! Для входа в личный кабинет вам необходимо подтвердить регистрацию! На ваш электронный адрес отправлено письмо со ссылкой для подтверждения. Чтобы подтвердить свой адрес, откройте ссылку из письма. В противном случае вы не сможете получить доступ к личному кабинету и функциям сайта</p></div>';

        //Авторизируем нового пользователя
		$creds = array();
        $creds['user_login'] = $username;
        $creds['user_password'] = $random_password;
        $creds['remember'] = true;

        $user = wp_signon( $creds, false );
	}
}

function ld_custom_registration_function() {
												//проверка скрытых полей формы
	if (!( empty($_POST) || !wp_verify_nonce($_POST['bH37nfG7ej5G0F3'],'Hn9rU3ek0rG8rb') )) {

	        // проверка безопасности введенных данных
	        global $reg_errors, $username, $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_city, $ld_specialization_array;
	        $username      =   sanitize_user( $_POST['email'] );
	        $email         =   sanitize_email( $_POST['email'] );
	        $ld_status     =   $_POST['ld_status'];
	        $ld_secondname =   $_POST['ld_secondname'];
	        $ld_name       =   $_POST['ld_name'];
	        $ld_patronymic =   $_POST['ld_patronymic'];
	        $ld_captcha    =   $_POST["g-recaptcha-response"];
	        $ld_city       =   $_POST["ld_city"];
	        $ld_specialization_array = $_POST['ld_specialization'];

	        ld_registration_validation($username, $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_captcha, $ld_city, $ld_specialization_array);

	        // вызов function ld_complete_registration, чтобы создать пользователя
	       	ld_complete_registration($username, $email, $ld_status, $ld_secondname, $ld_name, $ld_patronymic, $ld_city, $ld_specialization_array);

	       	if ( 1 <= count( $reg_errors->get_error_messages() ) ) {
	       		ld_registration_form(isset($email)?$email:'', isset($ld_status)?$ld_status:'', isset($ld_secondname)?$ld_secondname:'', isset($ld_name)?$ld_name:'', isset($ld_patronymic)?$ld_patronymic:'', isset($ld_city)?$ld_city:'', isset($ld_specialization_array)?$ld_specialization_array:'');
	       	}
    } else {
    	ld_registration_form(isset($email)?$email:'', isset($ld_status)?$ld_status:'', isset($ld_secondname)?$ld_secondname:'', isset($ld_name)?$ld_name:'', isset($ld_patronymic)?$ld_patronymic:'', isset($ld_city)?$ld_city:'', isset($ld_specialization_array)?$ld_specialization_array:'');
    }

}

// Регистрируем новый шорткод: [ld_registration]

add_shortcode( 'ld_registration', 'ld_custom_registration_shortcode' );

function ld_custom_registration_shortcode() {
    ob_start();
    ld_custom_registration_function();
    return ob_get_clean();
}

//подтверждение почты
add_action( 'after_setup_theme', 'ld_confirm_mail_shortcode' );

function ld_confirm_mail_shortcode() {
	if( $_GET['do'] AND $_GET['code'] ) {
		$confirm = get_user_meta( $_GET['do'], 'ld_mail_confirm', true );
		if ($confirm == '0') {
			$confirm_code = get_user_meta( $_GET['do'], 'ld_unique_string', true );
			if ($confirm_code == $_GET['code']) {
				update_user_meta( $_GET['do'], 'ld_mail_confirm', '1' );
				wp_redirect( home_url().'/wp-admin/admin.php?page=your_information'); // тут вставить ссылку со страницей с сообщением об успешной активации
				exit;
			}
		}
	}
}