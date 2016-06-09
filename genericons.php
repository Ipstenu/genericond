<?php
/*
Plugin Name: Genericon'd
Plugin URI: http://halfelf.org/
Description: Use the Genericon icon set within WordPress. Icons can be inserted using either HTML or a shortcode.
Version: 4.0.0
Author: Mika Epstein (Ipstenu)
Author URI: http://ipstenu.org/
Author Email: ipstenu@halfelf.org

	Credits: Forked plugin code from Rachel Baker's Font Awesome for WordPress plugin - https://github.com/rachelbaker/Font-Awesome-WordPress-Plugin

	License: MIT

	Copyright (C) 2013 - 2016 Mika Epstein (ipstenu@halfelf.org)

    This file is part of Genericon'd, a plugin for WordPress.

    The Genericon'd Plugin is free software: you can redistribute it and/or
    modify it under the terms of the GNU General Public License as published
    by the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    Genericons itself is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by the
    Free Software Foundation; either version 2 of the License, or (at your option)
    any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.


*/

class GenericonsHELF {

	// Holds option data.
    var $option_name = 'genericons_options';
    var $options = array();
    var $option_defaults;
    static $gen_ver = '4.0.0'; // Plugin version so I can be lazy
    
    public function __construct() {
        add_action( 'init', array( &$this, 'init' ) );
        
        add_action( 'admin_init', array( &$this, 'admin_init' ) );
        add_action( 'admin_menu', array( &$this, 'add_settings_menu'));

        	// Setting plugin defaults here:
		$this->option_defaults = array(
			'genericons_fonts'		=> 'no',
			'social_logos_fonts' 	=> 'no',
	    );
    }

	/**
	 * Admin init Callback
	 *
	 * @since 4.0
	 */
    function admin_init() {
	    $this->options = wp_parse_args( get_option( 'genericons_options' ), $this->option_defaults );

		// Add link to settings from plugins listing page
		add_filter( 'plugin_action_links', array( &$this, 'add_settings_link'), 10, 2 );
		// Add donate link to plugin meta
        add_filter('plugin_row_meta', array( &$this, 'plugin_links'), 10, 2);
        
        // Enqueue scripts for admins
        add_action( 'admin_enqueue_scripts', array( &$this, 'register_plugin_styles' ) );
		// Enqueue styles for admins
        add_action( 'admin_enqueue_scripts', array( &$this, 'register_admin_styles' ) );

	    // Register Settings
		$this->register_settings();
	}

	/**
	 *  Init Callback (front end)
	 *
	 * @since 1.0
	 */
    public function init() {
        add_action( 'wp_enqueue_scripts', array( &$this, 'register_plugin_styles' ) );
        add_shortcode( 'genericon', array( &$this, 'setup_shortcode' ) );
        add_filter( 'widget_text', 'do_shortcode' );
    }

    public function register_plugin_styles() {
	    
	    $this->options = get_option( 'genericons_options' );
	    
		wp_enqueue_style( 'genericond', plugins_url( 'css/genericond.css', __FILE__ , '', self::$gen_ver ) );
		wp_enqueue_script( 'genericons-svg4everybody', plugins_url( 'genericons/svg-php/svg4everybody.js', __FILE__ ), array(), self::$gen_ver, false );
		
		if ( $this->options['genericons_fonts'] == 'yes' ) {
			wp_register_style('genericons', plugins_url('genericons/icon-font/Genricons.css', __FILE__, false, '', self::$gen_ver) );
			wp_enqueue_style('genericons');
		}

		if ( $this->options['social_logos_fonts'] == 'yes' ) {
			wp_register_style('social-logos', plugins_url('social-logos/icon-font/social-logos.css', __FILE__, false, '', self::$gen_ver) );
			wp_enqueue_style('social-logos');
		}
		
    }

