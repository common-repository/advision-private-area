<?php
/*
Plugin Name:  Advision Private Area
Plugin URI:   www.advisionplus.com
Description:  This Plugin allow you to get a private area for each user of your website.
Version:      0.0.2
Author:       Marco Pappalardo
Author URI:   www.marcopappalardo.it
License:      GPL2
Text Domain:  adv-private-area
Domain Path:  /languages
*/

//Callback Scripts
if (!function_exists('adv_pvt_callback_for_setting_up_scripts')) {
	function adv_pvt_callback_for_setting_up_scripts() {
		wp_register_style( 'adv-private-area', plugins_url('css/style.css', __FILE__));
		wp_enqueue_style( 'adv-private-area' ); 
		wp_register_style( 'adv-font-awesome', plugins_url('includes/font-awesome/css/font-awesome.min.css', __FILE__));
		wp_enqueue_style( 'adv-font-awesome' ); 
	}
	add_action('wp_enqueue_scripts', 'adv_pvt_callback_for_setting_up_scripts');
}

//Richiamo il template di pagina
function adv_pvt_template_reservedarea(){
	include_once ('templates/template_adv_privatearea.php');
	adv_pa_shortcode();	
}
add_shortcode('adv_reserved_area', 'adv_pvt_template_reservedarea');


