<?php

/* 
 * Template Name: Pay
 */
global $wpdb;
if(is_user_logged_in()){
    $c_user = get_current_user_id();
    $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
    $row = $wpdb->get_row("SELECT * FROM $table_quiz_authentication WHERE user_id = '$c_user' AND status=1");
    if($row){
        if($row->status==1){

        }
    }
    $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
        $wpdb->insert(
        $table_quiz_authentication, 
        array(
            'user_id' => $c_user,
            'status'=> 1
        )
    );
}else{
    echo '<h1>Please Login</h1>';
}


