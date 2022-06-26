<?php
echo "<center><h1>QUIZ</h1></center>";

class user_quiz{
    function my_quiz_table(){
        ?>
    <div class="wrap">
         <table class="wp-list-table widefat fixed striped posts">
             
            <tr>
                <th><b>ID</b></th>
                <th><b>User</b></th>
                <th><b>Questions</b></th>
                <th><b>Selected Ans</b></th>
                <th><b>Result</b></th>
            </tr>
            
            
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . "user_quiz";
            $query =  "SELECT * FROM $table_name ORDER BY id DESC" ;
            $get_data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC" );
//            $question_answers = $wpdb->get_results( "SELECT * FROM $table_question_answers WHERE question_id=$data->id" );
//            $correct = 0;
//            foreach($question_answers as $question_answer){
//                $table_user_quiz = $wpdb->prefix . "user_quiz";
//                $user_quiz = $wpdb->get_row("SELECT * FROM $table_user_quiz WHERE question_ans_id = $question_answer->id AND user_id=$c_user ORDER BY id DESC");
//                
//                if($user_quiz){
//                    if($user_quiz->answer==1 && $question_answer->ane_true==1){
//                        $correct = $correct+1;
//                    }else if($user_quiz->answer==2 && $question_answer->ane_true==0){
//                        $correct = $correct+1;
//                    }
//                }else{
//                    echo 'not found';
//                }
//            }
            foreach ($get_data as $data){
                ?>
            <tr>
                <th><?php echo $data->id; ?></th>
                <?php
                $user_info = get_userdata($data->user_id);
                $first_name = $user_info->first_name;
                $last_name = $user_info->last_name;
                ?>
                <th><?php echo $first_name.' '.$last_name;  ?></th>
                <?php
                $table_questions = $wpdb->prefix . "questions";
                $question = $wpdb->get_row("SELECT * FROM $table_questions WHERE id = $data->question_id");
                ?>
                <th><?php echo $question->name; ?></th>
                <?php
                $table_question_answers = $wpdb->prefix . "question_answers";
                $question_ans = $wpdb->get_row("SELECT * FROM $table_question_answers WHERE id = $data->question_ans_id");
                $table_user_quiz = $wpdb->prefix . "user_quiz";
                $user_quiz = $wpdb->get_row("SELECT * FROM $table_user_quiz WHERE question_ans_id = $question_ans->id ORDER BY id DESC");
                $correct = 'False';
                if($user_quiz){
                    if($user_quiz->answer==1 && $question_ans->ane_true==1){
                        $correct = 'True';
                    }else if($user_quiz->answer==2 && $question_ans->ane_true==0){
                        $correct = 'True';
                    }
                }
                if($question_ans->ane_true==0){
                    $question_ans_true = $question_ans->ans_one;
                    
                }else{
                    $question_ans_true = $question_ans->ans_two;
                }
                ?>
                <th><?php echo $question_ans_true; ?></th>
                
                <th><?php echo $correct; ?></th>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
      <?php
    }
}
$quiz_obj = new user_quiz();
$quiz_obj->my_quiz_table();

