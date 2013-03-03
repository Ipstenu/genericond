<?php
/*
Plugin Name: Genericon'd
Plugin URI: http://halfelf.org/
Description: Use the Genericon icon set within WordPress. Icons can be inserted using either HTML or a shortcode.
Version: 1.1
Author: Mika Epstein
Author URI: http://ipstenu.org/
Author Email: ipstenu@ipstenu.org
Credits:
     Forked from Rachel Baker's Font Awesome for WordPress plugin
     https://github.com/rachelbaker/Font-Awesome-WordPress-Plugin

License:

  Copyright (C) 2013  Mika Epstein.

    This file is part of Genericons, a plugin for WordPress.

    The Genericons Plugin is free software: you can redistribute it and/or
    modify it under the terms of the GNU General Public License as published
    by the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    
    Genericons itself is free software; you can redistribute it and/or modify 
    it under the terms of the GNU General Public License as published by the 
    Free Software Foundation; either version 2 of the License, or (at your option) 
    any later version.

*/

class GenericonsHELF {
    public function __construct() {
        add_action( 'init', array( &$this, 'init' ) );
    }

    public function init() {
        add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
        add_shortcode( 'genericon', array( $this, 'setup_shortcode' ) );
        add_filter( 'widget_text', 'do_shortcode' );
        add_action( 'admin_menu', array( $this, 'add_settings_page'));
        add_filter('plugin_row_meta', array( $this, 'donate_link'), 10, 2);
    }

    public function register_plugin_styles() {
        global $wp_styles;
        wp_enqueue_style( 'genericons-styles', plugins_url( 'lib/genericons.min.css', __FILE__  ) );
    }

    public function setup_shortcode( $params ) {
        extract( shortcode_atts( array(
                    'icon'  => 'share'
                ), $params ) );
        $genericon = '<i class="genericon genericon-'.$params['icon'].'"></i>';

        return $genericon;
    }

    // donate link on manage plugin page
    public function donate_link($links, $file) {
        if ($file == plugin_basename(__FILE__)) {
                $donate_link = '<a href="https://www.wepay.com/donations/halfelf-wp">Donate</a>';
                $links[] = $donate_link;
        }
        return $links;
    }

     // Sets up the settings page
	public function add_settings_page() {
        $genericonspage = add_theme_page(__('Genericon\'d'), __('Genericon\'d'), 'edit_posts', 'genericons', array('GenericonsHELF', 'settings_page'));
        add_action( "admin_print_scripts-$genericonspage",  array( $this, 'genericons_loadcss_admin_head') );
    	}

    function genericons_loadcss_admin_head() {
    	wp_enqueue_style( 'genericons-example-styles', plugins_url( 'example.css', __FILE__  ) );
    	echo "<link rel='stylesheet' href='".plugins_url( 'example.css', __FILE__  )."' type='text/css' />\n";
    }

	 
 	function settings_page() {
     	include_once( 'settings.php');// Main Settings
	}

}

new GenericonsHELF();