<style>
    .request_button {
        font-size: 16px;
        background-color: #0a3959;
        color: #fff !important;
        min-width: 100px;
        height: 40px;
        border: 1px solid #0a3959;
        border-radius: 4px;
        line-height: 40px;
        padding: 0 20px;
    }
    .request_exam a {
        display: inline-block;
        font-size: 16px;
        color: #000;
        text-decoration: underline;
    }
    .tag_line {
        font-size: 12px;
        color: #888;
    }
    .myquestios_ans {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
    }
    .ques_ans_box {
        margin-top: 30px;
    }
    .myquestios_ans .ans_inradion {
        width: 100%;
        text-align: left;
        margin: 6px 0px;
        color: #4f4f4f;
        padding: 0 5% 0 0;
    }
    .quiz_infornt .ques_ans_box {
        display: none;
    }
    .quiz_infornt .ques_ans_box.active {
        display: block;
    }
    .main_box.quiz_page {

        align-items: center;
        /*background: url('/wp-content/plugins/quez_system/assets/image/bg.png');*/
/*        background-repeat: no-repeat;
        background-size: auto;*/

    }
    .welcome_note_section h2 {
        margin: 0 0 10px 0;
    }
    .welcome_note_section p {
        margin: 0 0 25px 0;
    }
    .welcome_note_section ul {
        margin: 0;
        padding: 0;
    }
.welcome_note_section ul li {
    list-style: none;
    display: inline-block;
}
.welcome_note_section ul li {
    list-style: none;
    display: inline-block;
}
</style>
<div class="quiz_wrapper">
    
    <div class="quiz_container">
