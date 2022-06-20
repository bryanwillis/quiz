<?php
// below path this path is use for php files also F:\wamp64\www\whatsup\wp-content\plugins\quez system/  
define("PLUGIN_DIR_PATH", plugin_dir_path(__FILE__)); 

// below path this path is use for js css images also PLUGIN_URLhttp://whatsup.local/wp-content/plugins 
define("PLUGIN_URL", plugins_url());  

// this pathe show current file also  F:\wamp64\www\whatsup\wp-content\plugins\quez system\config\db.php 
// echo __FILE__;

include_once PLUGIN_DIR_PATH . '../config/db.php';
include_once PLUGIN_DIR_PATH . '../view/menu.php';
include_once PLUGIN_DIR_PATH . '../pages/my-exame.php';
include_once PLUGIN_DIR_PATH . '../fun/functions.php';