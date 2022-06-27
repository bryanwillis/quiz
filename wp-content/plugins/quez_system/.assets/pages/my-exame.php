<div class="quiz_wrapper">
    <div class="quiz_container">
<?php
session_start();
function shortCodeMy_exame(){  ?>
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
    
    ?>
        
        <script>
            window.location.replace ("http://coach.defox.pk/assessment/?request=yes");
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
        foreach($_SESSION['quiz'] as $quiz){
            foreach($quiz as $question=>$ans){
                foreach($ans as $key=>$value){
                    $question_ans_id = str_replace('id_', '', $key);
                    if($value==1){
                        $answer = 1;
                    }else{
                        $answer = 0;
                    }
                    $table_user_quiz = $wpdb->prefix . "user_quiz";
                        $wpdb->insert(
                        $table_user_quiz, 
                        array(
                            'user_id' => $c_user,
                            'question_id' => $question,
                            'question_ans_id' => $question_ans_id,
                            'answer'=> $answer
                        )
                    );
                }
            }
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
            window.location.replace ("http://coach.defox.pk/assessment/?result=yes");
        </script>
        <?php
    }else{
        $table_check_user_quiz = $wpdb->prefix . "user_quiz";
        $check_user_quiz = $wpdb->get_row("SELECT * FROM $table_check_user_quiz WHERE user_id=$c_user");

        if($check_user_quiz){
        global $wpdb;
        $table_questions = $wpdb->prefix . "questions";
        $query =  "SELECT * FROM $table_questions ORDER BY id DESC" ;
        $get_data = $wpdb->get_results( "SELECT * FROM $table_questions ORDER BY id DESC" );

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
                    }else if($user_quiz->answer==2 && $question_answer->ane_true==0){
                        $correct = $correct+1;
                    }
                }else{
                    echo 'not found';
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
                <h1><strong><?php echo $k; ?>). </strong><?php echo $data->name; ?></h1>
            </div>
            <div class="question_results">
                <ul>
                    <li class="<?php echo $total_0; ?>">0</li>
                    <li class="<?php echo $total_1; ?>">1</li>
                    <li class="<?php echo $total_2; ?>">2</li>
                    <li class="<?php echo $total_3; ?>">3</li>
                    <li class="<?php echo $total_4; ?>">4</li>
                    <li class="<?php echo $total_5; ?>">5</li>
                </ul>
            </div>
        </div>
        <?php
        }
        ?>
        <form class="country_compare" method="get" name="compare" action="">
            <input type="hidden" name="print" value="yes">
            <input type="checkbox" name="country[]" value="Argentina">Argentina</br>
            <input type="checkbox" name="country[]" value="Australia">Australia</br>
            <input type="checkbox" name="country[]" value="China">China</br>
            <input type="checkbox" name="country[]" value="France">France</br>
            <input type="checkbox" name="country[]" value="Germany">Germany</br>
            <input type="checkbox" name="country[]" value="Honduras">Honduras</br>
            <input type="checkbox" name="country[]" value="India">India</br>
            <input type="checkbox" name="country[]" value="Israel">Israel</br>
            <input type="checkbox" name="country[]" value="Italy">Italy</br>
            <input type="checkbox" name="country[]" value="Japan">Japan</br>
            <input type="checkbox" name="country[]" value="Malaysia">Malaysia</br>
            <input type="checkbox" name="country[]" value="Mexico">Mexico</br>
            <input type="checkbox" name="country[]" value="Netherlands">Netherlands</br>
            <input type="checkbox" name="country[]" value="Philippines">Philippines</br>
            <input type="checkbox" name="country[]" value="Poland">Poland</br>
            <input type="checkbox" name="country[]" value="Qatar">Qatar</br>
            <input type="checkbox" name="country[]" value="Russia">Russia</br>
            <input type="checkbox" name="country[]" value="Spain">Spain</br>
            <input type="checkbox" name="country[]" value="Switzerland">Switzerland</br>
            <input type="checkbox" name="country[]" value="Turkey">Turkey</br>
            <input type="checkbox" name="country[]" value="UK">UK</br>
            <input type="checkbox" name="country[]" value="USA">USA</br>
            <button type="submit">Submit</button>
        </form>
        <h2>Submit and get the resulted PDF</h2>
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
                        unset($_SESSION['quiz']);
                        ?>
            <div class="quiz_infornt">
            <?php
               global $wpdb;
               $table_name = $wpdb->prefix . "questions";
               $get_data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id ASC" );
               foreach ($get_data as $k=>$data){
                   $k = $k+1;
                   $active = '';
                   if($k==1){
                       $active = 'active';
                   }
                   ?>

                <div class="ques_ans_box <?php echo $active ?>" id="question_<?php echo $k; ?>">
                   <h4><?php echo $k.'<strong>).</strong> '.$data->name; ?></h4>
                <?php
                $table_question_ans = $wpdb->prefix . "question_answers";
                $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$data->id ORDER BY id ASC");
                foreach ($get_ans as $n=>$g_ans){
                   $n = $n+1;
                       ?>
                    <div class="myquestios_ans">
                        <div class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $count ?>" name="select_ans_<?php echo $n ?>" > <?php echo $g_ans->ans_one ?>
                        </div>

                        <div class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $count ?>" name="select_ans_<?php echo $n ?>" > <?php echo $g_ans->ans_two; ?>
                        </div>
                    </div>
                    <?php
                   }
                 ?>
                       <input type="submit" value="NEXT" class="quiz_Pagnxt">
                </div>

                <?php
               }
            ?>
            <div class="ques_ans_box">
                <h1>Thank you!</h1>
                <p style="margin: 10px 0;">Please submit your assessment to see results</p>
                <a href="?result=yes" class="submit_assessment">Submit Assessment</a>
            </div>
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
                        $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
                        $wpdb->update(
                        $table_quiz_authentication, 
                            array(
                                'user_id' => $c_user,
                                'status'=> 1
                            ),
                            array( 'id' => $row->id )
                        );
                        ?>
        <script>
            window.location.replace ("http://coach.defox.pk/assessment/?request=yes");
        </script>
        <?php
