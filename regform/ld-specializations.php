<?php
///функция вывода чекбоксов,  для отображения отмеченных принимает массив типа ид_таксономии(ключ)=>ид_таксономии(значение)
function ld_view_chekbox($ld_specialization_array) {
	$ld_specializations = get_terms('specialization', 'orderby=none&hide_empty=0&parent=0');
	foreach ($ld_specializations as $ld_specialization) {

	    $ld_view.="<label class='chekbox'><input type='checkbox' name='ld_specialization[".$ld_specialization->term_id."]' value='".$ld_specialization->term_id."' ".checked( $ld_specialization->term_id, $ld_specialization_array[$ld_specialization->term_id], false ).">".$ld_specialization->name."</label>";

	    $childs_ld_specializations = get_terms('specialization', 'orderby=none&hide_empty=0&parent='.$ld_specialization->term_id);

	    if ($childs_ld_specializations) {
	    	$ld_razvernut = '0';
	    	foreach ($childs_ld_specializations as $childs_ld_specialization) {
	    		if ($childs_ld_specialization->term_id == $ld_specialization_array[$childs_ld_specialization->term_id]) {
	    			$ld_razvernut= '<fieldset class="razvernut"><legend></legend>';
	    			break;
	    		}
	    	}
	    	if ($ld_razvernut == '0') {
	    		$ld_razvernut= '<fieldset><legend></legend>';
	    	}
	    	$ld_view.=$ld_razvernut;

	    	foreach ($childs_ld_specializations as $childs_ld_specialization) {

	    		$ld_view.="<label class='chekbox'><input type='checkbox' name='ld_specialization[".$childs_ld_specialization->term_id."]' ".checked( $childs_ld_specialization->term_id, $ld_specialization_array[$childs_ld_specialization->term_id], false )." value='".$childs_ld_specialization->term_id."'>".$childs_ld_specialization->name."</label>";

    		}
    		$ld_view.= '</fieldset>';
	    }
	}
	echo $ld_view;
}
function ld_view_chekbox_lk($ld_specialization_array) {
	$ld_specializations = get_terms('specialization', 'orderby=none&hide_empty=0&parent=0');
	foreach ($ld_specializations as $ld_specialization) {

	    $ld_view.="<label class='chekbox'><input type='checkbox' name='ld_specialization[".$ld_specialization->term_id."]' value='".$ld_specialization->term_id."' ".checked( $ld_specialization->term_id, $ld_specialization_array[$ld_specialization->term_id], false ).">".$ld_specialization->name."</label>";

	    $childs_ld_specializations = get_terms('specialization', 'orderby=none&hide_empty=0&parent='.$ld_specialization->term_id);

	    if ($childs_ld_specializations) {

	    	$ld_razvernut= '<fieldset class="razvernut"><legend></legend>';

	    	$ld_view.=$ld_razvernut;

	    	foreach ($childs_ld_specializations as $childs_ld_specialization) {

	    		$ld_view.="<label class='chekbox'><input type='checkbox' name='ld_specialization[".$childs_ld_specialization->term_id."]' ".checked( $childs_ld_specialization->term_id, $ld_specialization_array[$childs_ld_specialization->term_id], false )." value='".$childs_ld_specialization->term_id."'>".$childs_ld_specialization->name."</label>";

    		}
    		$ld_view.= '</fieldset>';
	    }
	}
	echo $ld_view;
}
?>