    function register_admin_styles() {
    		wp_register_style( 'genericondExampleStyles', plugins_url( 'css/example.css', __FILE__ , '', self::$gen_ver ) );
		//wp_register_script( 'genericondExampleJS', plugins_url( 'js/example.js', __FILE__ , array( 'jquery' ), self::$gen_ver ) );
    }

    function add_admin_styles() {
		wp_enqueue_style( 'genericondExampleStyles' );
		//wp_enqueue_script( 'genericondExampleJS' );
    }

	/**
	 * Shortcode
	 * 
	 * @since 1.0
	 */
    public function setup_shortcode( $params ) {
        $attributes = shortcode_atts( array(
                    'icon'   => '',
                    'size'   => '',
                    'color'  => '',
                    'rotate' => '',
                    'repeat' => '1',
                    'title'  => ''
                ), $params );

		// Folders where images are stored
		$genericon_folder = plugin_dir_path(__FILE__).'/genericons/svg/';
		$socialogo_folder = plugin_dir_path(__FILE__).'/social-logos/svg/';

		// Check for files to make sure they exist and where.
	    if ( file_exists( $genericon_folder.$attributes['icon'].'.svg' ) ) {
			$icon_type = 'genericons';
	    } elseif ( file_exists( $socialogo_folder.$attributes['icon'].'.svg' )) {
		    $icon_type = 'social-logos';
	    } else {
		    $attributes['icon'] = 'stop';
		    $icon_type = 'genericons';
	    }

        // Resizing
        $icon_size = 'genericond-'.$icon_type.'-';
        if ( !empty($attributes['size']) && isset($attributes['size']) && in_array($attributes['size'], array('2x', '3x', '4x', '5x', '6x')) ) {
            $icon_size .= $attributes['size'];
        }
        else {
            $icon_size .= "1x";
        }

        // Color
        $icon_color = "fill:";
        if ( isset($attributes['color']) && !empty($attributes['color']) ) {
            $icon_color .= $attributes['color'];
        }
        else {
            $icon_color .= 'inherit';
        }
        $icon_color .= ";";

        // Rotate
        if ( !empty($attributes['rotate']) && isset($attributes['rotate']) && in_array($attributes['rotate'], array('90', '180', '270')) ) {
            $icon_rotate = 'genericond-rotate-'.$attributes['rotate'];
        } elseif ( !empty($attributes['rotate']) && isset($attributes['rotate']) && in_array($attributes['rotate'], array('flip-horizontal', 'flip-vertical')) )  {
	        $icon_rotate = 'genericond-'.$attributes['rotate'];
        } else {
            $icon_rotate = 'genericond-rotate-normal';
        }
        
        // Title
        if ( isset($attributes['title']) && !empty($attributes['title']) ) {
            $icon_title = $attributes['title'];
        } else {
            $icon_title = $attributes['icon'];
        }

        // Build the Genericon!
        $icon_styles = $icon_color; // In case I add more later? Hope I never have to, but...
        
        $icon = $this->get_genericond( $attributes['icon'], '', true, $icon_title, $icon_size, $icon_styles, $icon_rotate, $attributes['repeat'], $icon_type); 

		return $icon;
    }

	/**
	 * This allows us to get the SVG code and return as a variable
	 * Usage: get_genericond( 'name-of-icon' );
	 * 
	 * @since 4.0
	 */
    public function get_genericond( $name, $id = null, $external = true, $title = null, $size, $styles, $rotate, $repeat, $type ) {		
		$genericons_inject_sprite = null;
		$output = null;
	
		// Generate an attribute string for the SVG.
		$attr = 'class="genericon genericond-'. $type .' '. $type .'-'. $name .' '. $size .' '. $rotate .'"';
		$attr .= ' style="' . $styles . '"';
	
		// If the user has passed a unique ID, output it.
		if ( $id ) :
			$attr .= ' id="' . $id . '"';
		endif;
	
		if ( ! $title ) : // Use the icon name as the title if the user hasn't set one.
			$title = $name;
		endif;
	
		if ( 'none' === $title ) : // Specify the icon is presentational.
			$attr .= ' role="presentation"';
	
		else : // Output a title and role for screen readers.
			$attr .= ' title="' . $title . '"';
			$attr .= ' role="img" aria-labelledby="title"';
		endif;
	
		// Print the SVG tag.
		$return = '<svg ' . $attr . '>';
	
		if ( $external ) : // Default behavior; caches better.
		
			$return .= '<use xlink:href="' . esc_url( plugin_dir_url( __FILE__ ) ) .$type.'/svg-sprite/'.$type.'.svg#' . $name . '" />';
	
		else : // Use internal method if specified.
			$return .= '<use xlink:href="#' . $name . '" />';
			$genericons_inject_sprite = true;
		endif;

		$return .= '</svg>';

        // Repeat the icon if needed
        for ($i = 1 ; $i <= $repeat ; ++$i) {
	        $output .= $return;
	    }

	 return $output;
	}

