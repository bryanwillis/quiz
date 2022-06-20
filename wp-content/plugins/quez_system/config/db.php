<?php
class basicPlugin_database{
    public function __construct() {
        add_action( 'init', array( &$this, 'basic_plugin_db' ) );

    }
    
    public function basic_plugin_db(){
    global $wpdb;
     
    //create the name of the table including the wordpress prefix (wp_ etc)
     $quiz_authentication = $wpdb->prefix . "quiz_authentication";

    //$wpdb->show_errors(); 
     
    //check if there are any tables of that name already
    if($wpdb->get_var("show tables like 'quiz_authentication'") !== $quiz_authentication) 
    {
        //create your sql
        $sql =  "CREATE TABLE `$quiz_authentication` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `status` int(11) NOT NULL,
                PRIMARY KEY (`id`)
               ) ENGINE=MyISAM DEFAULT CHARSET=latin1";
    }  

    //include the wordpress db functions
     require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
           dbDelta($sql);
     
    //register the new table with the wpdb object
    if (!isset($wpdb->stats)) 
    {
        $wpdb->stats = $quiz_authentication; 
        //add the shortcut so you can use $wpdb->stats
        $wpdb->tables[] = str_replace($wpdb->prefix, '', $quiz_authentication); 
    }
    
    
    
    /*==================
     *      TABLE QUESTIONS
       ====================*/
    //create the name of the table including the wordpress prefix (wp_ etc)
     $questions = $wpdb->prefix . "questions";

    //$wpdb->show_errors(); 
     
    //check if there are any tables of that name already
    if($wpdb->get_var("show tables like 'quiz_authentication'") !== $questions) 
    {
        //create your sql
        $sql =  "CREATE TABLE `$questions` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(255) NOT NULL,
                    `description` text NOT NULL,
                    `status` int(11) NOT NULL,
                    PRIMARY KEY (`id`)
                   ) ENGINE=MyISAM DEFAULT CHARSET=latin1";
    }  

    //include the wordpress db functions
     require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
           dbDelta($sql);
     
    //register the new table with the wpdb object
    if (!isset($wpdb->stats)) 
    {
        $wpdb->stats = $questions; 
        //add the shortcut so you can use $wpdb->stats
        $wpdb->tables[] = str_replace($wpdb->prefix, '', $questions); 
    }
    
    
    /*
     ===================
     *   question_answers
     * ===================
    */
    //create the name of the table including the wordpress prefix (wp_ etc)
     $question_answers = $wpdb->prefix . "question_answers";

    //$wpdb->show_errors(); 
     
    //check if there are any tables of that name already
    if($wpdb->get_var("show tables like '$question_answers'") !== $question_answers) 
    {
        //create your sql
        $sql =  "CREATE TABLE `$question_answers` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `question_id` int(11) NOT NULL,
                    `ans_one` varchar(255) NOT NULL,
                    `ans_two` varchar(255) NOT NULL,
                    `ane_true` int(11) NOT NULL,
                    PRIMARY KEY (`id`)
                   ) ENGINE=MyISAM DEFAULT CHARSET=latin1";
    }  

    //include the wordpress db functions
     require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
           dbDelta($sql);
     
    //register the new table with the wpdb object
    if (!isset($wpdb->stats)) 
    {
        $wpdb->stats = $question_answers; 
        //add the shortcut so you can use $wpdb->stats
        $wpdb->tables[] = str_replace($wpdb->prefix, '', $question_answers); 
    }
    
    
    /*
     ===================
     *   user_quiz
     * ===================
    */
    //create the name of the table including the wordpress prefix (wp_ etc)
     $user_quiz = $wpdb->prefix . "user_quiz";

    //$wpdb->show_errors(); 
     
    //check if there are any tables of that name already
    if($wpdb->get_var("show tables like '$user_quiz'") !== $user_quiz) 
    {
        //create your sql
        $sql =  "CREATE TABLE `$user_quiz` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int(11) NOT NULL,
                    `question_id` int(11) NOT NULL,
                    `question_ans_id` int(11) NOT NULL,
                    `answer` int(11) NOT NULL,
                    PRIMARY KEY (`id`)
                   ) ENGINE=MyISAM DEFAULT CHARSET=latin1";
    }  

    //include the wordpress db functions
     require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
           dbDelta($sql);
     
    //register the new table with the wpdb object
    if (!isset($wpdb->stats)) 
    {
        $wpdb->stats = $user_quiz; 
        //add the shortcut so you can use $wpdb->stats
        $wpdb->tables[] = str_replace($wpdb->prefix, '', $user_quiz); 
    }
    
  }
}
new basicPlugin_database();