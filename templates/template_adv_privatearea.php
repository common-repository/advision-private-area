<?php

if (!function_exists('adv_pa_shortcode')){
	function adv_pa_shortcode(){
		//Get curren user information
		if (is_user_logged_in()){
			global $current_user; 
				wp_get_current_user(); 
				$my_user = $current_user->user_login ; 
				$my_user_level = $current_user->user_level ;
				$my_user_id = $current_user->ID;
				echo '<div class="adv_pvt_welcome_user"><i class="fa fa-user" aria-hidden="true"></i>'.__("Welcome, ","adv-private-area") .$my_user.'</div>';

			
			
				//Query post private areas
				$args = array(
					'post_type' => 'adv_privatearea',
					'meta_query' => array(
							array(
								'key' => 'adv_user_account',
								'value' => $my_user_id,
								'compare' => '='
							),
					)
				);

				$the_query = new WP_Query( $args );


				//Display content Area
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) {
						$the_query->the_post();

						$content = do_shortcode( get_the_content() );

						echo '<div class="adv_pvt_title">' . get_the_title() . '</div>';
						echo '<div class="adv_pvt_date">'.__("Uploaded on ","adv-private-area") .get_the_date('j F Y'). '</div>';
						echo '<div class="adv_pvt_content">'.$content.'</div>';
						$attached_document = get_post_meta(get_the_ID(), 'adv_document', true );				
						echo '<div class="adv_pvt_attached_document"><a href="'.$attached_document.'">'.__("Download Document","adv-private-area").'</a></div>';
					}
				} else {
					echo '<div class="adv_pvt_no_content">'.__("No content avalaible.", "adv-private-area").'</div>';
				}
				/* Restore original Post Data */
				wp_reset_postdata();
		}else{

			//LOGIN FORM
			echo '<div class="adv_pvt_login_title_reserved_area">'.__("Reserved Area","adv-private-area").'</div>';
			
			echo '<div class="adv_pvt_div_form">';
			echo '<div class="adv_pvt_login_form">';
			echo '<div class="adv_pvt_login_title">'.__("Login","adv-private-area").'</div>';
			echo '<div class="adv_pvt_login_text">'.__("Log in to access your reserved area","adv-private-area").'</div>';
			wp_login_form();
			echo '</div>';
			
		}
	}
}