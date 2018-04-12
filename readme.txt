=== Advanced Custom Fields: Monolith Fields ===
Contributors: Jesse Janowiak, NewCity
Tags: Advanced Custom Fields, ACF
Requires at least: 3.6.0
Tested up to: 4.9.0
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a number of custom, reusable field type to ACF that are commonly needed in Monolith framework projects.


= Compatibility =

This ACF field type is compatible with:
* ACF 5

== Installation ==

1. Copy the `acf-headline_group` folder into your `wp-content/plugins` folder
2. Activate the Headline Group plugin via the plugins admin page
3. Create a new field via ACF and select the Headline Group type
4. Read the description above for usage instructions

== Changelog ==

= 1.0.0 =
* Initial Release.

= 1.1.0 =
* Changed field ids in `image with caption` type from `image` and `credit` to `imgsrc` and `citation` to
better match monolith component variable names (breaks backwards compatibility).
* Changed category for headline group from "monolith" to "content"
