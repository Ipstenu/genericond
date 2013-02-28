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
	   // Main Settings
		?>
		<div class="wrap">
        <h2><div class="genericon genericon-wordpress"></div>Genericons</h2>
        
        <p>There are no settings for Genericon'd. This page is for documentation.</p>


        <h3>Usage Example</h3>

        <p><div alt="f202" class="genericon genericon-twitter"></div> is made by either <code>[genericon icon=twitter]</code> or <code>&lt;div alt="f202" class="genericon genericon-twitter"&gt;&lt;/div&gt;</code></p>
        
        <h3>Available Genericons</h3>

        <p>Here are all currently available Genericons</p>

        	<div class="icons">

            <h3>Post Formats</h3>

<table class="form-table">
<tbody><tr valign="top">
<td>
    <ul class="the-icons">
        <li><div alt="f100" class="genericon genericon-standard"></div> standard</li>
        <li><div alt="f101" class="genericon genericon-aside"></div> aside</li>
        <li><div alt="f102" class="genericon genericon-image"></div> image</li>
        <li><div alt="f103" class="genericon genericon-gallery"></div> gallery</li>
        <li><div alt="f104" class="genericon genericon-video"></div> video</li>
        <li><div alt="f105" class="genericon genericon-status"></div> status</li>
        <li><div alt="f106" class="genericon genericon-quote"></div> quote</li>
        <li><div alt="f107" class="genericon genericon-link"></div> link</li>
        <li><div alt="f108" class="genericon genericon-chat"></div> chat</li>
        <li><div alt="f109" class="genericon genericon-audio"></div> audio</li>
    </ul>   
</td>
</tr>
</tbody></table>

<h3>Social Icons</h3>

<table class="form-table">
<tbody><tr valign="top">
<td>
    <ul class="the-icons">
        <li><div alt="f200" class="genericon genericon-github"></div> github</li>
        <li><div alt="f201" class="genericon genericon-dribbble"></div> dribble</li>
        <li><div alt="f202" class="genericon genericon-twitter"></div> twitter</li>
        <li><div alt="f203" class="genericon genericon-facebook"></div> facebook</li>
        <li><div alt="f204" class="genericon genericon-facebook-alt"></div> facebook-alt</li>
        <li><div alt="f205" class="genericon genericon-wordpress"></div> wordpress</li>
        <li><div alt="f206" class="genericon genericon-googleplus"></div> googleplus</li>
        <li><div alt="f207" class="genericon genericon-linkedin"></div> linkedin</li>
        <li><div alt="f208" class="genericon genericon-linkedin-alt"></div> linkedin-alt</li>
        <li><div alt="f209" class="genericon genericon-pinterest"></div> pintrest</li>
        <li><div alt="f210" class="genericon genericon-pinterest-alt"></div> pintrest-alt</li>
        <li><div alt="f211" class="genericon genericon-flickr"></div> flickr</li>
        <li><div alt="f212" class="genericon genericon-vimeo"></div> vimeo</li>
        <li><div alt="f213" class="genericon genericon-youtube"></div> youtube</li>
    </ul>   
</td>
</tr>
</tbody></table>

<h3>Meta Icons</h3>

<table class="form-table">
<tbody><tr valign="top">
<td>
    <ul class="the-icons">
        <li><div alt="f300" class="genericon genericon-comment"></div> comment</li>
        <li><div alt="f301" class="genericon genericon-category"></div> category</li>
        <li><div alt="f302" class="genericon genericon-tag"></div> tag</li>
        <li><div alt="f303" class="genericon genericon-time"></div> time</li>
        <li><div alt="f304" class="genericon genericon-user"></div> user</li>
        <li><div alt="f305" class="genericon genericon-day"></div> day</li>
        <li><div alt="f306" class="genericon genericon-week"></div> week</li>
        <li><div alt="f307" class="genericon genericon-month"></div> month</li>
        <li><div alt="f308" class="genericon genericon-pinned"></div> pinned</li>
    </ul>   
</td>
</tr>
</tbody></table>

<h3>Other Icons</h3>

