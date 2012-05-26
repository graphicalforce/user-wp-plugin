<?php
/*
Plugin Name: Get user info
Description: A way to get the avatar, name, and description using a shortcode : use [authorinfo user="User Name"] and/or use a widget in the sidebar and just give the username of the user you want to get
Version: 1.0
Author: Jeff Freeman
Author URI: http://graphicalforce.com
*/

/**
 * Adds Featured_User widget.
 */
class Featured_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'featured_widget', // Base ID
			'Featured_User', // Name
			array( 'description' => __( 'Display the name, description, and avatar of any user. Just enter the username.', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = "Featured Seller of the Day";
		$user = apply_filters( 'widget_title', $instance['title'] );

		$user = get_userdatabylogin($user);
		$user_last = $user->last_name;
		$user_first = $user->first_name;
		$user_description = $user->description;
		$user_email = $user->user_email;
		$user_avatar = get_avatar($user_email);
		
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		    echo "user = {$user_first} {$user_last} description: {$user_description} avatar {$user_avatar}";
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'New title', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'User Name' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class Featured_User

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "featured_widget" );' ) );

/**
 * Adds Featured_User shortcode.
 */
 function getAuthorInfo( $atts) {
	 extract( shortcode_atts( array(
			'user' => 'something',
		), $atts ) );
		
		$user = get_userdatabylogin($user);
		$user_last = $user->last_name;
		$user_first = $user->first_name;
		$user_description = $user->description;
		$user_email = $user->user_email;
		$user_avatar = get_avatar($user_email);
		
    return "user = {$user_first} {$user_last} description: {$user_description} avatar {$user_avatar}";
}

// register shortcode
add_shortcode('authorinfo', 'getAuthorInfo');

?>