<?php
session_start();
function shortCodeMy_exame_updated(){  ?>
<?php
if(is_user_logged_in()){
    $c_user = get_current_user_id();
     global $wpdb;
if(isset($_GET['start'])){
    $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
    $row = $wpdb->get_row("SELECT * FROM $table_quiz_authentication WHERE user_id = '$c_user'");
    if($row){
        if($row->status==1){
            $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
            $wpdb->update(
            $table_quiz_authentication, 
                array(
                    'user_id' => $c_user,
                    'status'=> 1
                ),
                array( 'id' => $row->id )
            );
        }
    }else{
        $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
            $wpdb->insert(
            $table_quiz_authentication, 
            array(
                'user_id' => $c_user,
                'status'=> 1
            )
        );
    }
                            unset($_SESSION['quiz']);
    
    ?>
        
        <script>
            window.location.replace ("http://assessment.globalcoachcenter.com/assessment/?request=yes");
        </script>
        <?php
}else if(isset($_GET['print'])){
    ?>
        <style>
            #advanced-pdf-generator {
                text-align: center;
            }
            .inner_pages_content h2{
                text-align: center;
            }
        </style>
        <?php
    $table_check_user_quiz = $wpdb->prefix . "user_quiz";
        $check_user_quiz = $wpdb->get_row("SELECT * FROM $table_check_user_quiz WHERE user_id=$c_user");
        if($check_user_quiz){
            echo do_shortcode('[advanced-pdf-generator]');
        }else{
            ?>
        <h1>Sorry! Please Give Exam</h1>
        <?php
        }
}else if(isset($_GET['userresult'])){
    ?>
        <style>
            #advanced-pdf-generator {
                text-align: center;
            }
            .inner_pages_content h2{
                text-align: center;
            }
        </style>
        <?php
        $userresult = $_GET['userresult'];
    $table_check_user_quiz = $wpdb->prefix . "user_quiz";
        $check_user_quiz = $wpdb->get_row("SELECT * FROM $table_check_user_quiz WHERE user_id=$userresult");
        if($check_user_quiz){
            echo do_shortcode('[advanced-pdf-generator]');
        }else{
            ?>
        <h1>Sorry! You Are not allowed to see this page.</h1>
        <?php
        }
}else if(isset($_GET['result'])){
    ?>
        <style>
            .main_box.quiz_page {
                display: block;
            }
        </style>
        <?php
    if(isset($_SESSION['quiz'])){
        global $wpdb;
        $c_user = get_current_user_id();
        foreach($_SESSION['quiz'] as $quizs){
            foreach($quizs as $question=>$ans){
                $check_question_ans = $wpdb->prefix . "question_answers";
                $check_question_ans = $wpdb->get_row("SELECT * FROM $check_question_ans WHERE id = '$question'");
                if($check_question_ans){
                    if($ans==1){
                        $answer = 1;
                    }else{
                        $answer = 0;
                    }
                    $table_user_quiz = $wpdb->prefix . "user_quiz";
                        $wpdb->insert(
                        $table_user_quiz, 
                        array(
                            'user_id' => $c_user,
                            'question_id' => $check_question_ans->question_id,
                            'question_ans_id' => $check_question_ans->id,
                            'answer'=> $answer
                        )
                    );
                }
            }
//            die();
//            foreach($quiz as $question=>$ans){
//                foreach($ans as $key=>$value){
//                    $question_ans_id = str_replace('id_', '', $key);
//                    if($value==1){
//                        $answer = 1;
//                    }else{
//                        $answer = 0;
//                    }
//                    $table_user_quiz = $wpdb->prefix . "user_quiz";
//                        $wpdb->insert(
//                        $table_user_quiz, 
//                        array(
//                            'user_id' => $c_user,
//                            'question_id' => $question,
//                            'question_ans_id' => $question_ans_id,
//                            'answer'=> $answer
//                        )
//                    );
//                }
//            }
        }
        unset($_SESSION['quiz']);
        $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
        $row = $wpdb->get_row("SELECT * FROM $table_quiz_authentication WHERE user_id = '$c_user'");
        if($row){
            if($row->status==1){
                $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
                    $wpdb->update(
                    $table_quiz_authentication, 
                        array(
                            'user_id' => $c_user,
                            'status'=> 0
                        ),
                        array( 'id' => $row->id )
                     );
            }
        }
        ?>
        <script>
            window.location.replace ("http://assessment.globalcoachcenter.com/assessment/?result=yes");
        </script>
        <?php
    }else{
        $table_check_user_quiz = $wpdb->prefix . "user_quiz";
        $check_user_quiz = $wpdb->get_row("SELECT * FROM $table_check_user_quiz WHERE user_id=$c_user");

        if($check_user_quiz){
            $user_info = get_userdata($c_user);
            $first_name = $user_info->first_name;
            $last_name = $user_info->last_name;
            ?>
        <h1 class="result_title">ICBI Summary for <?php echo $user_info->first_name.' '.$user_info->last_name; ?></h1>
        <?php
        global $wpdb;
        $table_questions = $wpdb->prefix . "questions";
        $query =  "SELECT * FROM $table_questions ORDER BY id DESC" ;
        $get_data = $wpdb->get_results( "SELECT * FROM $table_questions ORDER BY id ASC" );

        foreach ($get_data as $k=>$data){
            $k=$k+1;
            $c_user = get_current_user_id();
            $table_question_answers = $wpdb->prefix . "question_answers";
            $query =  "SELECT * FROM $table_question_answers ORDER BY id DESC" ;
            $question_answers = $wpdb->get_results( "SELECT * FROM $table_question_answers WHERE question_id=$data->id" );
            $correct = 0;
            foreach($question_answers as $question_answer){
                $table_user_quiz = $wpdb->prefix . "user_quiz";
                $user_quiz = $wpdb->get_row("SELECT * FROM $table_user_quiz WHERE question_ans_id = $question_answer->id AND user_id=$c_user ORDER BY id DESC");
                if($user_quiz){
                    if($user_quiz->answer==1 && $question_answer->ane_true==1){
                        $correct = $correct+1;
                    }else if($user_quiz->answer==0 && $question_answer->ane_true==0){
                        $correct = $correct+1;
                    }
                }else{
//                    echo 'not found';
                }
            }
            $total_0 = '';
            $total_1 = '';
            $total_2 = '';
            $total_3 = '';
            $total_4 = '';
            $total_5 = '';
            if($correct==0){
                $total_0 = 'active';
            }else if($correct==1){
                $total_1 = 'active';
            }else if($correct==2){
                $total_2 = 'active';
            }else if($correct==3){
                $total_3 = 'active';
            }else if($correct==4){
                $total_4 = 'active';
            }else if($correct==5){
                $total_5 = 'active';
            }else{
                $total_0 = 'active';
            }
            ?>
        <div class="question_ans_box">
            <div class="question_no">
                <h1><strong><?php echo $k; ?>. </strong><?php echo $data->veriable; ?></h1>
            </div>
            <div class="question_results">
                <ul>
                    <li class=""><?php echo $data->first_text; ?></li>
                    <li class="<?php echo $total_0; ?>">0</li>
                    <li class="<?php echo $total_1; ?>">1</li>
                    <li class="<?php echo $total_2; ?>">2</li>
                    <li class="<?php echo $total_3; ?>">3</li>
                    <li class="<?php echo $total_4; ?>">4</li>
                    <li class="<?php echo $total_5; ?>">5</li>
                    <li class=""><?php echo $data->last_text; ?></li>
                </ul>
            </div>
        </div>
        <?php
        }
        ?>
        <p class="c_compare">Check the countries you are interested in comparing yourself with from the list below. You can choose as many as you like</p>
        <form class="country_compare" method="get" name="compare" action="">
            <input type="hidden" name="print" value="yes">
            <ul>
            <li><input type="checkbox" name="country[]" value="Argentina">Argentina</li>
            <li><input type="checkbox" name="country[]" value="Australia">Australia</li>
            <li><input type="checkbox" name="country[]" value="China">China</li>
            <li><input type="checkbox" name="country[]" value="France">France</li>
            <li><input type="checkbox" name="country[]" value="Germany">Germany</li>
            <li><input type="checkbox" name="country[]" value="Honduras">Honduras</li>
            <li><input type="checkbox" name="country[]" value="India">India</li>
            <li><input type="checkbox" name="country[]" value="Israel">Israel</li>
            <li><input type="checkbox" name="country[]" value="Italy">Italy</li>
            <li><input type="checkbox" name="country[]" value="Japan">Japan</li>
            <li><input type="checkbox" name="country[]" value="Kenya">Kenya</li>
            <li><input type="checkbox" name="country[]" value="Malaysia">Malaysia</li>
            <li><input type="checkbox" name="country[]" value="Mexico">Mexico</li>
            <li><input type="checkbox" name="country[]" value="Netherlands">Netherlands</li>
            <li><input type="checkbox" name="country[]" value="Philippines">Philippines</li>
            <li><input type="checkbox" name="country[]" value="Poland">Poland</li>
            <li><input type="checkbox" name="country[]" value="Qatar">Qatar</li>
            <li><input type="checkbox" name="country[]" value="Russia">Russia</li>
            <li><input type="checkbox" name="country[]" value="Saudi Arabia">Saudi Arabia</li>
            <li><input type="checkbox" name="country[]" value="Senegal">Senegal</li>
            <li><input type="checkbox" name="country[]" value="Singapore">Singapore</li>
            <li><input type="checkbox" name="country[]" value="Spain">Spain</li>
            <li><input type="checkbox" name="country[]" value="Switzerland">Switzerland</li>
            <li><input type="checkbox" name="country[]" value="Turkey">Turkey</li>
            <li><input type="checkbox" name="country[]" value="United Arab Emirates">UAE</li>
            <li><input type="checkbox" name="country[]" value="UK">UK</li>
            <li><input type="checkbox" name="country[]" value="USA">USA</li>
            </ul>
            
            <button type="submit">Submit</button>
        </form>
        <h2>submit to view or print your results</h2>
        <?php
    }else{
        ?>
        <h1>Sorry! Please give Assessment</h1>
        <?php
        }
    }
} else if(isset($_GET['request'])){
    
                
                $c_user = get_current_user_id();
                global $wpdb;
                $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
                $row = $wpdb->get_row("SELECT * FROM $table_quiz_authentication WHERE user_id = '$c_user' AND status=1");
                if($row){
                    if($row->status==1){
                        

                        ?>
            <div class="quiz_infornt">
                <p class="z_copy_right">The Global Coach Center – copyright, 2018</p>
                <?php
                for($page=1; $page<=17; $page++){
                    $page_active = '';
                    if(isset($_GET['question'])){
                        if($_GET['question']==$page){
                            $get_question = $_GET['question'];
                            $page_active = 'active';
                        }
                    }else{
                        $get_question = 1;
                        if($page==1){
                            $page_active = 'active';
                        }
                    }
                    ?>
                <div class="ques_ans_box <?php echo $page_active; ?>" id="page_<?php echo $page; ?>">
                    <div class="progress_bar">
                        <ul>
                            <?php
                            for($progress=1; $progress<=17; $progress++){
                                $progress_active = 'progress_active';
                                if($get_question<=$progress){
                                    $progress_active = '';
                                }
                                if($progress==17){
                                   ?>
                            <li class="<?php echo $progress_active; ?>"><a href="/assessment/?request=yes&question=<?php  echo $progress; ?>"></a></li>
                            <?php  
                                }else{
                            ?>
                            <li class="<?php echo $progress_active; ?>"><a href="/assessment/?request=yes&question=<?php  echo $progress; ?>"><?php // echo $progress; ?></a></li>
                            <?php 
                                }
                                } ?>
                        </ul>
                    </div>
                    <?php
                    if($get_question!=17){
                    ?>
                    <div class="tag_line">
                        Please choose one of the two answers for each question. There is no wrong or right answer.
                        Choose the answer that applies best for you. Think about the work environment, not your
                        personal life. Usually what comes up first in your mind is the best answer.
                    </div>
                <?php
                    }
                global $wpdb;
                if($page==1 && $get_question==1){

//                    for first question
                    $question_id = 1;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 2;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 3;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 4;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 5;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                }else if($page==2 && $get_question==2){

//                    for first question
                    $question_id = 6;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 7;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 8;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 9;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 10;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=1">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==3 && $get_question==3){
                    
//                    for first question
                    $question_id = 11;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 12;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 13;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 14;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 15;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=2">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==4 && $get_question==4){
                    
//                    for first question
                    $question_id = 16;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==1){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 1;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 2;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 3;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 4;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=3">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==5 && $get_question==5){
                    
//                    for first question
                    $question_id = 5;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 6;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 7;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 8;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 9;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=4">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==6 && $get_question==6){
                    
//                    for first question
                    $question_id = 10;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 11;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 12;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 13;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 14;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=5">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==7 && $get_question==7){
                    
//                    for first question
                    $question_id = 15;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 16;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==2){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 1;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 2;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 3;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=6">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==8 && $get_question==8){
                    
//                    for first question
                    $question_id = 4;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 5;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 6;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 7;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 8;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=7">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==9 && $get_question==9){
                    
//                    for first question
                    $question_id = 9;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 10;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 11;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 12;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 13;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=8">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==10 && $get_question==10){
                    
//                    for first question
                    $question_id = 14;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 15;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 16;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==3){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 1;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 2;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=9">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==11 && $get_question==11){
                    
//                    for first question
                    $question_id = 3;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 4;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 5;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 6;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 7;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=10">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==12 && $get_question==12){
                    
//                    for first question
                    $question_id = 8;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 9;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 10;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 11;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 12;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=11">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==13 && $get_question==13){
                    
//                    for first question
                    $question_id = 13;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 14;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 15;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 16;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==4){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 1;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=12">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==14 && $get_question==14){
                    
//                    for first question
                    $question_id = 2;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 3;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 4;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 5;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 6;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=13">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==15 && $get_question==15){
                    
//                    for first question
                    $question_id = 7;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 8;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 9;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 10;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 11;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=14">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==16 && $get_question==16){
                    
//                    for first question
                    $question_id = 12;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>1)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }
                        }
                    }
//                    second question
                    $question_id = 13;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>2)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    third question
                    $question_id = 14;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>3)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    forth question
                    $question_id = 15;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>4)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
