=== Plugin Name ===
Contributors: Kopepasah
Donate link: http://kopepasah.com/donate/
Tags: gist, github
Requires at least: 3.5
Tested up to: 3.8.1
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple WordPress plugin that adds a gist shortcode for getting a single Gist and the files within.

== Description ==

#Get Gist

A simple WordPress plugin that adds a gist shortcode for getting a single Gist and the files within. It uses the Gist V3 API. Has the option for authentication via Personal Access Token. 

##Usage
Get a Gist using a WordPress shortcode: `[gist id=00000000 file=file-name.php]`

The Gist will return as plain text. I recommend using WordPress plugin Syntaxhighlighter Evolved to highlight the Gist. See below for details.

##Options
| Option | Value      | Default    | Description                                           | Note                                                                  |
| ------ | ---------- | ---------- | ----------------------------------------------------- | --------------------------------------------------------------------- |
| id     | numeric    | null       | The id of the Gist.                                   | Required.                                                             |
| file   | string     | null       | The file name.                                        | Optional. Using no file name will return all files.                   |
| synhi  | true/false | false      | Highlight the gist.                                   | Optional, but requires Syntaxhighlighter Evolved Plugin for WordPress |
| wrap   | true/false | false      | Wrap the highlighted gist with .syntax-wrapper class. | Optional, but requires synhi to be true.                              |

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `get-gist` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add your Github API key on the plugins settings page (optional)

== Frequently Asked Questions ==

Have a question?

== Screenshots ==

1. None.

== Changelog ==

= 1.0.0 =
* Initial release version.

= 1.1.0 =
* Added ability to insert and API key.

= 1.1.1 =
* Added readme.txt.