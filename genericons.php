<?php
/*
Plugin Name: Genericon'd
Plugin URI: http://halfelf.org/
Description: Use the Genericon icon set within WordPress. Icons can be inserted using either HTML or a shortcode.
Version: 4.0.0
Author: Mika Epstein
Author URI: http://ipstenu.org/
Author Email: ipstenu@ipstenu.org

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

    static $gen_ver = '4.0.0'; // Plugin version so I can be lazy
    
    public function __construct() {
        add_action( 'init', array( &$this, 'init' ) );
    }

    public function init() {
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );

        add_shortcode( 'genericon', array( $this, 'setup_shortcode' ) );
        add_filter( 'widget_text', 'do_shortcode' );

        add_action( 'admin_menu', array( $this, 'add_settings_page'));
        add_filter('plugin_row_meta', array( $this, 'plugin_links'), 10, 2);
    }

    public function register_plugin_styles() {
		wp_enqueue_style( 'genericond', plugins_url( 'css/genericond.css', __FILE__ , '', self::$gen_ver ) );
		wp_enqueue_script( 'genericons-svg4everybody', plugins_url( '/genericons/svg-php/svg4everybody.js', __FILE__ ), array(), self::$gen_ver, false );
    }

    function register_admin_styles() {
		wp_register_style( 'genericondExampleStyles', plugins_url( 'css/example.css', __FILE__ , '', self::$gen_ver ) );
		wp_register_script( 'genericondExampleJS', plugins_url( 'js/example.js', __FILE__ , array( 'jquery' ), self::$gen_ver ) );
    }

    function add_admin_styles() {
         wp_enqueue_style( 'genericondExampleStyles' );
         wp_enqueue_script( 'genericondExampleJS' );
    }

    // The Shortcode
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
			$icon_type = 'genericon';
	    } elseif ( file_exists( $socialogo_folder.$attributes['icon'].'.svg' )) {
		    $icon_type = 'sociallogo';
	    } else {
		    $attributes['icon'] = 'stop';
		    $icon_type = 'genericon';
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
        
        if ( $icon_type == "genericon" ) {
			$icon = $this->get_genericond( $attributes['icon'], '', true, $icon_title, $icon_size, $icon_styles, $icon_rotate, $attributes['repeat']); 
		} 
		if ( $icon_type == "sociallogo" ) {
			$icon = $this->get_sociallogod( $attributes['icon'], '', true, $icon_title, $icon_size, $icon_styles, $icon_rotate, $attributes['repeat']);
		}

		return $icon;
    }

    public function get_genericond( $name, $id = null, $external = true, $title = null, $size, $styles, $rotate, $repeat ) {		
		$genericons_inject_sprite = null;
		$output = null;
	
		// Generate an attribute string for the SVG.
		$attr = 'class="genericon genericond-genericon genericon-' . $name .' '. $size .' '. $rotate .'"';
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
			$return .= '<use xlink:href="' . esc_url( plugin_dir_url( __FILE__ ) ) . '/genericons/svg-sprite/genericons.svg#' . $name . '" />';
	
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

    public function get_sociallogod( $name, $id = null, $external = true, $title = null, $size, $styles, $rotate, $repeat ) {		
		$genericons_inject_sprite = null;
		$output = null;
	
		// Generate an attribute string for the SVG.
		$attr = 'class="social-logo genericond-sociallogo social-logo-' . $name .' '. $size .' '. $rotate .'"';
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
			$return .= '<use xlink:href="' . esc_url( plugin_dir_url( __FILE__ ) ) . '/social-logos/svg-sprite/social-logos.svg#' . $name . '" />';
	
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

    // Sets up the settings page
	public function add_settings_page() {
        $page = add_theme_page(__('Genericon\'d'), __('Genericon\'d'), 'edit_posts', 'genericons', array($this, 'settings_page'));
        add_action( 'admin_print_styles-' . $page, array( $this, 'add_admin_styles') );
    	}

    // Content of the settings page
 	function settings_page() {
		?>
		<div class="wrap">

        <h2>Genericon'd <?php echo self::$gen_ver; ?> Usage</h2>

    	<div id="primary">
    		<div id="content">

			<p>COMING SOON!!!!</p>

		</div>

		<div id="temp" style="display: none;"></div>

        <p>Need more examples? Check out <a href="http://genericons.com">the official Genericons Examples</a> or <a href="http://wordpress.org/plugins/genericond/faq/">the Genericon'd FAQ</a>.</p>

        </div>
        <?php
	}

    // donate link on manage plugin page
    public function plugin_links($links, $file) {
        if ($file == plugin_basename(__FILE__)) {
                $donate_link = '<a href="https://store.halfelf.org/donate/">Donate</a>';
				$settings_link = '<a href="' . admin_url( 'themes.php?page=genericons' ) . '">' . __( 'Settings' ) . '</a>';
                $links[] = $settings_link.' | '.$donate_link;
        }
        return $links;
    }
}

new GenericonsHELF();

    function myplugin_activated_plugin_error() {
        update_option( 'myplugin_error',  ob_get_contents() );
    }
    function myplugin_deactivated_plugin_error() {
        delete_option( 'myplugin_error' );
    }
    add_action( 'activated_plugin', 'myplugin_activated_plugin_error' );
    add_action( 'deactivated_plugin', 'myplugin_deactivated_plugin_error' );
    
	function myplugin_message_plugin_error() {
	    ?>
	    <div class="notice notice-error">
	        <p><?php echo get_option( 'myplugin_error' ); ?></p>
	    </div>
	    <?php
		}
	if( get_option( 'myplugin_error' ) ) {
		add_action( 'admin_notices', 'myplugin_message_plugin_error' );	
	}