//                    fifth question
                    $question_id = 16;
                    $table_name = $wpdb->prefix . "questions";
                    $get_data = $wpdb->get_row( "SELECT * FROM $table_name WHERE id=$question_id" );
                    if($get_data){
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n=>$g_ans){
                           $n = $n+1;
                           if($n==5){
                               ?>
                            <h4><?php /*<strong>5)</strong> */ ?> <?php echo $get_data->name; ?></h4>
                            <div class="myquestios_ans">
                                <div class="ans_inradion">
                                    <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                                </div>

                                <div class="ans_inradion">
                                    <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                                </div>
                            </div>
                            <?php
                           }   
                        }
                    }
                    
                    echo '<a class="back_button" href="/assessment/?request=yes&question=15">Back</a>';
                    echo '<input type="submit" value="NEXT" class="quiz_Pagnxt">';
                
                }else if($page==17 && $get_question==17){
                    ?>
                            <h1>Thank you!</h1>
                            <p style="margin: 10px 0;">submit to view or print your results</p>
                            <a href="?result=submit" class="submit_assessment">Submit Assessment</a>
                        
                            <?php
                }
                ?>
                </div>
                <?php
                }
                ?>
                
        </div>
        <style>
            @media only screen and (max-width: 480px) {
                .main_box.quiz_page {
                    display: block;
                }
            }
        </style>
