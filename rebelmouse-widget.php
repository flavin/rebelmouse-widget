<?php
/*
 * Plugin Name: RebelMouse Widget
 * Plugin URI: http://www.rebelmouse.com
 * Description: A widget to add your rebelmouse stream
 * Version: 1.0
 * Author: Francisco Lavin
 * Author URI: http://www.rebelmouse.com/flavin
 *License: GPLv2 or later
 */

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Register the Widget
 */
add_action( 'widgets_init', create_function( '', 'register_widget("rebelmouse_widget");' ) ); 

/**
 * Create the widget class and extend from the WP_Widget
 */
 class rebelmouse_widget extends WP_Widget {
 	
	/**
	 * Set the widget defaults
	 */
	private $widget_title = "RebelMouse Widget";
	private $site_name = "rebelmouse";
	private $cols = 1;

	private $width = "257";
	private $height = "100%";

	private $skip = array();
	private $show_about = "true";
	private	$show_following = "true";
	private	$show_also_in_rm = "true";
	private	$show_share = "true";
 	
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		
		parent::__construct(
			'rebelmouse_widget',// Base ID
			'RebelMouse ',// Name
			array(
				'classname'		=>	'rebelmouse_widget',
				'description'	=>	__('A widget to add your rebelmouse stream.', 'framework')
			)
		);

		// Load JavaScript and stylesheets
		$this->register_scripts_and_styles();

	} // end constructor
	
	/**
	 * Registers and enqueues stylesheets for the administration panel and the
	 * public facing site.
	 */
	public function register_scripts_and_styles() {
		
		

	} // end register_scripts_and_styles
	

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

		/* Our variables from the widget settings. */
		$this->widget_title = apply_filters('widget_title', $instance['title'] );
		
		$this->site_name = $instance['site_name'];
		$this->cols = $instance['cols'];
		$this->skip = $instance['skip'];
		$this->width *= $this->cols;

        $params = 'embedded=1';
        $params .= '&cols=' . $this->cols;

        $skip = ($instance['show_about'] == "1" ?  "" : "about-site,");
        $skip .= ($instance['show_following'] == "1" ? "" : "network," );
        $skip .= ($instance['show_also_in_rm'] == "1" ? "" : "also-on-rm," );
        $skip .= ($instance['show_share'] == "1" ? "" : "share-frontpage" );

        if ( !empty( $skip ) )
            $params .= '&skip=' . $skip;
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $this->widget_title )
			echo $before_title . $this->widget_title . $after_title;

		/* RebelMouse Embed*/
        
		 ?>
            <style>.rebelmouse-embed { overflow-y:hidden;-ms-overflow-y: hidden;padding:0;magin:0;min-height:1500px; }iframe::-webkit-scrollbar { display: none; } </style>
            <iframe class="rebelmouse-embed" allowtransparency="true" frameborder="0" 
                    height="<?php echo $this->height; ?>px" width="<?php echo $this->width; ?>px"  
                    src="http://www.rebelmouse.com/<?php echo $this->site_name ?>/?<?php echo $params; ?>"></iframe>
		<?php 

		/* After widget (defined by themes). */
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
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['site_name'] = strip_tags( $new_instance['site_name'] );
		$instance['cols'] = strip_tags( $new_instance['cols'] );

		//$instance['width'] = strip_tags( $new_instance['width'] );
		$instance['height'] = strip_tags( $new_instance['height'] );
		
		$instance['show_about'] = (bool)$new_instance['show_about'];
		$instance['show_following'] = (bool)$new_instance['show_following'];
		$instance['show_also_in_rm'] = (bool)$new_instance['show_also_in_rm'];
		$instance['show_share'] = (bool)$new_instance['show_share'];

		return $instance;
	}
	
	/**
	 * Create the form for the Widget admin
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */	 
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
		'title' => $this->widget_title,
		'site_name' => $this->site_name,
		'cols' => $this->cols,
		'skip' => $this->skip,

		'width' => $this->width,
		'show_about' => $this->show_about,
		'show_following' => $this->show_following,
		'show_also_in_rm' => $this->show_also_in_rm,
		'show_share' => $this->show_share,
        'border_color' => $this->border_color
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>


			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'framework') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<!-- Page name: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'site_name' ); ?>"><?php _e('Site name (http://www.rebelmouse.com/[site_name])', 'framework') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'site_name' ); ?>" name="<?php echo $this->get_field_name( 'site_name' ); ?>" value="<?php echo $instance['site_name']; ?>" />
		</p>

		<!-- Cols: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'cols' ); ?>"><?php _e('Columns', 'framework') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'cols' ); ?>" name="<?php echo $this->get_field_name( 'cols' ); ?>" value="<?php echo $instance['cols']; ?>" />
		</p>
		
		<!-- Height: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e('Height', 'framework') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" />
		</p>
		
        <?php /*
		<!-- Width: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e('Width', 'framework') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" value="<?php echo $instance['width']; ?>" />
		</p>
         */?>
		
		<!-- Show About: Checkbox -->
		<p>
			<label for="<?php echo $this->get_field_id( 'show_about' ); ?>"><?php _e('Show About', 'framework') ?></label>
			<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_about' ); ?>" name="<?php echo $this->get_field_name( 'show_about' ); ?>" value="1" <?php echo ($instance['show_about'] == "true" ? "checked='checked'" : ""); ?> />
		</p>
		
		<!-- Show Following: Checkbox -->
		<p>
			<label for="<?php echo $this->get_field_id( 'show_following' ); ?>"><?php _e('Show Following', 'framework') ?></label>
			<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_following' ); ?>" name="<?php echo $this->get_field_name( 'show_following' ); ?>" value="1" <?php echo ($instance['show_following'] == "true" ? "checked='checked'" : ""); ?> />
		</p>
		
		<!-- Show Also in RebelMouse: Checkbox -->
		<p>
			<label for="<?php echo $this->get_field_id( 'show_also_in_rm' ); ?>"><?php _e('Show Also In RebelMouse', 'framework') ?></label>
			<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_also_in_rm' ); ?>" name="<?php echo $this->get_field_name( 'show_also_in_rm' ); ?>" value="1" <?php echo ($instance['show_also_in_rm'] == "true" ? "checked='checked'" : ""); ?> />
		</p>
		
		<!-- Show Share Site: Checkbox -->
		<p>
			<label for="<?php echo $this->get_field_id( 'show_share' ); ?>"><?php _e('Show Share This Site', 'framework') ?></label>
			<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'show_share' ); ?>" name="<?php echo $this->get_field_name( 'show_share' ); ?>" value="1" <?php echo ($instance['show_share'] == "true" ? "checked='checked'" : ""); ?> />
		</p>
		
	<?php
	}
 }
?>
