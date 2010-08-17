# ojs-extras

Preprocessing scripts, custom plugins, and hacks for OJS at the Electronic Text Centre, UNB Libraries. 

## Installation

Copy `ojs-extras/etcscripts` to `OJS_INSTALL_DIR`.  Copy the CONTENTS of `ojs-extras/plugins` INTO the `plugins/` folder provided by OJS, i.e., `OJS_INSTALL_DIR/plugins`.

### Plugins

`ojs-extras/plugins/blocks/extendedHelp`

Block plugin provides additional help options.

`ojs-extras/plugins/themes/etcDefault` 

ETC-flavoured OJS theme.

### ETC Scripts

`ojs-extras/etcscripts/preprocess`

Data munging scripts used by journal production staff.

`ojs-extras/etcscripts/deleteSubmissions` 

Bookmarklet hack to work around the lack of a batch submission deletion option in the OJS web interface. Copy the contents of `ojs-extras/etcscripts/deleteSubmissions/bookmarklet.js` into the location field of a new bookmark.
