/*
	Twitter Profile Script
*/

jQuery(document).ready(function() {
	
	var tw_profile_wrap_width = jQuery('.wpt-tw-profile-wrap').width(); // get wrap width
	
	if( tw_profile_wrap_width <= 240 ){ // if wrap width equal or less than 240 pixel add wpt-tp-responsive-wrap class
		
		jQuery('.wpt-tw-profile-wrap').addClass('wpt-tp-responsive-wrap'); // add wpt-tp-responsive-wrap class
		
	}
	
});