//                        echo do_shortcode('[wp_paypal_payment]');
                        
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
                        ?>
        <script>
            window.location.replace ("http://coach.defox.pk/assessment/?request=yes");
        </script>
        <?php
//                    echo do_shortcode('[wp_paypal_payment]');
                }
}else{
    ?>
        <div class="request_exam">
            <div class="welcome_note_section">
            <h2>Welcome to the ICBI, the Individual Cultural Blueprint Indicator.</h2>
            <p>
                The ICBI TM is a behavior, habit, and belief-based assessment that empowers you to learn
your cultural preferences and compare them with the median cultural preferences of
another specified culture and your team member. This may mean comparing your
preferences with those of the country where you’ll be relocating, the diverse cultures of
your colleagues, or the cultures with which your company interacts the most – either in
person or virtually.
            </p>
            <p>
                The ICBI TM takes about 20 minutes to complete and provides you with a complete “Cultural
Preferences and Gaps” report. It allows you to identify the most relevant cultural gaps
affecting your life and evaluate whether the resulting challenges can be solved by
addressing those gaps.
            </p>
            <p>
                There is no right or wrong in completing this assessment. There is no value assigned to the
scoring, for example a score of 0 or 5 is not better or worse.
            </p>
            <p>
                You can come back to the assessment anytime, and your answers will be saved. Once done,
click ‘submit’ and you will be able to generate a PDF report. You will also get an email
notification. You have the option to compare your results with cultures of interest or your
team members after you click ‘submit’.
            </p>
            <p>If you have any questions, please contact <a href="mailto:info@culturalbusinessconsulting.com">info@culturalbusinessconsulting.com</a>.</p>
            <ul>
                <li><a href="?request=yes">Request for Assessment</a></li>
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
        <div class="welcome_note_section">
            <h2>Welcome to the ICBI, the Individual Cultural Blueprint Indicator.</h2>
            <p>
                The ICBI TM is a behavior, habit, and belief-based assessment that empowers you to learn
your cultural preferences and compare them with the median cultural preferences of
another specified culture and your team member. This may mean comparing your
preferences with those of the country where you’ll be relocating, the diverse cultures of
your colleagues, or the cultures with which your company interacts the most – either in
person or virtually.
            </p>
            <p>
                The ICBI TM takes about 20 minutes to complete and provides you with a complete “Cultural
Preferences and Gaps” report. It allows you to identify the most relevant cultural gaps
affecting your life and evaluate whether the resulting challenges can be solved by
addressing those gaps.
            </p>
            <p>
                There is no right or wrong in completing this assessment. There is no value assigned to the
scoring, for example a score of 0 or 5 is not better or worse.
            </p>
            <p>
                You can come back to the assessment anytime, and your answers will be saved. Once done,
click ‘submit’ and you will be able to generate a PDF report. You will also get an email
notification. You have the option to compare your results with cultures of interest or your
team members after you click ‘submit’.
            </p>
            <p>If you have any questions, please contact <a href="mailto:info@culturalbusinessconsulting.com">info@culturalbusinessconsulting.com</a>.</p>
            <ul>
                <li>Please </li>
                <li>
                    <a href="/login">Login</a>
                </li>
                <li> or </li>
                <li>
                    <a href="/register">Register</a>
                </li>
                <li>to view assessment page</li>
            </ul>
        </div>
    <?php
}
?>
        </div>
