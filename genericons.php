<?php
/*
Plugin Name: Genericon'd
Plugin URI: http://halfelf.org/
Description: Use the Genericon icon set within WordPress. Icons can be inserted using either HTML or a shortcode.
Version: 3.0.1
Author: Mika Epstein
Author URI: http://ipstenu.org/
Author Email: ipstenu@ipstenu.org
Credits:
     Forked plugin code from Rachel Baker's Font Awesome for WordPress plugin
     https://github.com/rachelbaker/Font-Awesome-WordPress-Plugin

License:

  Copyright (C) 2013  Mika Epstein.

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
        add_filter('plugin_row_meta', array( $this, 'donate_link'), 10, 2);
    }

    public function register_plugin_styles() {
        global $wp_styles;
        if ( wp_style_is('genericons', 'registered') == TRUE) {
            wp_dequeue_style( 'genericons' );
            wp_deregister_style('genericons');
        }
        wp_enqueue_style( 'genericons', plugins_url( 'genericons/genericons.css', __FILE__ , '', '3.0.1'  ) );
        wp_enqueue_style( 'genericond', plugins_url( 'css/genericond.css', __FILE__ , '', '3.0.1' ) );
    }

    function register_admin_styles() {
    	wp_register_style( 'genericondExampleStyles', plugins_url( 'css/example.css', __FILE__ , '', '3.0.1' ) );
    	wp_register_script( 'genericondExampleJS', plugins_url( 'js/example.js', __FILE__ , array( 'jquery' ), '3.0.1' ) );
    }

    function add_admin_styles() {
         wp_enqueue_style( 'genericondExampleStyles' );
         wp_enqueue_script( 'genericondExampleJS' );
    }

    // The Shortcode
    public function setup_shortcode( $params ) {
        $genericonatts = shortcode_atts( array(
                    'icon'   => '',
                    'size'   => '',
                    'color'  => '',
                    'rotate' => '',
                    'repeat' => '1'
                ), $params );
        
        // Resizing
        $genericon_size = "genericon-";
        if ( !empty($genericonatts['size']) && isset($genericonatts['size']) && in_array($genericonatts['size'], array('2x', '3x', '4x', '5x', '6x')) ) { 
            $genericon_size .= $genericonatts['size']; 
        }
        else { 
            $genericon_size .= "1x"; 
        }
        
        // Color
        $genericon_color = "color:";
        if ( isset($genericonatts['color']) && !empty($genericonatts['color']) ) {
            $genericon_color .= $genericonatts['color'];
        }
        else {
            $genericon_color .= 'inherit';
        }
        $genericon_color .= ";";

        // Rotate
        if ( !empty($genericonatts['rotate']) && isset($genericonatts['rotate']) && in_array($genericonatts['rotate'], array('90', '180', '270', 'flip-horizontal', 'flip-vertical')) ) { 
            $genericon_rotate = 'genericon-rotate-'.$genericonatts['rotate']; 
        }
         
        // Build the Genericon!
        $genericon_styles = $genericon_color; // In case I add more later? Hope I never have to, but...

        $genericon_code = '<i style="'.$genericon_styles.'" class="genericond genericon genericon-'.$genericonatts['icon'].' '.$genericon_size.' '.$genericon_rotate.'"></i>';
        $genericon = $genericon_code;
        
        // Repeat the genericon
        for ($i = 2 ; $i <= $genericonatts['repeat']; ++$i) {
	        $genericon .= $genericon_code;
	    }

        return $genericon;
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

        <h2>Genericon'd 3.0.1 Settings</h2>
        
        <p>There are no settings for Genericon'd. This page is for documentation only. <a href="http://genericons.com">Genericons</a> are vector icons embedded in a webfont designed to be clean and simple keeping with a generic aesthetic. You can use genericons for instant HiDPI, to change icon colors on the fly, or even with CSS effects such as drop-shadows or gradients. They are provided here as a quick way to include them on your site, regardless of theme.</p>
    	<div id="primary">
    		<div id="content">
    			<div id="glyph">
    			</div>
    
    			<div class="description">
                    <p>Genericons can be displayed via one of the following methods:
                    <br /><code>&#091;genericon icon=twitter&#093;</code>
                    <br />
                    <br /><code>&lt;i alt="f202" class="genericond genericon genericon-twitter"&gt;&lt;/i&gt;</code></p>
                    
                    <p>You can also use <code>div</code> or <code>span</code> instead of <code>i</code></p>
                    
                    <p>On the fly color changing means you can make a Twitter Blue icon: <code>&#091;genericon icon=twitter color=#4099FF&#093;</code></p>
                    
                    <p>On the fly resize lets you make a Facebook icon bigger: <code>&#091;genericon icon=facebook size=4x&#093;</code></p>
            
                    <p>Want to repeat a Genericon multiple times? Like a star? <code>&#091;genericon icon=star repeat=3&#093;</code></p>
                    
                    <p>Want to flip it around? <code>&#091;genericon icon=twitter rotate=flip-horizontal&#093;</code></p>
    			</div>
    
    		</div>
    	</div>
                       
		<div id="icons">
			<div id="iconlist">

			<!-- post formats -->
			<div alt="f100" class="genericon genericon-standard"></div>
			<div alt="f101" class="genericon genericon-aside"></div>
			<div alt="f102" class="genericon genericon-image"></div>
			<div alt="f103" class="genericon genericon-gallery"></div>
			<div alt="f104" class="genericon genericon-video"></div>
			<div alt="f105" class="genericon genericon-status"></div>
			<div alt="f106" class="genericon genericon-quote"></div>
			<div alt="f107" class="genericon genericon-link"></div>
			<div alt="f108" class="genericon genericon-chat"></div>
			<div alt="f109" class="genericon genericon-audio"></div>

			<!-- social icons -->
			<div alt="f200" class="genericon genericon-github"></div>
			<div alt="f201" class="genericon genericon-dribbble"></div>
			<div alt="f202" class="genericon genericon-twitter"></div>
			<div alt="f203" class="genericon genericon-facebook"></div>
			<div alt="f204" class="genericon genericon-facebook-alt"></div>
			<div alt="f205" class="genericon genericon-wordpress"></div>
			<div alt="f206" class="genericon genericon-googleplus"></div>
			<div alt="f207" class="genericon genericon-linkedin"></div>
			<div alt="f208" class="genericon genericon-linkedin-alt"></div>
			<div alt="f209" class="genericon genericon-pinterest"></div>
			<div alt="f210" class="genericon genericon-pinterest-alt"></div>
			<div alt="f211" class="genericon genericon-flickr"></div>
			<div alt="f212" class="genericon genericon-vimeo"></div>
			<div alt="f213" class="genericon genericon-youtube"></div>
			<div alt="f214" class="genericon genericon-tumblr"></div>
			<div alt="f215" class="genericon genericon-instagram"></div>
			<div alt="f216" class="genericon genericon-codepen"></div>
			<span class="new"><div alt="f217" class="genericon genericon-polldaddy"></div></span>
			<span class="new"><div alt="f218" class="genericon genericon-googleplus-alt"></div></span>
			<span class="new"><div alt="f219" class="genericon genericon-path"></div></span>

			<!-- meta icons -->
			<div alt="f300" class="genericon genericon-comment"></div>
			<div alt="f301" class="genericon genericon-category"></div>
			<div alt="f302" class="genericon genericon-tag"></div>
			<div alt="f303" class="genericon genericon-time"></div>
			<div alt="f304" class="genericon genericon-user"></div>
			<div alt="f305" class="genericon genericon-day"></div>
			<div alt="f306" class="genericon genericon-week"></div>
			<div alt="f307" class="genericon genericon-month"></div>
			<div alt="f308" class="genericon genericon-pinned"></div>

			<!-- other icons -->
			<div alt="f400" class="genericon genericon-search"></div>
			<div alt="f401" class="genericon genericon-unzoom"></div>
			<div alt="f402" class="genericon genericon-zoom"></div>
			<div alt="f403" class="genericon genericon-show"></div>
			<div alt="f404" class="genericon genericon-hide"></div>
			<div alt="f405" class="genericon genericon-close"></div>
			<div alt="f406" class="genericon genericon-close-alt"></div>
			<div alt="f407" class="genericon genericon-trash"></div>
			<div alt="f408" class="genericon genericon-star"></div>
			<div alt="f409" class="genericon genericon-home"></div>
			<div alt="f410" class="genericon genericon-mail"></div>
			<div alt="f411" class="genericon genericon-edit"></div>
			<div alt="f412" class="genericon genericon-reply"></div>
			<span class="update"><div alt="f413" class="genericon genericon-feed"></div></span>
			<div alt="f414" class="genericon genericon-warning"></div>
			<div alt="f415" class="genericon genericon-share"></div>
			<div alt="f416" class="genericon genericon-attachment"></div>
			<div alt="f417" class="genericon genericon-location"></div>
			<div alt="f418" class="genericon genericon-checkmark"></div>
			<div alt="f419" class="genericon genericon-menu"></div>
			<span class="new"><div alt="f420" class="genericon genericon-refresh"></div></span>
			<div alt="f421" class="genericon genericon-minimize"></div>
			<div alt="f422" class="genericon genericon-maximize"></div>
						<div alt="f424" class="genericon genericon-spam"></div>
			<div alt="f425" class="genericon genericon-summary"></div>
			<div alt="f426" class="genericon genericon-cloud"></div>
			<div alt="f427" class="genericon genericon-key"></div>
			<div alt="f428" class="genericon genericon-dot"></div>
			<div alt="f429" class="genericon genericon-next"></div>
			<div alt="f430" class="genericon genericon-previous"></div>
			<div alt="f431" class="genericon genericon-expand"></div>
			<div alt="f432" class="genericon genericon-collapse"></div>
			<div alt="f433" class="genericon genericon-dropdown"></div>
			<div alt="f434" class="genericon genericon-dropdown-left"></div>
			<div alt="f435" class="genericon genericon-top"></div>
			<div alt="f436" class="genericon genericon-draggable"></div>
			<div alt="f437" class="genericon genericon-phone"></div>
			<div alt="f438" class="genericon genericon-send-to-phone"></div>
			<div alt="f439" class="genericon genericon-plugin"></div>
			<div alt="f440" class="genericon genericon-cloud-download"></div>
			<div alt="f441" class="genericon genericon-cloud-upload"></div>
			<div alt="f442" class="genericon genericon-external"></div>
			<div alt="f443" class="genericon genericon-document"></div>
			<div alt="f444" class="genericon genericon-book"></div>
			<div alt="f445" class="genericon genericon-cog"></div>
			<div alt="f446" class="genericon genericon-unapprove"></div>
			<div alt="f447" class="genericon genericon-cart"></div>
			<div alt="f448" class="genericon genericon-pause"></div>
			<div alt="f449" class="genericon genericon-stop"></div>
			<div alt="f450" class="genericon genericon-skip-back"></div>
			<div alt="f451" class="genericon genericon-skip-ahead"></div>
			<div alt="f452" class="genericon genericon-play"></div>
			<div alt="f453" class="genericon genericon-tablet"></div>
			<div alt="f454" class="genericon genericon-send-to-tablet"></div>
			<span class="new"><div alt="f455" class="genericon genericon-info"></div></span>
			<span class="new"><div alt="f456" class="genericon genericon-notice"></div></span>
			<span class="new"><div alt="f457" class="genericon genericon-help"></div></span>
			<span class="new"><div alt="f458" class="genericon genericon-fastforward"></div></span>
			<span class="new"><div alt="f459" class="genericon genericon-rewind"></div></span>
			<span class="new"><div alt="f460" class="genericon genericon-portfolio"></div></span>

			<!-- generic shapes -->
			<div alt="f500" class="genericon genericon-uparrow"></div>
			<div alt="f501" class="genericon genericon-rightarrow"></div>
			<div alt="f502" class="genericon genericon-downarrow"></div>
			<div alt="f503" class="genericon genericon-leftarrow"></div>

			</div>
		</div>
		
        <p>Need more examples? Check out <a href="<?php echo plugins_url( 'genericons/example.html', __FILE__); ?>">the official Genericons Examples</a></p>

        </div>
        <?php
	}

    // donate link on manage plugin page
    public function donate_link($links, $file) {
        if ($file == plugin_basename(__FILE__)) {
                $donate_link = '<a href="https://www.wepay.com/donations/halfelf-wp">Donate</a>';
                $links[] = $donate_link;
        }
        return $links;
    }

}

new GenericonsHELF();