	/**
	 * Admin Menu Callback
	 *
	 * @since 1.0
	 */
	public function add_settings_menu() {
        $page = add_theme_page(__('Genericon\'d', 'genericond'), __('Genericon\'d', 'genericond'), 'edit_posts', 'genericons', array($this, 'settings_page'));
		add_action( 'admin_print_styles-' . $page, array( &$this, 'add_admin_styles') );
    	}

	/**
	 * Register Admin Settings
	 *
	 * @since 4.0
	 */
    function register_settings() {
	    register_setting( 'genericons_legacy_fonts', 'genericons_options', array( $this, 'genericons_sanitize' ) );

		// The main section
		add_settings_section( 'genericons-fonts', 'Enable Legacy Fonts', array( &$this, 'legacy_fonts_callback'), 'genericons-settings' );

		// The Fields
		add_settings_field( 'lf-genericons', 'Genericons', array( &$this, 'lf_genericons_callback'), 'genericons-settings', 'genericons-fonts' );
		add_settings_field( 'lf-social-logos', 'Social Logos', array( &$this, 'lf_social_logos_callback'), 'genericons-settings', 'genericons-fonts' );
		
	}

	/**
	 * Legacy Fonts Callback
	 *
	 * @since 4.0
	 */
	function legacy_fonts_callback() {
	    ?><p><?php _e('Enabling legacy font-icons will ensure your old code keeps working, however it will add more weight to your site and slow it down. It is not recommended to keep this enabled.', 'genericons'); ?></p><?php
	}

	/**
	 * Legacy Fonts: Genericons Callback
	 *
	 * @since 4.0
	 */
	function lf_genericons_callback() {
		?>
		<input type="checkbox" id="genericons_options[genericons_fonts]" name="genericons_options[genericons_fonts]" value="yes" <?php echo checked( $this->options['genericons_fonts'], 'yes', true ); ?> >
		<?php
	}

	/**
	 * Legacy Fonts: Social Logos Callback
	 *
	 * @since 4.0
	 */
	function lf_social_logos_callback() {
		?>
		<input type="checkbox" id="genericons_options[social_logos_fonts]" name="genericons_options[social_logos_fonts]" value="yes" <?php echo checked( $this->options['social_logos_fonts'], 'yes', true ); ?> >
		<?php
	}	

	/**
	 * Options sanitization and validation
	 *
	 * @param $input the input to be sanitized
	 * @since 2.0
	 */
	function genericons_sanitize( $input ) {
	    	$options = $this->options;
	
	    	foreach ($options as $key=>$value) {
	            if ( !isset($input[$key]) || is_null( $input[$key] ) || $input[$key] == '0' ) {
		            $output[$key] = 'no';
	            } else {
		            $output[$key] = sanitize_text_field($input[$key]);
	            }
	        }
	
		return $output;
	}