<?php
                    }else{
//                        $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
//                        $wpdb->update(
//                        $table_quiz_authentication, 
//                            array(
//                                'user_id' => $c_user,
//                                'status'=> 1
//                            ),
//                            array( 'id' => $row->id )
//                        );
                        ?>
        <div class="request_exam">
            <div class="welcome_note_section">
                <h2>Welcome to the ICBI™, the Individual Cultural Blueprint Indicator.</h2>
                <h3>Your request to access the assessment has been received. You will get notified when you are approved</h3>
            </div>
            
        </div>
        <script>//
//            window.location.replace ("http://assessment.globalcoachcenter.com/assessment/?request=yes");
//        </script>
        <?php
//                        echo do_shortcode('[wp_paypal_payment]');
                        
                    }
                }else{
//                    $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
//                        $wpdb->insert(
//                        $table_quiz_authentication, 
//                        array(
//                            'user_id' => $c_user,
//                            'status'=> 1
//                        )
//                    );
                        ?>
        <div class="request_exam">
            <div class="welcome_note_section">
                <h2>Welcome to the ICBI™, the Individual Cultural Blueprint Indicator.</h2>
                <h3>Your request to access the assessment has been received. You will get notified when you are approved</h3>
            </div>
        </div>
        <script>
//            window.location.replace ("http://assessment.globalcoachcenter.com/assessment/?request=yes");
        </script>
        <?php
//                    echo do_shortcode('[wp_paypal_payment]');
                }
}else{
    ?>
        <div class="request_exam">
            <div class="welcome_note_section">
            <h2>Welcome to the ICBI™, the Individual Cultural Blueprint Indicator.</h2>
            <p>
            The ICBI™ is a behavior, habit, and belief-based assessment that empowers you to learn your cultural preferences and compare them with the median cultural preferences of another specified culture. This may mean comparing your preferences with those of the country where you’ll be relocating, the diverse cultures of your colleagues and clients, or the cultures with which your company interacts the most – either in person or virtually. 
            </p>
            <p>
            The ICBI™ takes about 30 minutes to complete and provides you with your personalized “Cultural Preferences and Gaps” report. It allows you to identify the most relevant cultural gaps affecting your life and work and help evaluate how to address the resulting challenges. 
            </p>
            <p>
            There is no right or wrong in completing this assessment. There is no value assigned to the scoring, for example a score of 0 or 5 is not better or worse. 
            </p>
            <p>
            Please dedicate 30 minutes of uninterrupted time to complete the assessment. Your results will not be saved, so please do not close the browser as you will have to start again from scratch if you do. Once completed, click ‘submit’ and you will be able to generate a PDF report. You will also get an email notification. You have the option to compare your results with 25 cultures of interest after you click ‘submit’. 
            </p>
            <p>If you have any questions, please contact <a href="mailto:info@culturalbusinessconsulting.com">info@culturalbusinessconsulting.com</a>.</p>
            <ul>
                <li><a class="request_button" href="?request=yes">Complete the ICBI</a></li>
                <li>  </li>
                <li>
                    <a href="/account">View Account</a>
                </li>
            </ul>
        </div>
            
        </div>
    <?php
}
}else{
    ?>
        <div class="request_exam">
            <div class="welcome_note_section">
            <h2>Welcome to the ICBI™, the Individual Cultural Blueprint Indicator.</h2>
            <p>
            The ICBI™ is a behavior, habit, and belief-based assessment that empowers you to learn your cultural preferences and compare them with the median cultural preferences of another specified culture. This may mean comparing your preferences with those of the country where you’ll be relocating, the diverse cultures of your colleagues and clients, or the cultures with which your company interacts the most – either in person or virtually. 
            </p>
            <p>
            The ICBI™ takes about 30 minutes to complete and provides you with your personalized “Cultural Preferences and Gaps” report. It allows you to identify the most relevant cultural gaps affecting your life and work and help evaluate how to address the resulting challenges. 
            </p>
            <p>
            There is no right or wrong in completing this assessment. There is no value assigned to the scoring, for example a score of 0 or 5 is not better or worse. 
            </p>
            <p>
            Please dedicate 30 minutes of uninterrupted time to complete the assessment. Your results will not be saved, so please do not close the browser as you will have to start again from scratch if you do. Once completed, click ‘submit’ and you will be able to generate a PDF report. You will also get an email notification. You have the option to compare your results with 25 cultures of interest after you click ‘submit’. 
            </p>
            <ul>
                <li>Please </li>
                <li>
                    <a href="/login">Login</a>
                </li>
                <li> or </li>
                <li>
                    <a href="/register">Register</a>
                </li>
                <li>to Complete the ICBI™</li>
            </ul>
<!--            <ul>
                <li><a class="request_button" href="?request=yes">Complete the ICBI</a></li>
            </ul>-->
        </div>
            
        </div>
    <?php
}
?>
        </div>
    <div class="loding_script">
        <img src="http://ela-beauty.com/themes/defox/images/ajax.gif">
    </div>
