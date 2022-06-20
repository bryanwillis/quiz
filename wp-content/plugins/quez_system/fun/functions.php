<?php
include_once PLUGIN_DIR_PATH . '../inc/enqu.php';
/*
=============
 function for
Questions
--------------------
*/





//function user_questions(){
//    global $wpdb;
//    $questions = $wpdb->prefix . "questions";
//    $name = $_POST["qs_name"];
//    $descr = $_POST["dsc"];
//    $sts = $_POST["status"];
//    
//    $insert = $wpdb->insert($questions, array(
//            'qs_name' => $name,
//            'status'  => $sts
//            ),
//            array(
//                    '%s', 
//                    '%d'
//            ) 
//        );
//    if($insert){
//        echo "successfull";
//    }
//    else{
//        echo "try again";
//    }
//}