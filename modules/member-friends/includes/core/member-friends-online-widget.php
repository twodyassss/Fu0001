<?php
if( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class um_my_friends_online
 */
class um_my_friends_online extends WP_Widget {


	/**
	 * um_my_friends_online constructor.
	 */
	function __construct() {
		
		parent::__construct(
		
		// Base ID of your widget
		'um_my_friends_online',

		// Widget name will appear in UI
		__('Ultimate Member - Friends Online', 'twodayssss'),

		// Widget description
		array( 'description' => __( 'Shows user online friends in a widget.', 'twodayssss' ), )
		);
	
	}


	/**
	 * Creating widget front-end
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		wp_enqueue_script( 'um_friends' );
		wp_enqueue_style( 'um_friends' );

		$title = apply_filters( 'widget_title', $instance['title'] );
		$max = $instance['max'];
		
		if( !is_user_logged_in() ) {
			return;
		}

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		
		// This is where you run the code and display the output
		echo do_shortcode('[ultimatemember_friends_online style=avatars max='.$max.']');
		
		echo $args['after_widget'];
	}


	/**
	 * Widget Backend
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'Friends Online', 'twodayssss' );
		}
		
		if ( isset( $instance[ 'max' ] ) ) {
			$max = $instance[ 'max' ];
		} else {
			$max = 12;
		}
		
		// Widget admin form
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'max' ); ?>"><?php _e( 'Maximum number of users in first view:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'max' ); ?>" name="<?php echo $this->get_field_name( 'max' ); ?>" type="text" value="<?php echo esc_attr( $max ); ?>" />
		</p>
		
		<?php 
	}


	/**
	 * Updating widget replacing old instances with new
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['max'] = ( ! empty( $new_instance['max'] ) ) ? strip_tags( $new_instance['max'] ) : 12;
		return $instance;
	}
}