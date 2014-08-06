=== Genericon'd ===
Contributors: Ipstenu
Tags: icons, genericons, font icon, UI
Requires at least: 3.5
Tested up to: 4.0
Stable tag: 3.1.2
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

= Usage =

Add shortcode or HTML to your posts, pages and even widgets to display a Genericons icon.

== Frequently Asked Questions ==

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

= 3.1.2 = 
* 2014-08-06
* Updating example page
* Force recache of font CSS

= 3.1.1 = 
* 2014-07-09
* Version bump to force recache

= 3.1.0 = 
* 2014-07-08
* <a href="http://genericons.com/2014/07/08/3-1/">Major 3.1 release to Genericons</a>

= 3.0.3.4 =
* 2014-06-16
* Adding better link to settings
* Updating function names
* Minor Cleanup

= 3.0.3.3 =
* 2014-03
* Issue with debugger (<a href="https://wordpress.org/support/topic/debugger-notice">props violacase</a> though I have NO idea why you see the errors and I don't!)

= 3.0.3.2 =
* 2014-01-10
* Update of iconset (see <a href="http://genericons.com/2014/01/10/3-0-3/">Genericons 3.0.3 Release Notes</a> for details)
* Static variable for version in the code, only matters to me.
* Ignore the fact we were on 3.0.3 branch first. I suck.

= 3.0.3.1 =
* 2013-11-01
* New versions of Genericons
* Fixed regression in 3.0.2

= 3.0.2 =
* 2013-10-29
* Typo caused Genericons not to work on back end. Sorry.

= 3.0.2 =
* 2013-10-25
* Link to 'settings' from plugins page (per UI testing at WordCamp Boston)
* Cleaned up 'settings' page (per UI testing at WordCamp Boston)
* Compatible with 3.7

= 3.0.1 =
* 2013-09-27
* Update of iconset (see <a href="http://genericons.com/2013/09/25/3-0-1/">Genericons 3.0.1 Release Notes</a> for details)
* Forcing remove of other genericons if mine is found. It's not nice, but I update (generally) faster than Jetpack.

= 3.0.0 =
* 2013-09-11
* Update of iconset (see <a href="http://genericons.com/2013/09/11/3-0/">Genericons 3.0 Release Notes</a> for details)
* Reformating the admin page to match the official site (plus look cooler)
* Adding in 'copy' features like http://genericons.com/ has
* Moving 'examples' off the page into it's own page (for my sanity)

= 2.0.9.3 =
* 2013-08-26
* Jetpack fixed their conflict (no change here)
* Typo in Genericons example page
* Minor CSS conflict with MP6 (affected menus)

= 2.0.9.2 =
* 2013-07-19
* Rolled back change to correct conflict with Jetpack

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
Many icons were visually updated. Please be mindful if you choose to upgrade and check that the updated icons behave as you intended. See: <a href="http://genericons.com/2014/01/10/3-0-3/">Genericons 3.0.3 Release Notes</a>
