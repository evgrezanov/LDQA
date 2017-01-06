<?php

/*страница сортировки*/
 function cp_cp_podmenu_html(){
	if (!current_user_can('manage_options')){
	  wp_die( __('У вас нет прав для просмотра данной страницы.') );
	}
	

	echo '<h2>'.__('Сортировка записей таксономии').'</h2>';
?>	
	    <select id="selecttaxterm" name="selecttaxterm" >
		    <option value='0'><?php _e('--Выберите термин--'); ?></option>
	        <?php
					      
						  $argsTax=array(
                                'public'   => true,
                                '_builtin' => false
                            );
						  
						  $output = 'objects';
                          $operator = 'or';
						  
						  $argsTerm = array(
                               'number' 		=> 0,
                               'offset' 		=> 0,
                               'orderby' 		=> 'name',
                               'order' 		    => 'ASC',
                               'hide_empty' 	=> true,
                               'fields' 		=> 'all',
                               'slug' 		    => '',
                               'hierarchical'   => false,
                               'name__like' 	=> '',
                               'pad_counts' 	=> false,
                               'get' 			=> '',
                               'child_of' 	    => 0,
                               'parent' 		=> '',
                            );
                           
						  
                          $taxonomies=get_taxonomies($argsTax,$output,$operator);
					     
						  if  ($taxonomies) {
				              foreach ($taxonomies as $taxonomy ) {
                                  echo '<optgroup class="'.$taxonomy->name.'" label="'.$taxonomy->label.'">';
						          $myterms = get_terms($taxonomy->name, $argsTerm);
								  if ($myterms) {
								      foreach ($myterms as $term){
                                          echo '<option class="'.$term->taxonomy.'" value="'.$term->term_id.'">'.$term->name.'</option>';
                                        } 
							        }
                                }
						    }			       
					   ?> 
	    </select>
		<!-- выбор элемента списка -->
		<script type="text/javascript">
	        var dropdown = document.getElementById("selecttaxterm");
			var loc = window.location;
	        function onCatChange() {
				if ( dropdown.options[dropdown.selectedIndex].value > 0 ) {
					location.href = "<?php echo home_url(); ?>/wp-admin/edit.php?page=custom_post_order&term_id="+dropdown.options[dropdown.selectedIndex].value;
		        }
	        }
	        dropdown.onchange = onCatChange;
        </script>
		</br>
		

<?php	
    if ( isset($_REQUEST['term_id'])){
      
	  $term_id=$_REQUEST['term_id'];
	  $site_url = get_site_url();
      $our_term_url = $site_url.'/wp-admin/edit.php?page=custom_post_order&term_id='.$term_id;
	  	
	  // если выбрана ли сортировка по алфавиту 
	  if ( isset($_REQUEST['date'])) {
	      $sort_date=$_REQUEST['date'];		  
		}
	  
	  // если выбрана сортировка по дате
	  if ( isset($_REQUEST['abc'])) {
	      $sort_abc=$_REQUEST['abc'];
		}
	  	  
	  		// проверим было ли отсортировано по date\abc
	        $check_type_sort_option = get_option('cp-sort-type-term-id-'.$term_id);
	        ////error_log($check_type_sort_option);
			if (($check_type_sort_option=='date') or ($check_type_sort_option=='')) {
	            $list_title = 'Cортировка записей по дате';
            }
			
	        if ($check_type_sort_option=='abc') {
	            $list_title = 'Cортировка записей по алфавиту';
            }
			
			if ($check_type_sort_option=='custom') {
			    $list_title = 'Произвольная сортировка записей';
			}
			
					
	        // узнаем были ли отсортированы записи в рамках данного термина
	        $option_name = 'cp-sort-term-id-'.$term_id;
	        $term_sort = get_option($option_name);
	        $meta_key = 'cp-term-id-'.$term_id;
	  
	        // узнаем тип сортировки
	        $sort_type = 'cp-sort-type-term-id-'.$term_id;
	  
	        // узнаем таксономию выбранного термина
	        $tax_name = cp_get_tax_name_by_term_id($term_id);
            $tax_obj = get_taxonomy($tax_name);
	        $term_obj = get_term($term_id,$tax_name);
	  
	        // ссылка на страницу термина
	        $term_link = get_term_link((int)$term_id,$tax_name);
	        echo '<h3>'.$tax_obj->label.': <a href="'.$term_link.'">'.$term_obj->name.'</a></h3>';
      
	  // вывод списка постов
	  if ( isset($tax_name)) {						
                
				// если ранее была произвольная сортировка
				if ($term_sort=='true'){
			        $args = array(
	                    'tax_query' => array(
		                    array(
			                    'taxonomy' => $tax_name,
			                    'field' => 'id',
			                    'terms' => array((int)($term_id))
		                    )
	                    ),
	                    'posts_per_page' => -1 ,
						'orderby' => 'meta_value_num',
						'meta_key' => $meta_key,
						'order' => 'ASC'
                    );
			    }
				
				// по умолчанию 
				else {
				    $args = array(
					    'tax_query' => array(
		                    array(
			                    'taxonomy' => $tax_name,
			                    'field' => 'id',
			                    'terms' => array( $term_id )
		                    )
	                    ),
	                    'posts_per_page' => -1,
						'orderby' => 'date',
						'order' => 'DESC'
                    );
					$list_title = 'Cортировка записей по дате';
					$sort_def = 'true';
				}
				
				// по алфавиту
				if ( isset($sort_abc))  {
				    $args = array(
					    'tax_query' => array(
		                    array(
			                    'taxonomy' => $tax_name,
			                    'field' => 'id',
			                    'terms' => array( $term_id )
		                    )
	                    ),
	                    'posts_per_page' => -1,
						'orderby' => 'title',
						'order' => 'ASC'
                    );
					$list_title = 'Cортировка записей по алфавиту';
				}
				
				// по дате
				if ( isset($sort_date) ) {
				    $args = array(
					    'tax_query' => array(
		                    array(
			                    'taxonomy' => $tax_name,
			                    'field' => 'id',
			                    'terms' => array( $term_id )
		                    )
	                    ),
	                    'posts_per_page' => -1,
						'orderby' => 'date',
						'order' => 'DESC'
                    );
					$list_title = 'Cортировка записей по дате';
				}
            
			
			$posts = get_posts( $args );
            
			echo '<a href="'.add_query_arg( 'date', '1',$our_term_url ).'">'.__('сортировать по дате').'</a></br>';
			echo '<a href="'.add_query_arg( 'abc', '1',$our_term_url ).'">'.__('сортировать по алфавиту').'</a>';
			
			echo '<h3>'.__($list_title).':</h3>';
			
			// вывод постов в админке
			echo '<ul id="sortable">';
			
			$i=1;
            foreach($posts as $pst) {
			    /*если была выбрана сортировка по алфавиту или по дате то сразу пропишем мету*/
				$i++;
				if ((isset ($sort_date)) or (isset ($sort_def))){
					update_post_meta($pst->ID, 'cp-term-id-'.$term_id, $i);
					
					// сразу запишем опцию
					update_option('cp-sort-type-term-id-'.$term_id, 'date');
				}
				if (isset ($sort_abc)){
					update_post_meta($pst->ID, 'cp-term-id-'.$term_id, $i);
					
					// сразу запишем опцию 
					update_option('cp-sort-type-term-id-'.$term_id, 'abc');
				}
				/*---закончили---*/
	            
				$sort = get_post_meta($pst->ID, 'cp-term-id-'.$term_id, true);
				echo '<li class="ui-state-default" id="arrayorder_'.$pst->ID.'">'.$pst->post_title.'</li>';
			}
			echo '</ul>';
			wp_reset_query();
			echo '<div id="info"></div>';
			
	    }
	}
	?>
    <?php /* сортировка и передача порядка в функцию */?>
	<script type="text/javascript">
        $(function(){
           $("#sortable").sortable({
		        update : function () {
			        var sort = $("#sortable").sortable('serialize', { key: 'post_id' });
			        console.log(sort);
					tax_name = <?php if (isset ($tax_name)) {echo "'".$tax_name."'";} ?>;
					term_id = <?php if (isset ($term_id)) {echo "'".$term_id."'";} ?>;
					$.ajax({
					    data: {
					        sort : sort,
							tax_name : tax_name,
							term_id : term_id,
							action : 'cp_save_sort'
						},
						url: ajaxurl,
						success: function(data) {
                                }
					});
			    }
		    });
        });
	</script>
	<?php
}

