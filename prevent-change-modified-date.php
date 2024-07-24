<?php
/**
 * @wordpress-plugin
 * Plugin Name:       Prevent Change Modified Date
 * Plugin URI:        https://github.com/nabeghe/wordpress-prevent-change-modified-date
 * Description:       It prevents the modification date of posts from being edited, except by activating a checkbox when updating the post.
 * Version:           0.1.0
 * Requires at least: 6.0
 * Requires PHP:      5.6
 * Author:            Hadi Akbarzadeh
 * Author URI:        https://elatel.ir
 */

function herminal_prevent_change_modified_date_post_types() {
	return apply_filters( 'prevent_change_modified_date_post_types', [ 'post', 'page', 'article', 'product' ] );
}

add_action( 'post_submitbox_misc_actions', '_herminal_prevent_change_modified_date' );
function _herminal_prevent_change_modified_date( $post ) {

	if ( in_array( get_post_type( $post->ID ), herminal_prevent_change_modified_date_post_types() ) ) : ?>
        <div class="misc-pub-section misc-pub-section-last">
            <label>
                <input type="checkbox" value="1" name="herminal_change_modify_time"/>Modify
            </label>
        </div>
	<?php endif;
}

add_filter( 'wp_insert_post_data', '_herminal_prevent_modified_until_check', 20, 4 );
function _herminal_prevent_modified_until_check( $data, $postarr, $unsanitized_postarr, $update ) {
	if (
		isset( $data ) &&
		isset( $data['post_type'] ) &&
		in_array( $data['post_type'], herminal_prevent_change_modified_date_post_types() ) &&
		isset( $postarr ) &&
		empty( $_POST['herminal_change_modify_time'] ) &&
		isset( $data['post_modified'] ) &&
		isset( $postarr['post_modified'] )
	) {
		$data['post_modified']     = $postarr['post_modified'];
		$data['post_modified_gmt'] = $postarr['post_modified_gmt'];
	}
}