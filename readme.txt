=== RebelMouse Widget ===
Contributors: Francisco Lavin
Tags: Social, RebelMouse, embed
Requires at least: 3.0
Tested up to: 3.3
Stable tag: trunk
License: GPLv2

Add a widget to add your rebelmouse stream

== Description ==

Add your [Rebelmouse](http://www.rebelmouse.com/) stream to your site.

You can add this as Widget, or insert code wherever you want follow steps in FAQ section.

== Installation ==

As this is a Wordpress Widget it's very easy to install.

1. Extract the contents of the zip file into your wp-content/plugins directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the Appearance -> widget screen drag the widget to a sidebar and fill out the options.

or you can insert without widget version like this:

Usage:
`<?php rebelmouse_embed( $args ); ?>`

Params:
$args could be:
    'site_name' You rebelmouse site name

    'cols' Number of columns to show (the final width is 257px for columns number)

    'skip' Element to hide in the stream: about-site, network, also-on-rm, share-frontpage

    'height' height of the iframe

    'show_button' if you want to show the follow button, 1 to show, 0 to hide.

    'theme_button' Theme for the button, could be "dark" or "clear".

Example:
`<?php rebelmouse_embed( 'site_name=rebelmouse&skip=network,also-on-rm&show_button=1' ); ?>`

== Screenshots ==

1. Options page.
2. Demo how it looks.

== Changelog ==

== Upgrade Notice ==

== Arbitrary section ==
