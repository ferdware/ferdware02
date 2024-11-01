<?php
/*
Name: Ferdware First Plugin
Slug: ferdware-plugin
Category: tools
Url: http://www.vvveb.com
Description: Insert footer and header scripts such as analytics or widgets.
Thumb: insert-scripts.svg
Author: ferdware
Version: 0.1
Author url: http://www.vvveb.com
Settings: /admin/?module=plugins/insert-scripts/settings
*/

use Vvveb\System\Event;

if (! defined('V_VERSION')) {
	die('Invalid request!');
}