<table class="form-table">
<tbody><tr valign="top">
<td>
    <ul class="the-icons">
        <li><div alt="f400" class="genericon genericon-search"></div> search</li>
        <li><div alt="f401" class="genericon genericon-unzoom"></div> unzoom</li>
        <li><div alt="f402" class="genericon genericon-zoom"></div> zoom</li>
        <li><div alt="f403" class="genericon genericon-show"></div> show</li>
        <li><div alt="f404" class="genericon genericon-hide"></div> hide</li>
        <li><div alt="f405" class="genericon genericon-close"></div> close</li>
        <li><div alt="f406" class="genericon genericon-close-alt"></div> close-alt</li>
        <li><div alt="f407" class="genericon genericon-trash"></div> trash</li>
        <li><div alt="f408" class="genericon genericon-star"></div> star</li>
        <li><div alt="f409" class="genericon genericon-home"></div> home</li>
        <li><div alt="f410" class="genericon genericon-mail"></div> mail</li>
        <li><div alt="f411" class="genericon genericon-edit"></div> edit</li>
        <li><div alt="f412" class="genericon genericon-reply"></div> reply</li>
        <li><div alt="f413" class="genericon genericon-feed"></div> feed</li>
        <li><div alt="f414" class="genericon genericon-warning"></div> warning</li>
        <li><div alt="f415" class="genericon genericon-share"></div> share</li>
        <li><div alt="f416" class="genericon genericon-attachment"></div> attachment</li>
        <li><div alt="f417" class="genericon genericon-location"></div> location</li>
        <li><div alt="f418" class="genericon genericon-checkmark"></div> checkmark</li>
        <li><div alt="f419" class="genericon genericon-menu"></div> menu</li>
        <li><div alt="f420" class="genericon genericon-top"></div> top</li>
        <li><div alt="f421" class="genericon genericon-minimize"></div> minimize</li>
        <li><div alt="f422" class="genericon genericon-maximize"></div> maximize</li>
        <li><div alt="f423" class="genericon genericon-404"></div> 404</li>
    </ul>   
</td>
</tr>
</tbody></table>
        
        <h3>Generic Shapes</h3>
        
<table class="form-table">
<tbody><tr valign="top">
<td>
    <ul class="the-icons">
        <li><div alt="f500" class="genericon genericon-uparrow"></div> uparrow</li>
        <li><div alt="f501" class="genericon genericon-rightarrow"></div> rightarrow</li>
        <li><div alt="f502" class="genericon genericon-downarrow"></div> downarrow</li>
        <li><div alt="f503" class="genericon genericon-leftarrow"></div> leftarrow</li>
    </ul>   
</td>
</tr>
</tbody></table>        

    <p><em>The following documentation is from the default example.html included in the Genericons download package.</em></p>

	<p>If you want to insert an icon manually using the <code>:before</code> selector, you can setup CSS rules like the following example. <strong>Make sure to set the size to a multiple of 16px</strong> or the icons could end up looking fuzzy:</p>

<p><textarea class="code" style="min-height: 150px;" onclick="select();">.my-icon:before {
        content: '\2605';
        display: inline-block;
        -webkit-font-smoothing: antialiased;
        font: normal 32px/1 'Genericons';
        vertical-align: middle;
}</textarea></p>

	<p>Add a matching class to your HTML:</p>

	<p><code>&lt;div class="my-icon"&gt;You're a Star!&lt;/div&gt;</code></p>

	<p>Here's the result: <span class="my-icon">You're a Star!</span></p>

	<h2>Examples</h2>

	<p>Turn every icon a <span style="color: #fa8072;">Salmon</span> color:</p>

<p><textarea class="code" style="min-height: 70px" onclick="select();">
.genericon {
	color: #fa8072;
}</textarea></p>

	<p>Or turn the stars <span style="color: #ffd700;">Gold</span>:</p>

<p><textarea class="code" style="min-height: 70px" onclick="select();">
.genericon-star {
	color: #fa8072;
}</textarea></p>

	<p>Use icons for bulleted lists:</p>

	<ul class="my-checklist">
		<li>One</li>
		<li>Two</li>
		<li>Three</li>
		<li>Four</li>
	</ul>

<p><textarea class="code" style="min-height: 130px" onclick="select();">
<ul class="my-checklist">
	<li>One</li>
	<li>Two</li>
	<li>Three</li>
	<li>Four</li>
</ul></textarea></p>