</div>
<style>
    .myquestios_ans {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
    }
    .ques_ans_box {
        margin-top: 30px;
    }
    .myquestios_ans .ans_inradion {
        width: 50%;
        text-align: left;
        margin: 6px 0px;
    }
    .quiz_infornt .ques_ans_box {
        display: none;
    }
    .quiz_infornt .ques_ans_box.active {
        display: block;
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
<script type="text/javascript">
   jQuery(document).ready(function(){
       jQuery(document).on('click','.quiz_Pagnxt',function(e){
            var question = jQuery(this).parent().attr('id');
            var question_id = question.replace("question_", "");
            var id_1st_ans = jQuery('input[name="select_ans_1"]:checked').attr('id');            
            var select_ans_1 = document.querySelector('#'+question+' input[name="select_ans_1"]:checked').value;
            
            var id_2nd_ans = jQuery('input[name="select_ans_2"]:checked').attr('id'); 
            var select_ans_2 = document.querySelector('#'+question+' input[name="select_ans_2"]:checked').value;
            
            var id_3rd_ans = jQuery('input[name="select_ans_3"]:checked').attr('id'); 
            var select_ans_3 = document.querySelector('#'+question+' input[name="select_ans_3"]:checked').value;
            
            var id_4th_ans = jQuery('input[name="select_ans_4"]:checked').attr('id'); 
            var select_ans_4 = document.querySelector('#'+question+' input[name="select_ans_4"]:checked').value;
            
            var id_5th_ans = jQuery('input[name="select_ans_5"]:checked').attr('id'); 
            var select_ans_5 = document.querySelector('#'+question+' input[name="select_ans_5"]:checked').value;
            if(select_ans_1=='' || select_ans_2=='' || select_ans_3=='' || select_ans_4=='' || select_ans_5==''){
                alert('Please select ans for evert question!');
                return false;
            }else{
                if(select_ans_1==1 || select_ans_1==2){
                    
                }else{
                    alert('Something went wrong.!');
                    return false;
                }
                if(select_ans_2==1 || select_ans_2==2){
                    
                }else{
                    alert('Something went wrong.!');
                    return false;
                }
                if(select_ans_3==1 || select_ans_3==2){
                    
                }else{
                    alert('Something went wrong.!');
                    return false;
                }
                if(select_ans_4==1 || select_ans_4==2){
                    
                }else{
                    alert('Something went wrong.!');
                    return false;
                }
                if(select_ans_5==1 || select_ans_5==2){
                    
                }else{
                    alert('Something went wrong.!');
                    return false;
                }
                e.preventDefault();
                jQuery("#"+question).removeClass('active').next().addClass('active');
                
                
                jQuery.ajax({
                    type : "post",
                    url : Ajaxquizs.ajaxurl,
                    data : {action: "my_exame_responses", question_id:question_id, id_1st_ans:id_1st_ans, 
                        select_ans_1:select_ans_1, id_2nd_ans:id_2nd_ans, select_ans_2:select_ans_2,
                        id_3rd_ans:id_3rd_ans, select_ans_3:select_ans_3,
                        id_4th_ans:id_4th_ans, select_ans_4:select_ans_4,
                        id_5th_ans:id_5th_ans, select_ans_5:select_ans_5},
                    success: function($data) {
//                        alert($data);
                      }
                });
            }
        });
    });
</script>
    <?php
}
add_shortcode('my_exmeShow_by_shortcode', 'shortCodeMy_exame');

