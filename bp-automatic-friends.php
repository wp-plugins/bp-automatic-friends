<?php
/*
Plugin Name: BuddyPress Automatic Friends
Plugin URI: http://www.stevenword.com/bp-automatic-friends/
Description: Automatically create and accept friendships for specified users upon new user registration. * Requires BuddyPress
Version: 1.6.1
Author: Steven K. Word
Author URI: http://www.stevenword.com
*/

/*
 Copyright 2009  Steven K Word  (email : stevenword@gmail.com)

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

/** 
 * Loader function only fires if BuddyPress exists
 * @uses is_admin, add_action
 * @action bp_loaded
 * @return null
 */
function skw_bpaf_loader(){

	/* Load the admin */
	if ( is_admin() ){
		require_once( dirname(__FILE__).'/includes/admin.php' );
	}
	
	/* A Hook into BP Core Activated User */
	add_action('bp_core_activated_user', 'skw_bpaf_activated_user');
}
add_action( 'bp_loaded', 'skw_bpaf_loader');

/**
 * A Hook into BP Core Activated User
 * When a initiator user registers for the blog, create initiator friendship with the specified user(s) and autoaccept those friendhips.
 * @global bp
 * @param initiator_user_id
 * @uses get_userdata, get_option, explode, friends_add_friend, get_friend_user_ids, total_friend_count
 * @return null
 */
function skw_bpaf_activated_user($initiator_user_id) {
	
	global $bp;

	/* Get the user data for the initiatorly registered user. */
	$initiator_user_info = get_userdata($initiator_user_id);

	/* Get the user data for the user specified in the admin settings. */
	$friend_user_info = get_userdata($friend_user_id);

	/* Get the friend users id(s) */
	$options = get_option( 'skw_bpaf_options' );
	$skw_bpaf_user_ids = $options['skw_bpaf_user_ids'];

	/* Check to see if the admin options are set*/
	if($skw_bpaf_user_ids != ''){

		$friend_user_ids = explode(',', $skw_bpaf_user_ids);
		foreach($friend_user_ids as $friend_user_id){
			
			/* Request the friendship */
			if ( !friends_add_friend( $initiator_user_id, $friend_user_id, $force_accept = true ) ) {
				return false;
			}
			else {     
				/* Get friends of $user_id */
				$friend_ids = BP_Friends_Friendship::get_friend_user_ids( $initiator_user_id ); 

				/* Loop through the initiator's friends and update their friend counts */
                foreach ( (array)$friend_ids as $friend_id ) { 
					BP_Friends_Friendship::total_friend_count( $friend_id ); 	 	 
	            }
	            
	            /* Update initiator friend counts */
	            BP_Friends_Friendship::total_friend_count( $initiator_user_id ); 
			}
			
		}
			
	}
	return;
}