</div>

<script type="text/javascript">
   jQuery(document).ready(function(){
       jQuery(document).on('click','.quiz_Pagnxt',function(e){
            var question = jQuery(this).parent().attr('id');
            var next_question = jQuery("#"+question).next().attr('id');
            var next_question_id = next_question.replace("page_", "");
            var first_question = jQuery('input[name="select_ans_1"]').attr('id');
            var first_question_value = jQuery('#'+first_question+':checked').attr('value');
            var first_question_id = first_question.replace("id_", "");
            
            var second_question = jQuery('input[name="select_ans_2"]').attr('id');
            var second_question_value = jQuery('#'+second_question+':checked').attr('value');
            var second_question_id = second_question.replace("id_", "");
            
            var third_question = jQuery('input[name="select_ans_3"]').attr('id');
            var third_question_value = jQuery('#'+third_question+':checked').attr('value');
            var third_question_id = third_question.replace("id_", "");
            
            var fourth_question = jQuery('input[name="select_ans_4"]').attr('id');
            var fourth_question_value = jQuery('#'+fourth_question+':checked').attr('value');
            var fourth_question_id = fourth_question.replace("id_", "");
            
            var fifth_question = jQuery('input[name="select_ans_5"]').attr('id');
            var fifth_question_value = jQuery('#'+fifth_question+':checked').attr('value');
            var fifth_question_id = fifth_question.replace("id_", "");
            
//            return false;
//            var id_1st_ans = jQuery('input[name="select_ans_1"]:checked').attr('value');   
//            
//            var select_ans_1 = document.querySelector('#'+question+' input[name="select_ans_1"]:checked').value;
//            
//            var id_2nd_ans = jQuery('input[name="select_ans_2"]:checked').attr('id'); 
//            var select_ans_2 = document.querySelector('#'+question+' input[name="select_ans_2"]:checked').value;
//            
//            var id_3rd_ans = jQuery('input[name="select_ans_3"]:checked').attr('id'); 
//            var select_ans_3 = document.querySelector('#'+question+' input[name="select_ans_3"]:checked').value;
//            
//            var id_4th_ans = jQuery('input[name="select_ans_4"]:checked').attr('id'); 
//            var select_ans_4 = document.querySelector('#'+question+' input[name="select_ans_4"]:checked').value;
//            
//            var id_5th_ans = jQuery('input[name="select_ans_5"]:checked').attr('id'); 
//            var select_ans_5 = document.querySelector('#'+question+' input[name="select_ans_5"]:checked').value;
            if(first_question_value=='' || second_question_value=='' || third_question_value=='' || fourth_question_value=='' || fifth_question_value==''){
                alert('Please select ans for evert question!');
                return false;
            }else{
                if(first_question_value==1 || first_question_value==2){
                    jQuery("#"+first_question).parent().parent().removeClass("error");
                }else{
                    jQuery("#"+first_question).parent().parent().addClass("error");
                    return false;
                }
                if(second_question_value==1 || second_question_value==2){
                    jQuery("#"+second_question).parent().parent().removeClass("error");
                }else{
                    jQuery("#"+second_question).parent().parent().addClass("error");
                    return false;
                }
                if(third_question_value==1 || third_question_value==2){
                    jQuery("#"+third_question).parent().parent().removeClass("error");
                }else{
                    jQuery("#"+third_question).parent().parent().addClass("error");
                    return false;
                }
                if(fourth_question_value==1 || fourth_question_value==2){
                    jQuery("#"+fourth_question).parent().parent().removeClass("error");
                }else{
                    jQuery("#"+fourth_question).parent().parent().addClass("error");
                    return false;
                }
                if(fifth_question_value==1 || fifth_question_value==2){
                    jQuery("#"+fifth_question).parent().parent().removeClass("error");
                }else{
                    jQuery("#"+fifth_question).parent().parent().addClass("error");
                    return false;
                }
                jQuery(".loding_script").addClass('active');
                jQuery.ajax({
                    type : "post",
                    url : Ajaxquizs.ajaxurl,
                    data : {action: "my_exame_responses", first_question_id:first_question_id, 
                        second_question_id:second_question_id, third_question_id:third_question_id,
                        fourth_question_id:fourth_question_id, fifth_question_id:fifth_question_id,
                        first_question_value:first_question_value, second_question_value:second_question_value,
                        third_question_value:third_question_value, fourth_question_value:fourth_question_value,
                        fifth_question_value:fifth_question_value},
                    success: function($data) {
                        jQuery(".loding_script").removeClass('active');
//                        alert($data);
                        window.location.replace ("http://assessment.globalcoachcenter.com/assessment/?request=yes&question="+next_question_id);
                    }
                });
                
            }
            return false;
        });
    });
</script>
    <?php
}
add_shortcode('my_exmeShow_by_shortcode_updated', 'shortCodeMy_exame_updated');