<p><textarea class="code" style="min-height: 260px;" onclick="select();">
.my-checklist {
	list-style-type: none;
	text-indent: -16px;
}
.my-checklist li:before {
	padding-right: 16px;
        content: '\f418';
        display: inline-block;
        -webkit-font-smoothing: antialiased;
        font: normal 16px/1 'Genericons';
        vertical-align: text-top;
}</textarea></p>

	<p>Use icons to style blockquotes:</p>

	<blockquote class="my-blockquote">Sometimes I've believed as many as six impossible things before breakfast. &mdash;<em>Lewis Carroll</em></blockquote>
	<blockquote class="my-blockquote">`Twas brillig, and the slithy toves Did gyre and gimble in the wabe: All mimsy were the borogoves, And the mome raths outgrabe. "Beware the Jabberwock, my son!  The jaws that bite, the claws that catch!  Beware the Jubjub bird, and shun The frumious Bandersnatch!"</blockquote>

<p><textarea class="code" style="min-height: 40px;" onclick="select();"><blockquote class="my-blockquote">Sometimes I've believed as many as six impossible things before breakfast. &mdash;<em>Lewis Carroll</em></blockquote></textarea></p>

<p><textarea class="code" style="min-height: 300px;" onclick="select();">
.my-blockquote {
	background: #eee;
	border-left: 32px solid #ddd;
	padding: 10px;
}
.my-blockquote:before {
	margin-left: -42px;
	padding-right: 10px;
        content: '\f106';
        display: inline-block;
        -webkit-font-smoothing: antialiased;
        font: normal 32px/20px 'Genericons';
        vertical-align: bottom;
} </textarea></p>

	<p>Use icons to style blockquotes:</p>

	<a class="my-button" href="javascript:void()"><i class="genericon genericon-show"></i> View</a>
	<a class="my-button" href="javascript:void()"><i class="genericon genericon-audio"></i> Listen</a>

<p><textarea class="code" style="min-height: 40px;" onclick="select();"><a class="my-button" href="#"><i class="genericon genericon-show"></i> View</a>
<a class="my-button" href="#"><i class="genericon genericon-audio"></i> Listen</a></textarea></p>

<p><textarea class="code" style="min-height: 300px;" onclick="select();">
.my-button {
	font-family: Helvetica, sans-serif;
	background: #e05d22; /* Old browsers */
	background: -webkit-linear-gradient(top, #e05d22 0%, #d94412 100%); /* Chrome10+,Safari5.1+ */
	background: -moz-linear-gradient(   top, #e05d22 0%, #d94412 100%); /* FF3.6+ */
	background: -ms-linear-gradient(    top, #e05d22 0%, #d94412 100%); /* IE10+ */
	background: -o-linear-gradient(     top, #e05d22 0%, #d94412 100%); /* Opera 11.10+ */
	background: linear-gradient(  to bottom, #e05d22 0%, #d94412 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e05d22', endColorstr='#d94412', GradientType=0); /* IE6-9 */
	display: inline-block;
	padding: 10px 16px 6px 16px;
	color: #fff;
	text-decoration: none;
	border: none;
	border-bottom: 3px solid #b93207;
	border-radius: 2px;
}

.my-button:hover,
.my-button:focus {
	background: #ed6a31; /* Old browsers */
	background: -webkit-linear-gradient(top, #ed6a31 0%, #e55627 100%); /* Chrome10+,Safari5.1+ */
	background: -moz-linear-gradient(   top, #ed6a31 0%, #e55627 100%); /* FF3.6+ */
	background: -ms-linear-gradient(    top, #ed6a31 0%, #e55627 100%); /* IE10+ */
	background: -o-linear-gradient(     top, #ed6a31 0%, #e55627 100%); /* Opera 11.10+ */
	background: linear-gradient(  to bottom, #ed6a31 0%, #e55627 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ed6a31', endColorstr='#e55627', GradientType=0); /* IE6-9 */
	outline: none;
}

.my-button:active {
	background: #d94412; /* Old browsers */
	background: -webkit-linear-gradient(top, #d94412 0%, #e05d22 100%); /* Chrome10+,Safari5.1+ */
	background: -moz-linear-gradient(   top, #d94412 0%, #e05d22 100%); /* FF3.6+ */
	background: -ms-linear-gradient(    top, #d94412 0%, #e05d22 100%); /* IE10+ */
	background: -o-linear-gradient(     top, #d94412 0%, #e05d22 100%); /* Opera 11.10+ */
	background: linear-gradient(  to bottom, #d94412 0%, #e05d22 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#d94412', endColorstr='#e05d22', GradientType=0); /* IE6-9 */
	border: none;
	border-top: 3px solid #b93207;
	padding: 6px 16px 10px 16px;
}</textarea>/</p>

	<h2>CSS Preprocessors</h2>

	<p>Preprocessing extensions such as Sass (SCSS Syntax) or LESS</a> can make it easier to manage CSS for a lot of things at once using things like variables and mixins.</p>

	<p>This example will seup the basic genericon rules and sets a color you can use for all icons using Sass:</p>

<p><textarea class="code" style="min-height: 360px;" onclick="select();">$icon-color: "#fa8072";

.genericon {
        color: $icon-color;
}

@mixin genericon-rules {
        display: inline-block;
        -webkit-font-smoothing: antialiased;
        font: normal 16px/1 'Genericons';
        vertical-align: middle;
}

.my-icon:before {
        content: '\2605';
        @include genericon-rules;
}</textarea></p>

	<p>Here is a similar example for LESS:</p>

<p><textarea class="code" style="min-height: 360px;" onclick="select();">@icon-color: "#fa8072";

.genericon {
        color: @icon-color;
}

.genericon-rules {
        display: inline-block;
        -webkit-font-smoothing: antialiased;
        font: normal 16px/1 'Genericons';
        vertical-align: middle;
}

.my-icon:before {
        content: '\2605';
        .genericon-rules;
}</textarea></p>

	<h2>Fallback images for IE7 and below</h2>

	<p>Genericons <strong>does not come with fallback icons by default</strong> -- therefore you have to create them yourself. If you are using HTML similar to this example:

	<p><code>&lt;span class="genericon genericon-warning"&gt;&lt;/span&gt;</code></p>

	<p>You can use the asterisk hack to serve a different icon to IE7 once you have saved the fallback icons to your project:</p>

<textarea class="code" style="min-height: 85px;" onclick="select();">.genericon-warning {
        *background: url(fallback-icon.png) no-repeat center center;
        *text-indent: 100%;
}</textarea>

</div>



        </div>
        <?php
	}


}

new GenericonsHELF();