// Register Custom Post Type Private Area
// Post Type Key: adv_privatearea
if (!function_exists('adv_pvt_create_privatearea_cpt')) {
	function adv_pvt_create_privatearea_cpt() {

		$labels = array(
			'name' => _x( 'Private Areas', 'Post Type General Name', 'adv-private-area' ),
			'singular_name' => _x( 'Private Area', 'Post Type Singular Name', 'adv-private-area' ),
			'menu_name' => _x( 'Private Areas', 'Admin Menu text', 'adv-private-area' ),
			'name_admin_bar' => _x( 'Private Area', 'Add New on Toolbar', 'adv-private-area' ),
			'archives' => __( 'Private Area Archives', 'adv-private-area' ),
			'attributes' => __( 'Private Area Attributes', 'adv-private-area' ),
			'parent_item_colon' => __( 'Parent Private Area:', 'adv-private-area' ),
			'all_items' => __( 'All Private Areas', 'adv-private-area' ),
			'add_new_item' => __( 'Add New Private Area', 'adv-private-area' ),
			'add_new' => __( 'Add New', 'adv-private-area' ),
			'new_item' => __( 'New Private Area', 'adv-private-area' ),
			'edit_item' => __( 'Edit Private Area', 'adv-private-area' ),
			'update_item' => __( 'Update Private Area', 'adv-private-area' ),
			'view_item' => __( 'View Private Area', 'adv-private-area' ),
			'view_items' => __( 'View Private Areas', 'adv-private-area' ),
			'search_items' => __( 'Search Private Area', 'adv-private-area' ),
			'not_found' => __( 'Not found', 'adv-private-area' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'adv-private-area' ),
			'featured_image' => __( 'Featured Image', 'adv-private-area' ),
			'set_featured_image' => __( 'Set featured image', 'adv-private-area' ),
			'remove_featured_image' => __( 'Remove featured image', 'adv-private-area' ),
			'use_featured_image' => __( 'Use as featured image', 'adv-private-area' ),
			'insert_into_item' => __( 'Insert into Private Area', 'adv-private-area' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Private Area', 'adv-private-area' ),
			'items_list' => __( 'Private Areas list', 'adv-private-area' ),
			'items_list_navigation' => __( 'Private Areas list navigation', 'adv-private-area' ),
			'filter_items_list' => __( 'Filter Private Areas list', 'adv-private-area' ),
		);
		$capabilities = array(
			'edit_post'             => 'edit_area',
			'read_post'             => 'read_area',
			'delete_post'           => 'delete_area',
			'edit_posts'            => 'edit_areas',
			'edit_others_posts'     => 'edit_others_areas',
			'publish_posts'         => 'publish_areas',
			'read_private_posts'    => 'read_private_areas',
		);
		$args = array(
			'label' => __( 'Private Area', 'adv-private-area' ),
			'description' => __( 'This Post Type create all the dashboard for each user into web site', 'adv-private-area' ),
			'labels' => $labels,
			'menu_icon' => 'dashicons-businessman',
			'supports' => array('title', 'editor', 'thumbnail'),
			'taxonomies' => array(),
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 5,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'can_export' => true,
			'has_archive' => true,
			'hierarchical' => false,
			'exclude_from_search' => false,
			'show_in_rest' => true,
			'publicly_queryable' => true,
			'capabilities' => $capabilities,
		);
		register_post_type( 'adv_privatearea', $args );

	}
	add_action( 'init', 'adv_pvt_create_privatearea_cpt', 0 );
}


add_role('site_manager', __('Site Manager'),array(
			'edit_area'				=> true,
			'read_area'             => true,
			'delete_area'           => true,
			'edit_areas'            => true,
			'edit_others_areas'     => true,
			'publish_areas'         => true,
			'read_private_areas'    => true,
			'read'					=> true,
       )
);

function adv_pvt_admin_role_caps() {
    // Gets the simple_role role object.
    $role = get_role( 'administrator' );
 
    // Add a new capability.
    $role->add_cap('edit_area', true);
    $role->add_cap('read_area', true);
    $role->add_cap('delete_area', true);
    $role->add_cap('edit_areas', true);
    $role->add_cap('edit_others_areas', true);
    $role->add_cap('publish_areas', true);
    $role->add_cap('read_private_areas', true);
    $role->add_cap('read', true);
}
 
// Add simple_role capabilities, priority must be after the initial role definition.
add_action( 'init', 'adv_pvt_admin_role_caps', 11 );


global $private_area;
$args = array(
    'post_type' => 'adv_privatearea',
);
$private_area = get_posts($args);


//rendo il post type privato di default
if (!function_exists('adv_force_type_private')) {
	function adv_force_type_private( $new_status, $old_status, $post ) { 
		if ( $post->post_type == 'adv_privatearea' && $new_status == 'publish' && $old_status  != $new_status ) {
			$post->post_status = 'private';
			wp_update_post( $post );
		}
	} 
	add_action( 'transition_post_status', 'adv_force_type_private', 10, 3 );
}


//rendo il post privato visibile al ruolo sottoscrittore
if (!function_exists('adv_private_posts_subscribers')) {
	function adv_private_posts_subscribers(){
		$subRole = get_role( 'subscriber' );
		$subRole->add_cap( 'read_private_posts' );
		$subRole->add_cap( 'read_private_pages' );
		}
	add_action( 'init', 'adv_private_posts_subscribers' );
}


// rimuovo privato dal titolo
if (!function_exists('adv_pvt_clean_title')) {
	function adv_pvt_clean_title($titolo) {
		$titolo = esc_attr($titolo);
		$cerca = array(
			'#Private:#',
			'#Privato:#'
		);
		$sostituisci = array(
			'' // Sostituiamo la voce "Privato" con
		);
		$titolo = preg_replace($cerca, $sostituisci, $titolo);
		return $titolo;
	}
	add_filter('the_title', 'adv_pvt_clean_title');
}


// Meta Box Class: User Detail
// Get the field value: $metavalue = get_post_meta( $post_id, $field_id, true );
class adv_pvt_userdetailMetabox {

	private $screen = array(
		//'post',
		'adv_privatearea',
	);

	private $meta_fields = array(
		array(
			'label' => 'User Account',
			'id' => 'adv_user_account',
			'type' => 'users',
		),
	);

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
	}

	public function add_meta_boxes() {
		foreach ( $this->screen as $single_screen ) {
			add_meta_box(
				'userdetail',
				__( 'User Detail', 'adv-private-area' ),
				array( $this, 'meta_box_callback' ),
				$single_screen,
				'side',
				'default'
			);
		}
	}

	public function meta_box_callback( $post ) {
		wp_nonce_field( 'userdetail_data', 'userdetail_nonce' );
		echo 'Select the user who will appear this area';
		$this->field_generator( $post );
	}

	public function field_generator( $post ) {
        $output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_post_meta( $post->ID, $meta_field['id'], true );
			if ( empty( $meta_value ) ) {
				if ( isset( $meta_field['default'] ) ) {
					$meta_value = $meta_field['default'];
				}
			}
			switch ( $meta_field['type'] ) {
				case 'users':
					$usersargs = array(
						'selected' => $meta_value,
						'echo' => 0,
						'name' => $meta_field['id'],
						'id' => $meta_field['id'],
						'show_option_none' => 'Select a user',
					);
					$input = wp_dropdown_users($usersargs);
					break;
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? 'style="width: 100%"' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
	}

	public function format_rows( $label, $input ) {
		return '<tr><th>'.$label.'</th><td>'.$input.'</td></tr>';
	}

	public function save_fields( $post_id ) {
		if ( ! isset( $_POST['userdetail_nonce'] ) )
			return $post_id;
		$nonce = $_POST['userdetail_nonce'];
		if ( !wp_verify_nonce( $nonce, 'userdetail_data' ) )
			return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		foreach ( $this->meta_fields as $meta_field ) {
			if ( isset( $_POST[ $meta_field['id'] ] ) ) {
				switch ( $meta_field['type'] ) {
					case 'email':
						$_POST[ $meta_field['id'] ] = sanitize_email( $_POST[ $meta_field['id'] ] );
						break;
					case 'text':
						$_POST[ $meta_field['id'] ] = sanitize_text_field( $_POST[ $meta_field['id'] ] );
						break;
				}
				update_post_meta( $post_id, $meta_field['id'], sanitize_text_field( $_POST[ $meta_field['id'] ] ) );
			} else if ( $meta_field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, $meta_field['id'], '0' );
			}
		}
	}
}

if (class_exists('adv_pvt_userdetailMetabox')) {
	new adv_pvt_userdetailMetabox;
};

// Meta Box Class: Content
// Get the field value: $metavalue = get_post_meta( $post_id, $field_id, true );
class adv_pvt_contentMetabox {

