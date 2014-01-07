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