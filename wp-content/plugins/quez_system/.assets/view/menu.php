<?php 
add_action( 'admin_menu', 'my_custom_menu' );
function my_custom_menu(){
    $page_title = 'quezsystem';
    $menu_title = 'ICBI';
    $capability = 'manage_options';
    $menu_slug  = 'user-authentication';
    $function   = 'my_new_user_auth';
    $icon_url   = 'dashicons-welcome-learn-more';
    $position   = 11;

    add_menu_page(
                $page_title,
                $menu_title, 
                $capability, 
                $menu_slug, 
                $function, 
                $icon_url, 
                $position 
    );
    
    /*
------
 SUB MENU
=============== 
*/
    add_submenu_page(
        "user-authentication",    // PARENT SLUG
        "User Authentication",    // PAGE TITLE
        "User Authentication",    // MENU TITLE
        "manage_options",         // CAPABILITY = USER_LEVEL ACCESS
        "user-authentication",    // SUB MENU SLUG
        "my_new_user_auth"        // CALL BACK FUNCTION
    );
    
    add_submenu_page(
        "user-authentication",     // PARENT SLUG
        "Questions",               // PAGE TITLE
        "Questions",               // MENU TITLE
        "manage_options",          // CAPABILITY = USER_LEVEL ACCESS
        "questions",               // SUB MENU SLUG
        "my_new_user_questions"    // CALL BACK FUNCTION
    );
    add_submenu_page(
        "user-authentication",     // PARENT SLUG
        "User ICBI",               // PAGE TITLE
        "User ICBI",               // MENU TITLE
        "manage_options",          // CAPABILITY = USER_LEVEL ACCESS
        "user-quiz",               // SUB MENU SLUG
        "my_new_user_user_quiz"    // CALL BACK FUNCTION
    );
    
    add_submenu_page(
        "country-answer",     // PARENT SLUG
        "Country Answer",               // PAGE TITLE
        "Country Answer",               // MENU TITLE
        "manage_options",          // CAPABILITY = USER_LEVEL ACCESS
        "country-answer",               // SUB MENU SLUG
        "my_new_country_answer"    // CALL BACK FUNCTION
    );
}

function my_new_user_auth(){
    include 'user_auth.php';
}
function my_new_user_questions(){
    include 'questions.php';
}
function my_new_user_user_quiz(){
    include 'quiz.php';
}
function my_new_country_answer(){
    include 'country_answer.php';
}