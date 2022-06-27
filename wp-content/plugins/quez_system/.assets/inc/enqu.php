<?php
add_action('admin_enqueue_scripts', 'quez_admin_assets');
function quez_admin_assets(){
    wp_enqueue_style( 'style',  PLUGIN_URL . '/quez_system/assets/css/style.css', __FILE__ , false );
    wp_enqueue_style( 'fontcli',  PLUGIN_URL . '/quez_system/assets/Calibri/fonts.css', __FILE__ , false );
}

add_action( 'init', 'quiz_script_enqueuer' );
function quiz_script_enqueuer() {
   wp_enqueue_script( 'my_script', PLUGIN_URL . '/quez_system/assets/js/custom.js', __FILE__, array('jquery'));
   wp_localize_script( 'my_script', 'Ajaxquizs', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ))); 
   wp_enqueue_script( 'my_script');
   
}