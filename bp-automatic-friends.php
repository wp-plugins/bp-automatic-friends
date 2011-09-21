<?php
/*
Plugin Name: BuddyPress Automatic Friends
Plugin URI: http://www.stevenword.com/bp-automatic-friends/
Description: Automatically create and accept friendships for specified users upon new user registration. * Requires BuddyPress
Version: 1.1
Author: Steven K. Word
Author URI: http://www.stevenword.com
*/

/*
 Copyright 2011  Steven K Word  (email : stevenword@gmail.com)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Include the admin file if in wp-admin */
if ( is_admin() ){
	require_once( dirname(__FILE__).'/includes/admin.php' );

	//if (is_plugin_active('buddypress/bp-loader.php')) {
	//	echo 'BuddyPress Found!';
	//}
	//else echo 'BuddyPress Lost!';

}

/**
 * A Hook into User Register
 * 
 * When a initiator user registers for the blog, create initiator friendship with the specified user(s) and autoaccept those friendhips.
 *
*/
function skw_bpaf_user_register($initiator_user_id) {
	
	/*Get the user data for the initiatorly registered user.*/
	$initiator_user_info = get_userdata($initiator_user_id);

	/*Get the user data for the user specified in the admin settings.*/
	$friend_user_id = '1'; /*Get this from the options later*/
	$friend_user_info = get_userdata($friend_user_id);


	/* Get the friend users id(s) */
	$options = get_option( 'skw_bpaf_options' );
	$skw_bpaf_user_ids = $options['skw_bpaf_user_ids'];

	/* Check to see if the admin options are set*/
	if($skw_bpaf_user_ids != ''){

		$friend_user_ids = explode(',', $skw_bpaf_user_ids);
		foreach($friend_user_ids as $friend_user_id){
			
			/*Request the friendship*/
			if ( !friends_add_friend( $initiator_user_id, $friend_user_id, $force_accept = true ) ) {
				//bp_core_add_message( __( 'Friendship could not be requested.', 'buddypress' ), 'error' );
				//echo "<p>Friendship could not be requested.</p>";
			} else {
				//bp_core_add_message( __( 'Friendship requested', 'buddypress' ) );
				//echo "<p>Friendship requested</p>";
			}

			/*Get the friendship ID*/
			$friendship_id = friends_get_friendship_id( $initiator_user_id, $friend_user_id );
			//echo "<p>Friendship ID: ".$friendship_id."</p>";

		}//foreach
	}//if
	//exit;
	return;
}
add_action('user_register', 'skw_bpaf_user_register');