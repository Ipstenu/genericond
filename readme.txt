=== Genericon'd ===
Contributors: Ipstenu
Tags: icons, genericons, font icon, UI
Requires at least: 3.9
Tested up to: 4.6
Stable tag: 3.4.1
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

Install as a normal WordPress Plugin.

= Usage =

Add shortcode or HTML to your posts, pages and even widgets to display a Genericons icon.

Example - To display the Twitter icon:

HTML: `<div class="genericond genericon genericon-twitter"></div>` or `<i class="genericond genericon genericon-twitter"></i>`

Shortcode: `[genericon icon=twitter]`

== Frequently Asked Questions ==

= I have an idea for an icon! =

Great! I'm a monkey with a crayon! Seriously, though, I didn't make Genericons, I have no artistic ability to make more. If I did, we'd have a unicorn one. Please file issues and requests for new icons <a href="https://github.com/Automattic/Genericons/issues">directly with Genericons</a>.

= Aren't they called Genericons with an S? =

Yes, but Genericon'd is a Zaboo-esque sort of way of saying 'These icons have been genericonified!' I was in a The Guild frame of mind. Also since this is not the official plugin, I didn't want to use that slug.

= Are there any known conflicts? =

* Jetpack 2.3.1 and older had a CSS conflict. This has been resolved in Jetpack 2.3.2, so please upgrade.
* Slim Jetpack has the same issue as Jetpack, however they have not yet (to my knowledge) corrected the CSS conflict

= What are all the codes to use? =

If you're like me, you forget this alllll the time. On your WP dashboard, go to Apperance -> Genericon'd. The page there will show you everything you need to know about using Genericons, complete with clicky-copy-pasta links.

= Can I add it to menus? =

Yes! If you use CSS classes, you can apply a class like this:  `genericon genericon-facebook` You may need to jigger about with css to make the layout perfect.

If you want to put an icon AND text, you have two options. One is to use pure CSS ala <a href="http://justintadlock.com/archives/2013/08/14/social-nav-menus-part-2">Justin Tadlock's implimentation</a>, and the other is to just add in the menu text like this: `<i class="genericond genericon genericon-facebook"></i>`

= When I exported and imported my content, the menu code didn't come with. What up? =

Yeah, Apparently it doesn't export/import right. I don't know why. I'm working on it, and a way to put shortcodes in menus, but patches are welcome.

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

Genericons itself <strong>does not come with fallback icons by default</strong> -- therefore you have to create them yourself. You can use HTML similar to this example: <code>&lt;span class="genericon genericon-warning"&gt;&lt;/span&gt;</code>

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

= 3.4.1 =
* 2015-11-12
* IE8 support restored. 

= 3.4.0 = 
* 2015-09-18
* <a href="http://genericons.com/2015/09/18/3-4/">Major 3.4 release to Genericons</a>
* Move path to CSS file per change in Genericons
* Remove my rotation code as it's now included in core Genericons
* Split rotation and flip code to reflect changes above
* Fix broken rotations (which apparently was broken ages ago and no one noticed, sorry)

== Upgrade Notice ==
There is no change to the functionality of the plugin, nor have icons been added. This is for parity with the base code version.