/* получить количество постов термина */
function cp_get_post_count($tax_name, $term_id) {
    $args = array(
		'tax_query' => array(
		    array(
		        'taxonomy' => $tax_name,
		        'field' => 'id',
			    'terms' => array( $term_id )
		    )
	    ),
	    'posts_per_page' => -1,
    );
	$posts = get_posts( $args );
	$i=0;
	foreach($posts as $pst) {
		$i++;
	}
	wp_reset_query();
	return $i;
}

/*добавляем ссылку на переход на строницу сортировки*/ 
//add_action('admin_notices', 'cp_example_admin_notice');
function cp_example_admin_notice() {
  global $pagenow;
  global $wp;
  $edit_page=0;  
  if ($pagenow == 'edit-tags.php') {
      $tmp ='?action=edit&taxonomy=';
      $current_url = home_url(add_query_arg(array()));
	  $edit_page = substr_count($current_url, $tmp);
	  if ($edit_page==1) {
	        $tag_ID = $_REQUEST['tag_ID'];
		    $tax_name = $_REQUEST['taxonomy'];
		    $post_count = cp_get_post_count($tax_name,$tag_ID);
		    if ($post_count>0) {
                echo '<div class="updated"><p>';
                printf(__('Для сортировки записей перейдите по <a href="%1$s">ссылке</a>'), home_url().'/wp-admin/edit.php?page=custom_post_order&term_id='.$tag_ID);
                echo "</p></div>";
		    }
        }		 
	}
}

/*функция определяет название таксономии по переданому ид термина*/
function cp_get_tax_name_by_term_id($term_id) {
    $argsTax=array(
        'public'   => true,
        '_builtin' => false
    );
	$output = 'objects';
    $operator = 'or';
	
	$argsTerm = array(
        'number' 		=> 0,
        'offset' 		=> 0,
        'orderby' 		=> 'id',
        'order' 		=> 'ASC',
        'hide_empty' 	=> true,
        'fields' 		=> 'all',
        'slug' 		    => '',
        'hierarchical'  => true,
        'name__like' 	=> '',
        'pad_counts' 	=> false,
        'get' 			=> '',
        'child_of' 	    => 0,
        'parent' 		=> '',
    );
	$taxonomies=get_taxonomies($argsTax,$output,$operator);
	foreach ($taxonomies as $taxonomy ) {
	    $myterms = get_terms($taxonomy->name, $argsTerm);
		if ($myterms) {
	        foreach ($myterms as $term){
				if ($term_id==$term->term_id) {
				    $tax_name=$taxonomy->name;  
				}
			}
		}
	}
	return $tax_name;
}

?>