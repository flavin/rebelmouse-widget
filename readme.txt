=== RebelMouse Widget ===
Contributors: Francisco Lavin
Tags: Social, RebelMouse, embed
Requires at least: 3.0
Tested up to: 3.3
Stable tag: trunk
License: GPLv2

Add a widget to add your rebelmouse stream

== Description ==

Add your [RebelMouse](http://www.rebelmouse.com/) front page to your site.

You can add your stream as a widget or insert the code wherever you want by following the steps in FAQ section.

== Installation ==

This Wordpress widget is easy to install:

1. Extract the contents of the zip file into your wp-content/plugins directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the Appearance -> widget screen, drag the widget to a sidebar, and fill out the necessary fields and options.

Alternatively, you can insert a version without a widget (see below):

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

1. Options page
2. Example

== Changelog ==

== Upgrade Notice ==

== Arbitrary section ==
