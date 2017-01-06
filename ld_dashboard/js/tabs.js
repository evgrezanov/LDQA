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