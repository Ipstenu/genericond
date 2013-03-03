=== Genericon'd ===
Contributors: ipstenu
Tags: icons, genericons, font icon, UI
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables easy use of the Genericons icon font set from within WordPress.  Icons can be inserted using either HTML or a shortcode.

== Description ==

Genericons are vector icons embedded in a webfont designed to be clean and simple keeping with a generic aesthetic.

A full list of the Genericons icons is available: [http://genericons.com/](http://genericons.com/)

To use any of the Genericons icons on your WordPress site you can use basic HTML (for inserting in themes and functions) or shortcodes (for use in posts or widgets)

To display the Twitter icon

HTML: `<div class="genericon genericon-twitter"></div>`

Shortcode: `[genericon icon=twitter]`

== Installation ==

Install as a normal WordPress Plugin

Add shortcode or HTML to your posts, pages and even widgets to display a Genericons icon.

== Frequently Asked Questions ==

= Aren't they called Genericons with an S? =

Yes, but Genericon'd is a Zaboo-esque sort of way of saying 'These icons have been genericonified!' I was in a The Guild frame of mind. Also since this is not the official plugin, I didn't want to use that slug.

= What are all the codes to use? =

If you're like me, you forget this alllll the time. On your WP dashboard, go to Apperance -> Genericon'd. The page there will show you everything you need to know about using Genericons.

= Can I add it to menus? =

Yes! If you use CSS classes, you can apply a class like this:  `genericon genericon-facebook` You may need to jigger about with css to make the layout perfect.

= Why don't these work on IE 7? =

Genericons itself <strong>does not come with fallback icons by default</strong> -- therefore you have to create them yourself. If you are using HTML similar to this example: <code>&lt;span class="genericon genericon-warning"&gt;&lt;/span&gt;</code>

You can use the asterisk hack to serve a different icon to IE7 once you have saved the fallback icons to your project:

<pre>.genericon-warning {
    *background: url(fallback-icon.png) no-repeat center center;
    *text-indent: 100%;
}
</pre>

== Screenshots ==

1. Genericon'd help page

== Changelog ==

= 1.1 =
* 
* Tweaks and adjustments. 
* Moving documentation to it's own page for easier updating.


= 1.0 =
* 2013-02-27
* Initial release