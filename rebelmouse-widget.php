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
 class Rebelmouse_Widget extends WP_Widget {
 	
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

	private	$show_button = "true";
	private	$theme_button = "clear";
 	
	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		
		parent::__construct(
			'Rebelmouse_Widget',// Base ID
			'RebelMouse ',// Name
			array(
				'classname'		=>	'Rebelmouse_Widget',
				'description'	=>	__('A widget to add your rebelmouse stream.', 'framework')
			)
		);

	} // end constructor
	
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
		$this->width *= $this->cols;

		$this->show_button = ($instance['show_button'] == "1" ?  True : False);
		$this->theme_button = $instance['theme_button'];

        $skip = ($instance['show_about'] == "1" ?  "" : "about-site,");
        $skip .= ($instance['show_following'] == "1" ? "" : "network," );
        $skip .= ($instance['show_also_in_rm'] == "1" ? "" : "also-on-rm," );
        $skip .= ($instance['show_share'] == "1" ? "" : "share-frontpage" );
		
		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $this->widget_title )
			echo $before_title . $this->widget_title . $after_title;

		/* RebelMouse Render*/
        $this->render( array('site_name' => $this->site_name, 'cols' => $this->cols
                            ,'skip' => $skip, 'width' => $this->width, 'height' => $this->height
                            ,'show_button' => $this->show_button, 'theme_button' => $this->theme_button ) );

		/* After widget (defined by themes). */
		echo $after_widget;
	}

    public function render( $args ) {
        $r = wp_parse_args( $args
                            , array('site_name' => 'rebelmouse'
                                    ,'cols' => '1'
                                    ,'skip' => ''
                                    ,'width'=> '0'
                                    ,'height' => '0'
                                    ,'show_button' => True
                                    ,'theme_button' => 'clear'
                                    ) );

        if ( empty( $height ) )
            $r['height'] = '100%';
        else
            $r['height'] .= 'px';

        if ( empty( $width ) )
            $r['width'] = '100%';
        else
            $r['width'] .= 'px';

        $params = 'embedded=1';

        $params .= '&cols=' . $r['cols'];

        if ( !empty( $r['skip'] ) )
            $params .= '&skip=' . $r['skip'];

        ?>

        <style>.rebelmouse-embed { overflow-y:hidden;-ms-overflow-y: hidden;padding:0;magin:0;min-height:1500px; }iframe::-webkit-scrollbar { display: none; } </style>
        <?php
        if  ( $r['show_button'] )
            if ( $r['theme_button'] == 'dark' )
                echo '<a href="http://www.rebelmouse.com/' . $r['site_name'] . '/" class="rebelmouse_follow"><img src="http://www.rebelmouse.com/static/img/resources/follow-me-drk-logo.png"></a>';
            else
                echo '<a href="http://www.rebelmouse.com/' . $r['site_name'] . '/" class="rebelmouse_follow"><img src="http://www.rebelmouse.com/static/img/resources/follow-me-logo.png"></a>';

        ?>
            <iframe class="rebelmouse-embed" allowtransparency="true" frameborder="0" 
                    height="<?php echo $r['height']; ?>" width="<?php echo $r['width']; ?>"  
                    src="http://www.rebelmouse.com/<?php echo $r['site_name'] ?>/?<?php echo $params; ?>"></iframe>
        <?php
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

		$instance['show_button'] = (bool)$new_instance['show_button'];
		$instance['theme_button'] = $new_instance['theme_button'];

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

		'width' => $this->width,
		'show_about' => $this->show_about,
		'show_following' => $this->show_following,
		'show_also_in_rm' => $this->show_also_in_rm,
		'show_share' => $this->show_share,
        'border_color' => $this->border_color
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <h4 style="border-bottom: solid 1px #CCC;">RebelMouse Embed</h4>

		<!-- Widget Title: Text Input -->
		<p>


			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title', 'framework') ?>: </label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<!-- Page name: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'site_name' ); ?>"><?php _e('Site name', 'framework') ?>: </label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'site_name' ); ?>" name="<?php echo $this->get_field_name( 'site_name' ); ?>" value="<?php echo $instance['site_name']; ?>" />
            <br><small>http://www.rebelmouse.com/[site_name]</small>
		</p>

		<!-- Cols: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'cols' ); ?>"><?php _e('Columns', 'framework') ?>: </label>
            <select id="<?php echo $this->get_field_id( 'cols' ); ?>" name="<?php echo $this->get_field_name( 'cols' ); ?>">
                <?php for ( $i=1; $i <=5; $i++ ): ?>
                <option value="<?echo $i; ?>"  <?php ($i == $instance['cols'])? 'seleted="selected"':'';  ?>><?echo $i; ?></option>
                <?php endfor; ?> 
            </select> 
            <br><small>(each column is 271 pixels wide)</small>
		</p>
		
		<!-- Height: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e('Height', 'framework') ?>: </label>
			<input type="text" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" value="<?php echo $instance['height']; ?>" size="5" />px
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
			<label for="<?php echo $this->get_field_id( 'show_about' ); ?>"><?php _e('Show About', 'framework') ?>: </label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_about' ); ?>" name="<?php echo $this->get_field_name( 'show_about' ); ?>" value="1" <?php echo ($instance['show_about'] == "true" ? "checked='checked'" : ""); ?> />
		</p>
		
		<!-- Show Following: Checkbox -->
		<p>
			<label for="<?php echo $this->get_field_id( 'show_following' ); ?>"><?php _e('Show Following', 'framework') ?>: </label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_following' ); ?>" name="<?php echo $this->get_field_name( 'show_following' ); ?>" value="1" <?php echo ($instance['show_following'] == "true" ? "checked='checked'" : ""); ?> />
		</p>
		
		<!-- Show Also in RebelMouse: Checkbox -->
		<p>
			<label for="<?php echo $this->get_field_id( 'show_also_in_rm' ); ?>"><?php _e('Show Also In RebelMouse', 'framework') ?>: </label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_also_in_rm' ); ?>" name="<?php echo $this->get_field_name( 'show_also_in_rm' ); ?>" value="1" <?php echo ($instance['show_also_in_rm'] == "true" ? "checked='checked'" : ""); ?> />
		</p>
		
		<!-- Show Share Site: Checkbox -->
		<p>
			<label for="<?php echo $this->get_field_id( 'show_share' ); ?>"><?php _e('Show Share This Site', 'framework') ?>: </label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_share' ); ?>" name="<?php echo $this->get_field_name( 'show_share' ); ?>" value="1" <?php echo ($instance['show_share'] == "true" ? "checked='checked'" : ""); ?> />
		</p>

        <h4 style="border-bottom: solid 1px #CCC;">Follow Button</h4>

		<!-- Show Follow button: Checkbox -->
		<p>
			<label for="<?php echo $this->get_field_id( 'show_button' ); ?>"><?php _e('Show Follow Button', 'framework') ?>: </label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'show_button' ); ?>" name="<?php echo $this->get_field_name( 'show_button' ); ?>" value="1" <?php echo ($instance['show_button'] == "true" ? "checked='checked'" : ""); ?> />
		</p>
		
		<!-- Theme: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'theme_button' ); ?>"><?php _e('Theme Follow Button', 'framework') ?>: </label>
            <select id="<?php echo $this->get_field_id( 'theme_button' ); ?>" name="<?php echo $this->get_field_name( 'theme_button' ); ?>">
                <option value="clear"  <?php ($instance['theme_button'] == 'clear' )? 'seleted="selected"':'';  ?>>Clear</option>
                <option value="dark"  <?php ($instance['theme_button'] == 'dark' )? 'seleted="selected"':'';  ?>>Dark</option>
            </select> 
		</p>

	<?php
	}
 }

function rebelmouse_embed( $args ) {
    $rm_widget = new Rebelmouse_Widget();
    $rm_widget->render( $args );
}

?>