	/**
	 * Settings Page
	 *
	 * @since 1.0
	 */
 	function settings_page() {
		?>
		<div class="wrap">

	        <h1><?php printf( __( 'Genericon\'d %s Usage', 'genericond' ), self::$gen_ver); ?></h1>
	        
	        <?php settings_errors(); ?>
	    		
	    		<p><?php _e( 'As of version 4.0, Genericon\'d has combined two separate icon libraries, as the original Genericons removed support for social media. In order to adversely impact users as little as possible, the library for Social Icons was added. This should have no impact on displaying icons in shortcodes, however any usage of the old div/i/span method to show icons will no longer work as the font is no longer called by default.', 'genericond' ); ?></p>

	    		<div id="content">
	    			<div id="glyph">
					<form method="post" action="options.php">
					<?php
			            settings_fields( 'genericons_legacy_fonts' );
			            do_settings_sections( 'genericons-settings' );
			            submit_button();
					?>
					</form>
	    			</div>
				
				<div class="description">
					<h2>Usage Example</h2>
		            <p><?php _e( 'Genericons can be displayed via shortcodes.', 'genericond' ); ?></p>
		            
		            <p><code>&#091;genericon icon=twitter&#093;</code></p>
	
		            <p><?php _e( 'Color Change:', 'genericond' ); ?> <code>&#091;genericon icon=twitter color=#4099FF&#093;</code></p>
		            <p><?php _e( 'Increase size:', 'genericond' ); ?> <code>&#091;genericon icon=facebook size=4x&#093;</code></p>
		            <p><?php _e( 'Repeat icon:', 'genericond' ); ?> <code>&#091;genericon icon=star repeat=3&#093;</code></p>
		            <p><?php _e( 'Flip icon:', 'genericond' ); ?> <code>&#091;genericon icon=twitter rotate=flip-horizontal&#093;</code></p>
				</div>
	    		</div>

			<div class="clear"></div>
	    		
			<h3><?php _e( 'Genericons', 'genericond' ); ?></h3>

			<p><?php _e( 'The following are all the included Genericon icons, with the name listed below.', 'genericond' ); ?></p>

			<div id="icons"><div id="iconlist">
				<?php
				
				$imagepath = plugin_dir_path(__FILE__).'/genericons/svg-min/';
				foreach( glob( $imagepath.'*' ) as $filename ){
					$name  = str_replace( $imagepath, '' , $filename );
					$name  = str_replace( '.svg', '', $name );	
					$image = $this->get_genericond( $name, '', true, $name, '1x', '', '', '1', 'genericons');
					echo '<span role="img" class="genericond-icon">' . $image . $name .'</span>';
				}
				?>
			</div></div>
			
			<hr>

			<h3><?php _e( 'Social Logos', 'genericond' ); ?></h3>

			<p><?php _e( 'The following are all the included Social Logo icons, with the name listed below.', 'genericond' ); ?></p>

			<div id="icons"><div id="iconlist">
				<?php
				
				$imagepath = plugin_dir_path(__FILE__).'/social-logos/svg-min/';
				foreach( glob( $imagepath.'*' ) as $filename ){
					$name  = str_replace( $imagepath, '' , $filename );
					$name  = str_replace( '.svg', '', $name );
					$image = $this->get_genericond( $name, '', true, $name, '1x', '', '', '1', 'social-logos');
					echo '<span role="img" class="genericond-icon">' . $image . $name .'</span>';
				}
				?>
			</div></div>
        </div>
        <div class="clear"></div>
        <?php
	}

	/**
	 * Add donate links on plugin listing
	 *
	 * @since 1.0
	 */
    public function plugin_links($links, $file) {
        if ($file == plugin_basename(__FILE__)) {
                $donate_link = '<a href="https://store.halfelf.org/donate/">Donate</a>';
                $links[] = $donate_link;
        }
        return $links;
    }

	/**
	 * Add settings link on plugin
	 *
	 * @since 4.0
	 */
	function add_settings_link( $links, $file ) {
		if ( plugin_basename( __FILE__ ) == $file ) {
			$settings_link = '<a href="' . admin_url( 'themes.php?page=genericons' ) .'">' . __( 'Settings', 'genericond' ) . '</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

}

new GenericonsHELF();