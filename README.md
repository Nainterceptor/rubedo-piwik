rubedo-piwik
============

# How to install

* Add "nainterceptor/piwik": "~2.2" to composer.extensions.json (Only on rubedo's next branch, because commit 4d8079bf65 is needed)
* Run ./rubedo.sh
* rm cache/config/extensions.array.php
* copy extensions/nainterceptor/piwik/config/module.php.dist to module.php
* Edit with your trackerURL and your sites IDs.

# Features
* Do not track Backoffice