	private $screen = array(
		'adv_privatearea',
	);

	private $meta_fields = array(
		array(
			'label' => 'Document',
			'id' => 'adv_document',
			'returnvalue' => 'url',
			'type' => 'media',
		),
	);

	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'admin_footer', array( $this, 'media_fields' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
	}

	public function add_meta_boxes() {
		foreach ( $this->screen as $single_screen ) {
			add_meta_box(
				'content',
				__( 'Content', 'adv-private-area' ),
				array( $this, 'meta_box_callback' ),
				$single_screen,
				'advanced',
				'default'
			);
		}
	}

	public function meta_box_callback( $post ) {
		wp_nonce_field( 'content_data', 'content_nonce' );
		echo __("Attached Content to display on font-end.", "adv-private-area");
		$this->field_generator( $post );
	}
	public function media_fields() {
		?><script>
			jQuery(document).ready(function($){
				if ( typeof wp.media !== 'undefined' ) {
					var _custom_media = true,
					_orig_send_attachment = wp.media.editor.send.attachment;
					$('.content-media').click(function(e) {
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(this);
						var id = button.attr('id').replace('_button', '');
						_custom_media = true;
							wp.media.editor.send.attachment = function(props, attachment){
							if ( _custom_media ) {
								if ($('input#' + id).data('return') == 'url') {
									$('input#' + id).val(attachment.url);
								} else {
									$('input#' + id).val(attachment.id);
								}
								$('div#preview'+id).css('background-image', 'url('+attachment.url+')');
							} else {
								return _orig_send_attachment.apply( this, [props, attachment] );
							};
						}
						wp.media.editor.open(button);
						return false;
					});
					$('.add_media').on('click', function(){
						_custom_media = false;
					});
					$('.remove-media').on('click', function(){
						var parent = $(this).parents('td');
						parent.find('input[type="text"]').val('');
						parent.find('div').css('background-image', 'url()');
					});
				}
			});
		</script><?php
	}

	public function field_generator( $post ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_post_meta( $post->ID, $meta_field['id'], true );
			if ( empty( $meta_value ) ) {
				if ( isset( $meta_field['default'] ) ) {
					$meta_value = $meta_field['default'];
				}
			}
			switch ( $meta_field['type'] ) {
				case 'media':
					$meta_url = '';
						if ($meta_value) {
							if ($meta_field['returnvalue'] == 'url') {
								$meta_url = $meta_value;
							} else {
								$meta_url = wp_get_attachment_url($meta_value);
							}
						}
					$input = sprintf(
						'<input style="display:none;" id="%s" name="%s" type="text" value="%s"  data-return="%s"><input style="width: 19%%;margin-right:5px;" class="button content-media" id="%s_button" name="%s_button" type="button" value="Select" /><input style="width: 19%%;" class="button remove-media" id="%s_buttonremove" name="%s_buttonremove" type="button" value="Clear" />',
						$meta_field['id'],
						$meta_field['id'],
						$meta_value,
						$meta_field['returnvalue'],
						$meta_field['id'],
						$meta_url,
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['id']
					);
					break;
				default:
					$input = sprintf(
						'<input %s id="%s" name="%s" type="%s" value="%s">',
						$meta_field['type'] !== 'color' ? 'style="width: 100%"' : '',
						$meta_field['id'],
						$meta_field['id'],
						$meta_field['type'],
						$meta_value
					);
			}
			$output .= $this->format_rows( $label, $input );
		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
		$document = get_post_meta(get_the_ID(), 'adv_document', true);
		if($document){
			echo '<div>'.__("You already Attached a document.","adv-private-area");
			echo '<a target="_blank" style="margin:0px 10px;" href="'.get_post_meta(get_the_ID(), 'adv_document', true).'">'.__("Preview Document Attached","adv-private-area").'</a>';
			echo '</div>';
		}
	}

	public function format_rows( $label, $input ) {
		return '<tr><th>'.$label.'</th><td>'.$input.'</td></tr>';
	}

	public function save_fields( $post_id ) {
		if ( ! isset( $_POST['content_nonce'] ) )
			return $post_id;
		$nonce = $_POST['content_nonce'];
		if ( !wp_verify_nonce( $nonce, 'content_data' ) )
			return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;
		foreach ( $this->meta_fields as $meta_field ) {
			if ( isset( $_POST[ $meta_field['id'] ] ) ) {
				switch ( $meta_field['type'] ) {
					case 'email':
						$_POST[ $meta_field['id'] ] = sanitize_email( $_POST[ $meta_field['id'] ] );
						break;
					case 'text':
						$_POST[ $meta_field['id'] ] = sanitize_text_field( $_POST[ $meta_field['id'] ] );
						break;
				}
				update_post_meta( $post_id, $meta_field['id'], sanitize_text_field( $_POST[ $meta_field['id'] ] ) );
			} else if ( $meta_field['type'] === 'checkbox' ) {
				update_post_meta( $post_id, $meta_field['id'], '0' );
			}
		}
	}
}

if (class_exists('adv_pvt_contentMetabox')) {
	new adv_pvt_contentMetabox;
};