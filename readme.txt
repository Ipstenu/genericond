=== Genericon'd ===
Contributors: Ipstenu
Tags: icons, genericons, font icon, UI
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 2.0.9.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables easy use of the Genericons icon font set from within WordPress.  Icons can be inserted using either HTML or a shortcode.

== Description ==

Genericons are vector icons embedded in a webfont designed to be clean and simple keeping with a generic aesthetic.

A full list of the Genericons icons is available at [http://genericons.com/](http://genericons.com/) but also on the WP Admin -> Apperance -> Genericon'd page.

To use any of the Genericons icons on your WordPress site you can use basic HTML (for inserting in themes and functions) or shortcodes (for use in posts or widgets). You can adjust the size of the icons via css or, when using the shortcode, the size attribute. Default size is 16px.

To display the Twitter icon:

HTML: `<div class="genericond genericon genericon-twitter"></div>` or `<i class="genericond genericon genericon-twitter"></i>`

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

If you want to put an icon AND text, you 

= When I exported and imported my content, the menu code didn't come with. What up? =

There's an alternate way to add in menu code: `<i class="genericond genericon genericon-facebook"></i>`

And apparently it doesn't export/import right. I don't know why. I'm working on it, and a way to put shortcodes in menus, but patches are welcome.

= How do I change colors? =

The power of CSS! If you want to change the color to red for all genericons, add `.genericon {color:red;}` to your theme's CSS. If you just want Twitter to be blue, add `.genericon-twitter {color:blue;}` and so on and so forth. Colors are based on font, you see.

= Okay, but I want to change color in just this one use... =

I know what you mean. Try this: `[genericon icon=twitter color=blue]`

It uses inline styling, which I hate, but this was the best way to solve the problem that I could find (suggestions welcome).

= Speaking of, can I make just this one icon bigger? =

Sure can! Use this: `[genericon icon=twitter size=2x]`

You can use 2x through 6x. Anything else punts it to 1x.

= I want to repeat an icon =

You mean like this: `[genericon icon=star repeat=4]`

= Can I flip an icon? =

Sure! `[genericon icon=twitter rotate={90|180|270|flip-horizontal|flip-vertical} ]`

= How about changing the hover-color? =

While I certainly could write that in, I decided not to. You totally can do this with CSS, however I feel you should only be changing color when there's an action, like hovering over a link, and generally you've already done that. But if you want to manually do it in your CSS, it would go like this: `.genericon-twitter:hover {background-color:pink;color:purple;}`

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
2. Zaboo, patron avatar of Genericon'd

== Changelog ==

= 2.0.9.2 =
* 2013-07-19
* Corrected conflict with Jetpack

= 2.0.9.1 =
* 2013-07-18
* Fixed bug with resizing when other themes call in Genericons
* Change manual nested if into an `in_array()` instead (smarter!)
* Corrected CSS to match official files 100%
* Renamed `/lib/` to `/genericons/` (for easier updates)
* Moved Genericon'd CSS to it's own file and folder (for easier updates)
* Added in rotate (Credit: <a href="http://fortawesome.github.io/Font-Awesome/examples/#rotated-flipped">Font-Awesome</a>)

= 2.0.9 =
* 2013-06-26
* Updated for Genericons 2.0.9 (our numbers are not yours)
* Hilighting new/updated icons on the admin end

= 2.0 =
* 2013-05-10
* 25 new (or updated) Genericons by Joen!

= 1.3.1 =
* 2013-04-11 <em>NON CRITICAL UPDATE</em>
* Typo! Spelled pinterest wrong (pintrest vs pinterest ...), props to Quique

= 1.3 =
* 2013-03-09
* Changed resize to use multiples of 16px, after talking to Joen.
* Tumblr added by Joen, in response to bribery of version 1.3

= 1.2 =
* 2013-03-07
* Added in variable for size to allow for on-the-fly adjustments to color and size.
* Changed image size from 16px to 1em (this isn't perfect, but allows for smexier resizing)
* Fixed broken settings pages.

= 1.1 =
* 2013-03-06
* Tweaks and adjustments. 
* New banner (thanks to Joen)

= 1.0 =
* 2013-02-27
* Initial release

== Upgrade Notice ==

New class of "genericond" added to allow for more precise styling in a way that doesn't blow up themes.