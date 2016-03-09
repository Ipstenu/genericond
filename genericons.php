<?php
/*
Plugin Name: Genericon'd
Plugin URI: http://halfelf.org/
Description: Use the Genericon icon set within WordPress. Icons can be inserted using either HTML or a shortcode.
Version: 3.4.1
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

    static $gen_ver = '3.4.1'; // Plugin version so I can be lazy
    
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
        global $wp_styles;
        if ( wp_style_is('genericons', 'registered') == TRUE) {
            wp_dequeue_style( 'genericons' ); // This is to force other plugins and themes with older versions to STFUN00B
            wp_deregister_style('genericons');
        }
        wp_enqueue_style( 'genericons', plugins_url( 'genericons/genericons/genericons.css', __FILE__ , '', self::$gen_ver ) );
        wp_enqueue_style( 'genericond', plugins_url( 'css/genericond.css', __FILE__ , '', self::$gen_ver ) );
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
        $genericonatts = shortcode_atts( array(
                    'icon'   => '',
                    'size'   => '',
                    'color'  => '',
                    'rotate' => '',
                    'repeat' => '1',
                    'title'  => ''
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
        if ( !empty($genericonatts['rotate']) && isset($genericonatts['rotate']) && in_array($genericonatts['rotate'], array('90', '180', '270')) ) {
            $genericon_rotate = 'genericon-rotate-'.$genericonatts['rotate'];
        } elseif ( !empty($genericonatts['rotate']) && isset($genericonatts['rotate']) && in_array($genericonatts['rotate'], array('flip-horizontal', 'flip-vertical')) )  {
	        $genericon_rotate = 'genericon-'.$genericonatts['rotate'];
        } else {
            $genericon_rotate = 'genericon-rotate-normal';
        }
        
        // Title
        if ( isset($genericonatts['title']) && !empty($genericonatts['title']) ) {
            $genericon_title = $genericonatts['title'];
        }
        else {
            $genericon_title = $genericonatts['icon'];
        }


        // Build the Genericon!
        $genericon_styles = $genericon_color; // In case I add more later? Hope I never have to, but...
        $genericon_code = '<i title="'.$genericon_title.'" style="'.$genericon_styles.'" class="genericond genericon genericon-'.$genericonatts['icon'].' '.$genericon_size.' '.$genericon_rotate.'"></i>';
        $genericon = $genericon_code;

        // Repeat the genericon if needed
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

        <h2>Genericon'd <?php echo self::$gen_ver; ?> Usage</h2>

    	<div id="primary">
    		<div id="content">
    			<div id="glyph">
    			</div>

    			<div class="description">
    			    <p><a href="http://genericons.com">Genericons</a> are vector icons embedded in a webfont designed to be clean and simple keeping with a generic aesthetic. You can use them for instant HiDPI, to change icon colors on the fly, or with CSS effects such as drop-shadows or gradients.</p>
    			    
                    <p>Genericons can be displayed via one of the following methods:
                    <br />Shortcodes: <code>&#091;genericon icon=twitter&#093;</code>
                    <br />HTML:<code>&lt;i alt="f202" class="genericond genericon genericon-twitter"&gt;&lt;/i&gt;</code></p>
                    <p><strong>Shortcode Examples</strong>:
                    <br />Color Change: <code>&#091;genericon icon=twitter color=#4099FF&#093;</code>
                    <br />Increase size: <code>&#091;genericon icon=facebook size=4x&#093;</code>
                    <br />Repeat icon: <code>&#091;genericon icon=star repeat=3&#093;</code>
                    <br />Flip icon: <code>&#091;genericon icon=twitter rotate=flip-horizontal&#093;</code></p>
    			</div>

    		</div>
    	</div>

		<div id="icons">
			<div id="iconlist">

			<!-- note, the text inside the HTML elements is purely for the seach -->
			
			<div alt="f423" class="genericon genericon-404" title="genericon-404">404</div>
			
			<div alt="f508" class="genericon genericon-activity" title="genericon-activity">activity</div>
			
			<div alt="f509" class="genericon genericon-anchor" title="genericon-anchor">anchor</div>
			
			<div alt="f101" class="genericon genericon-aside" title="genericon-aside">aside</div>
			
			<div alt="f416" class="genericon genericon-attachment" title="genericon-attachment">attachment</div>
			
			<div alt="f109" class="genericon genericon-audio" title="genericon-audio">audio</div>
			
			<div alt="f471" class="genericon genericon-bold" title="genericon-bold">bold</div>
			
			<div alt="f444" class="genericon genericon-book" title="genericon-book">book</div>
			
			<div alt="f50a" class="genericon genericon-bug" title="genericon-bug">bug</div>
			
			<div alt="f447" class="genericon genericon-cart" title="genericon-cart">cart</div>
			
			<div alt="f301" class="genericon genericon-category" title="genericon-category">category</div>
			
			<div alt="f108" class="genericon genericon-chat" title="genericon-chat">chat</div>
			
			<div alt="f418" class="genericon genericon-checkmark" title="genericon-checkmark">checkmark</div>
			
			<div alt="f405" class="genericon genericon-close" title="genericon-close">close</div>
			
			<div alt="f406" class="genericon genericon-close-alt" title="genericon-close-alt">close-alt</div>
			
			<div alt="f426" class="genericon genericon-cloud" title="genericon-cloud">cloud</div>
			
			<div alt="f440" class="genericon genericon-cloud-download" title="genericon-cloud-download">cloud-download</div>
			
			<div alt="f441" class="genericon genericon-cloud-upload" title="genericon-cloud-upload">cloud-upload</div>
			
			<div alt="f462" class="genericon genericon-code" title="genericon-code">code</div>
			
			<div alt="f216" class="genericon genericon-codepen" title="genericon-codepen">codepen</div>
			
			<div alt="f445" class="genericon genericon-cog" title="genericon-cog">cog</div>
			
			<div alt="f432" class="genericon genericon-collapse" title="genericon-collapse">collapse</div>
			
			<div alt="f300" class="genericon genericon-comment" title="genericon-comment">comment</div>
			
			<div alt="f305" class="genericon genericon-day" title="genericon-day">day</div>
			
			<div alt="f221" class="genericon genericon-digg" title="genericon-digg">digg</div>
			
			<div alt="f443" class="genericon genericon-document" title="genericon-document">document</div>
			
			<div alt="f428" class="genericon genericon-dot" title="genericon-dot">dot</div>
			
			<div alt="f502" class="genericon genericon-downarrow" title="genericon-downarrow">downarrow</div>
			
			<div alt="f50b" class="genericon genericon-download" title="genericon-download">download</div>
			
			<div alt="f436" class="genericon genericon-draggable" title="genericon-draggable">draggable</div>
			
			<div alt="f201" class="genericon genericon-dribbble" title="genericon-dribbble">dribbble</div>
			
			<div alt="f225" class="genericon genericon-dropbox" title="genericon-dropbox">dropbox</div>
			
			<div alt="f433" class="genericon genericon-dropdown" title="genericon-dropdown">dropdown</div>
			
			<div alt="f434" class="genericon genericon-dropdown-left" title="genericon-dropdown-left">dropdown-left</div>
			
			<div alt="f411" class="genericon genericon-edit" title="genericon-edit">edit</div>
			
			<div alt="f476" class="genericon genericon-ellipsis" title="genericon-ellipsis">ellipsis</div>
			
			<div alt="f431" class="genericon genericon-expand" title="genericon-expand">expand</div>
			
			<div alt="f442" class="genericon genericon-external" title="genericon-external">external</div>
			
			<div alt="f203" class="genericon genericon-facebook" title="genericon-facebook">facebook</div>
			
			<div alt="f204" class="genericon genericon-facebook-alt" title="genericon-facebook-alt">facebook-alt</div>
			
			<div alt="f458" class="genericon genericon-fastforward" title="genericon-fastforward">fastforward</div>
			
			<div alt="f413" class="genericon genericon-feed" title="genericon-feed">feed</div>
			
			<div alt="f468" class="genericon genericon-flag" title="genericon-flag">flag</div>
			
			<div alt="f211" class="genericon genericon-flickr" title="genericon-flickr">flickr</div>
			
			<div alt="f226" class="genericon genericon-foursquare" title="genericon-foursquare">foursquare</div>
			
			<div alt="f474" class="genericon genericon-fullscreen" title="genericon-fullscreen">fullscreen</div>
			
			<div alt="f103" class="genericon genericon-gallery" title="genericon-gallery">gallery</div>
			
			<div alt="f200" class="genericon genericon-github" title="genericon-github">github</div>
			
			<div alt="f206" class="genericon genericon-googleplus" title="genericon-googleplus">googleplus</div>
			
			<div alt="f218" class="genericon genericon-googleplus-alt" title="genericon-googleplus-alt">googleplus-alt</div>
			
			<div alt="f50c" class="genericon genericon-handset" title="genericon-handset">handset</div>
			
			<div alt="f461" class="genericon genericon-heart" title="genericon-heart">heart</div>
			
			<div alt="f457" class="genericon genericon-help" title="genericon-help">help</div>
			
			<div alt="f404" class="genericon genericon-hide" title="genericon-hide">hide</div>
			
			<div alt="f505" class="genericon genericon-hierarchy" title="genericon-hierarchy">hierarchy</div>
			
			<div alt="f409" class="genericon genericon-home" title="genericon-home">home</div>
			
			<div alt="f102" class="genericon genericon-image" title="genericon-image">image</div>
			
			<div alt="f455" class="genericon genericon-info" title="genericon-info">info</div>
			
			<div alt="f215" class="genericon genericon-instagram" title="genericon-instagram">instagram</div>
			
			<div alt="f472" class="genericon genericon-italic" title="genericon-italic">italic</div>
			
			<div alt="f427" class="genericon genericon-key" title="genericon-key">key</div>
			
			<div alt="f503" class="genericon genericon-leftarrow" title="genericon-leftarrow">leftarrow</div>
			
			<div alt="f107" class="genericon genericon-link" title="genericon-link">link</div>
			
			<div alt="f207" class="genericon genericon-linkedin" title="genericon-linkedin">linkedin</div>
			
			<div alt="f208" class="genericon genericon-linkedin-alt" title="genericon-linkedin-alt">linkedin-alt</div>
			
			<div alt="f417" class="genericon genericon-location" title="genericon-location">location</div>
			
			<div alt="f470" class="genericon genericon-lock" title="genericon-lock">lock</div>
			
			<div alt="f410" class="genericon genericon-mail" title="genericon-mail">mail</div>
			
			<div alt="f422" class="genericon genericon-maximize" title="genericon-maximize">maximize</div>
			
			<div alt="f419" class="genericon genericon-menu" title="genericon-menu">menu</div>
			
			<div alt="f50d" class="genericon genericon-microphone" title="genericon-microphone">microphone</div>
			
			<div alt="f421" class="genericon genericon-minimize" title="genericon-minimize">minimize</div>
			
			<div alt="f50e" class="genericon genericon-minus" title="genericon-minus">minus</div>
			
			<div alt="f307" class="genericon genericon-month" title="genericon-month">month</div>
			
			<div alt="f50f" class="genericon genericon-move" title="genericon-move">move</div>
			
			<div alt="f429" class="genericon genericon-next" title="genericon-next">next</div>
			
			<div alt="f456" class="genericon genericon-notice" title="genericon-notice">notice</div>
			
			<div alt="f506" class="genericon genericon-paintbrush" title="genericon-paintbrush">paintbrush</div>
			
			<div alt="f219" class="genericon genericon-path" title="genericon-path">path</div>
			
			<div alt="f448" class="genericon genericon-pause" title="genericon-pause">pause</div>
			
			<div alt="f437" class="genericon genericon-phone" title="genericon-phone">phone</div>
			
			<div alt="f473" class="genericon genericon-picture" title="genericon-picture">picture</div>
			
			<div alt="f308" class="genericon genericon-pinned" title="genericon-pinned">pinned</div>
			
			<div alt="f209" class="genericon genericon-pinterest" title="genericon-pinterest">pinterest</div>
			
			<div alt="f210" class="genericon genericon-pinterest-alt" title="genericon-pinterest-alt">pinterest-alt</div>
			
			<div alt="f452" class="genericon genericon-play" title="genericon-play">play</div>
			
			<div alt="f439" class="genericon genericon-plugin" title="genericon-plugin">plugin</div>
			
			<div alt="f510" class="genericon genericon-plus" title="genericon-plus">plus</div>
			
			<div alt="f224" class="genericon genericon-pocket" title="genericon-pocket">pocket</div>
			
			<div alt="f217" class="genericon genericon-polldaddy" title="genericon-polldaddy">polldaddy</div>
			
			<div alt="f460" class="genericon genericon-portfolio" title="genericon-portfolio">portfolio</div>
			
			<div alt="f430" class="genericon genericon-previous" title="genericon-previous">previous</div>
			
			<div alt="f469" class="genericon genericon-print" title="genericon-print">print</div>
			
			<div alt="f106" class="genericon genericon-quote" title="genericon-quote">quote</div>
			
			<div alt="f511" class="genericon genericon-rating-empty" title="genericon-rating-empty">rating-empty</div>
			
			<div alt="f512" class="genericon genericon-rating-full" title="genericon-rating-full">rating-full</div>
			
			<div alt="f513" class="genericon genericon-rating-half" title="genericon-rating-half">rating-half</div>
			
			<div alt="f222" class="genericon genericon-reddit" title="genericon-reddit">reddit</div>
			
			<div alt="f420" class="genericon genericon-refresh" title="genericon-refresh">refresh</div>
			
			<div alt="f412" class="genericon genericon-reply" title="genericon-reply">reply</div>
			
			<div alt="f466" class="genericon genericon-reply-alt" title="genericon-reply-alt">reply-alt</div>
			
			<div alt="f467" class="genericon genericon-reply-single" title="genericon-reply-single">reply-single</div>
			
			<div alt="f459" class="genericon genericon-rewind" title="genericon-rewind">rewind</div>
			
			<div alt="f501" class="genericon genericon-rightarrow" title="genericon-rightarrow">rightarrow</div>
			
			<div alt="f400" class="genericon genericon-search" title="genericon-search">search</div>
			
			<div alt="f438" class="genericon genericon-send-to-phone" title="genericon-send-to-phone">send-to-phone</div>
			
			<div alt="f454" class="genericon genericon-send-to-tablet" title="genericon-send-to-tablet">send-to-tablet</div>
			
			<div alt="f415" class="genericon genericon-share" title="genericon-share">share</div>
			
			<div alt="f403" class="genericon genericon-show" title="genericon-show">show</div>
			
			<div alt="f514" class="genericon genericon-shuffle" title="genericon-shuffle">shuffle</div>
			
			<div alt="f507" class="genericon genericon-sitemap" title="genericon-sitemap">sitemap</div>
			
			<div alt="f451" class="genericon genericon-skip-ahead" title="genericon-skip-ahead">skip-ahead</div>
			
			<div alt="f450" class="genericon genericon-skip-back" title="genericon-skip-back">skip-back</div>
			
			<div alt="f220" class="genericon genericon-skype" title="genericon-skype">skype</div>
			
			<div alt="f424" class="genericon genericon-spam" title="genericon-spam">spam</div>
			
			<div alt="f515" class="genericon genericon-spotify" title="genericon-spotify">spotify</div>
			
			<div alt="f100" class="genericon genericon-standard" title="genericon-standard">standard</div>
			
			<div alt="f408" class="genericon genericon-star" title="genericon-star">star</div>
			
			<div alt="f105" class="genericon genericon-status" title="genericon-status">status</div>
			
			<div alt="f449" class="genericon genericon-stop" title="genericon-stop">stop</div>
			
			<div alt="f223" class="genericon genericon-stumbleupon" title="genericon-stumbleupon">stumbleupon</div>
			
			<div alt="f463" class="genericon genericon-subscribe" title="genericon-subscribe">subscribe</div>
			
			<div alt="f465" class="genericon genericon-subscribed" title="genericon-subscribed">subscribed</div>
			
			<div alt="f425" class="genericon genericon-summary" title="genericon-summary">summary</div>
			
			<div alt="f453" class="genericon genericon-tablet" title="genericon-tablet">tablet</div>
			
			<div alt="f302" class="genericon genericon-tag" title="genericon-tag">tag</div>
			
			<div alt="f303" class="genericon genericon-time" title="genericon-time">time</div>
			
			<div alt="f435" class="genericon genericon-top" title="genericon-top">top</div>
			
			<div alt="f407" class="genericon genericon-trash" title="genericon-trash">trash</div>
			
			<div alt="f214" class="genericon genericon-tumblr" title="genericon-tumblr">tumblr</div>
			
			<div alt="f516" class="genericon genericon-twitch" title="genericon-twitch">twitch</div>
			
			<div alt="f202" class="genericon genericon-twitter" title="genericon-twitter">twitter</div>
			
			<div alt="f446" class="genericon genericon-unapprove" title="genericon-unapprove">unapprove</div>
			
			<div alt="f464" class="genericon genericon-unsubscribe" title="genericon-unsubscribe">unsubscribe</div>
			
			<div alt="f401" class="genericon genericon-unzoom" title="genericon-unzoom">unzoom</div>
			
			<div alt="f500" class="genericon genericon-uparrow" title="genericon-uparrow">uparrow</div>
			
			<div alt="f304" class="genericon genericon-user" title="genericon-user">user</div>
			
			<div alt="f104" class="genericon genericon-video" title="genericon-video">video</div>
			
			<div alt="f517" class="genericon genericon-videocamera" title="genericon-videocamera">videocamera</div>
			
			<div alt="f212" class="genericon genericon-vimeo" title="genericon-vimeo">vimeo</div>
			
			<div alt="f414" class="genericon genericon-warning" title="genericon-warning">warning</div>
			
			<div alt="f475" class="genericon genericon-website" title="genericon-website">website</div>
			
			<div alt="f306" class="genericon genericon-week" title="genericon-week">week</div>
			
			<div alt="f205" class="genericon genericon-wordpress" title="genericon-wordpress">wordpress</div>
			
			<div alt="f504" class="genericon genericon-xpost" title="genericon-xpost">xpost</div>
			
			<div alt="f213" class="genericon genericon-youtube" title="genericon-youtube">youtube</div>
			
			<div alt="f402" class="genericon genericon-zoom" title="genericon-zoom">zoom</div>

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
