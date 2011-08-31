<?php
/* Setup the Admin */
function skw_bpaf_admin_init(){

	//Register Settings
	register_setting( 'skw_bpaf_options', 'skw_bpaf_options', 'skw_bpaf_settings_validate_options' );

	//Settings - General Section
	add_settings_section(
		'skw_bpaf_settings_general',
		'General Options',
		'skw_bpaf_settings_text',
		'skw_bpaf_settings_page'
	);

	add_settings_field('skw_bpaf_user_ids', 'User ID(s)', 'skw_bpaf_settings_user_ids_input', 'skw_bpaf_settings_page', 'skw_bpaf_settings_general' );

}
add_action( 'admin_init', 'skw_bpaf_admin_init');

/* Setup Admin Menu Options & Settings */
function skw_bpaf_admin_menu() {

	if ( !is_site_admin() )
		return false;
	add_submenu_page( 'bp-general-settings', __( 'BuddyPress Automatic Friends', 'skw-bpaf-settings'), __( 'Automatic Friends', 'skw-bpaf-settings' ), 'manage_options', 'skw-bpaf-settings', 'skw_bpaf_settings_page' );

}
add_action( 'admin_menu', 'skw_bpaf_admin_menu', '11' );


/* Settings Page */
function skw_bpaf_settings_page(){
	?>
	<div class="wrap">
		<?php //screen_icon(); ?>
		<h2>BuddyPress Automatic Friends Settings</h2>
		<form method="post" action="options.php">
		<?php settings_fields('skw_bpaf_options');?>
		<?php do_settings_sections('skw_bpaf_settings_page');?>
		<input name="Submit" type="submit" value="Save Changes" />
		</form>
	</div><!--/.wrap-->
	<?php
}
/* Instructions*/
function skw_bpaf_settings_text() {
	echo "<p>Enter the user id(s) you would like to autofriend upon new user registration.</p>";
}

/* Form Inputs */
function skw_bpaf_settings_user_ids_input() {
	$options = get_option( 'skw_bpaf_options' );
	//print_r($options);
	$user_ids = $options['skw_bpaf_user_ids'];
	
	echo "<p>";
	echo "<input class='regular-text' id='skw_bpaf_user_ids' name='skw_bpaf_options[skw_bpaf_user_ids]' type='text' value='$user_ids' />";
	echo "<span class='description'>* comma separated</span>";
	echo "</p>";
}

/* Form Validation */
function skw_bpaf_settings_validate_options($input) {
	$valid = array();
	$valid['skw_bpaf_user_ids'] = preg_replace(
		'/[^0-9,]/',
		'',
		$input['skw_bpaf_user_ids']
	);
	//$valid['skw_bpaf_user_ids'] = $input['skw_bpaf_user_ids'];

	return $valid;
}
?>