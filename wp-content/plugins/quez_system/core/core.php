<?php
define("PLUGIN_DIR_PATH", plugin_dir_path(__FILE__)); 

// below path this path is use for js css images also PLUGIN_URLhttp://whatsup.local/wp-content/plugins 
define("PLUGIN_URL", plugins_url());  

include_once PLUGIN_DIR_PATH . '../config/db.php';
include_once PLUGIN_DIR_PATH . '../view/menu.php';
// include_once PLUGIN_DIR_PATH . '../pages/my-exame.php';
include_once PLUGIN_DIR_PATH . '../pages/my-exame_1.php';
include_once PLUGIN_DIR_PATH . '../fun/functions.php';