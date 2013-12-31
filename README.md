#Get Gist

A simple WordPress plugin that adds a gist shortcode for getting a single gist and the files within. It uses the Gist V3 API.

##Usage
Get a gist using a WordPress shortcode: [gist id=00000000 file=file-name.php]

##Options
| Option | Value      | Description                                           | Note                                                                  |
| ------ | ---------- |:-----------------------------------------------------:|:---------------------------------------------------------------------:|
| id     | numeric    | The id of the Gist.                                   | Required.                                                             |
| file   | string     | The file name.                                        | Optional. Using no file name will return all files.                   |
| synhi  | true/false | Highlight the gist.                                   | Optional, but requires Syntaxhighlighter Evolved Plugin for WordPress |
| wrap   | true/false | Wrap the highlighted gist with .syntax-wrapper class. | Optional, but requires synhi to be true.                              |