# ojs-extras

Preprocessing scripts, custom plugins, and hacks for OJS at the Electronic Text Centre, UNB Libraries. 

## Installation

Copy _ojs-extras/etcscripts_ to _OJS_INSTALL_DIR_.  Copy the CONTENTS of _ojs-extras/plugins_ INTO the _plugins/_ folder provided by OJS, i.e., _OJS_INSTALL_DIR/plugins_.

### Plugins

_ojs-extras/plugins/blocks/extendedHelp_

Block plugin provides additional help options.

_ojs-extras/plugins/themes/etcDefault_ 

ETC-flavoured OJS theme.

### ETC Scripts

_ojs-extras/etcscripts/preprocess_

Data munging scripts used by journal production staff

_ojs-extras/etcscripts/deleteSubmissions_ 

Bookmarklet hack to work around the lack of a batch submission deletion option in the OJS web interface. Copy the contents of _ojs-extras/etcscripts/deleteSubmissions/bookmarklet.js_ into the location field of a new bookmark.
