<?php
session_start();

function shortCodeMy_exame_updated() {

    if (is_user_logged_in()) {
        $c_user = get_current_user_id();
        global $wpdb;
        if (isset($_GET['start'])) {
            $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
            $row = $wpdb->get_row("SELECT * FROM $table_quiz_authentication WHERE user_id = '$c_user'");
            if ($row) {
                if ($row->status == 1) {
                    $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
                    $wpdb->update(
                        $table_quiz_authentication,
                        array(
                            'user_id' => $c_user,
                            'status'  => 1,
                        ),
                        array('id' => $row->id)
                    );
                }
            } else {
                $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
                $wpdb->insert(
                    $table_quiz_authentication,
                    array(
                        'user_id' => $c_user,
                        'status'  => 1,
                    )
                );
            }
            unset($_SESSION['quiz']);

            ?>

        <script>
            window.location.replace ("<?php echo site_url(); ?>/assessment/?request=yes");
        </script>
        <?php
}else if (isset($_GET['print'])) {

    $user_id = get_current_user_id();

    $is_hide_report = $_REQUEST['is_hide_report'];

    if ($is_hide_report) {
        $is_hide_report;
    } else {
        $is_hide_report = '';
    }
    update_user_meta($user_id, 'is_hide_report', $is_hide_report);

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
    $check_user_quiz       = $wpdb->get_row("SELECT * FROM $table_check_user_quiz WHERE user_id=$c_user");
    if ($check_user_quiz) {
        echo do_shortcode('[advanced-pdf-generator]');
    } else {
        ?>
<h1>Sorry! Please Take Assessment</h1>
<?php
}
}else if (isset($_GET['userresult'])) {
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
$userresult            = $_GET['userresult'];
    $table_check_user_quiz = $wpdb->prefix . "user_quiz";
    $check_user_quiz       = $wpdb->get_row("SELECT * FROM $table_check_user_quiz WHERE user_id=$userresult");
    if ($check_user_quiz) {
        echo do_shortcode('[advanced-pdf-generator]');
    } else {
        ?>
<h1>Sorry! You Are not allowed to see this page.</h1>
<?php
}
}else if (isset($_GET['result'])) {
    ?>
<style>
    .main_box.quiz_page {
        display: block;
    }
</style>
<?php
if (isset($_SESSION['quiz'])) {
        global $wpdb;
        $c_user = get_current_user_id();
        foreach ($_SESSION['quiz'] as $quizs) {
            foreach ($quizs as $question => $ans) {
                $check_question_ans = $wpdb->prefix . "question_answers";
                $check_question_ans = $wpdb->get_row("SELECT * FROM $check_question_ans WHERE id = '$question'");
                if ($check_question_ans) {
                    if ($ans == 1) {
                        $answer = 1;
                    } else {
                        $answer = 0;
                    }
                    $table_user_quiz = $wpdb->prefix . "user_quiz";
                    $wpdb->insert(
                        $table_user_quiz,
                        array(
                            'user_id'         => $c_user,
                            'question_id'     => $check_question_ans->question_id,
                            'question_ans_id' => $check_question_ans->id,
                            'answer'          => $answer,
                        )
                    );
                }
            }

        }
        unset($_SESSION['quiz']);
        $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
        $row                       = $wpdb->get_row("SELECT * FROM $table_quiz_authentication WHERE user_id = '$c_user'");
        if ($row) {
            if ($row->status == 1) {
                $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
                $wpdb->update(
                    $table_quiz_authentication,
                    array(
                        'user_id' => $c_user,
                        'status'  => 0,
                    ),
                    array('id' => $row->id)
                );
            }
        }
        ?>
<script>
    window.location.replace ("<?php echo site_url(); ?>/assessment/?result=yes");
</script>
<?php
} else {

        global $wpdb;
        $c_user = get_current_user_id();
        $um     = get_user_meta($c_user);
        $tn     = get_user_meta($c_user, 'team_name');
        if ($tn) {
            /*echo 'Current User - ' . $um['first_name'][0] . ' - ' . $c_user;
            echo '<br><br>Team Name - ' . $tn[0];*/
            $users = get_users(array(
                'meta_key'   => 'team_name',
                'meta_value' => $tn[0],
            ));
            /*echo '<br><br>Team Members - ';
        foreach ($users as $key => $user) {
        echo '<br>';
        print_r($user->data->display_name);
        print_r('-' . $user->data->ID);
        }*/
        }

        $table_check_user_quiz = $wpdb->prefix . "user_quiz";
        $check_user_quiz       = $wpdb->get_row("SELECT * FROM $table_check_user_quiz WHERE user_id=$c_user");

        if ($check_user_quiz) {
            $user_info  = get_userdata($c_user);
            $first_name = $user_info->first_name;
            $last_name  = $user_info->last_name;
            ?>
<!--h1 class="result_title">ICBI Summary for <?php echo $user_info->first_name . ' ' . $user_info->last_name; ?></h1-->
<p class="c_compare" style="font-size: 18px;" >Check the countries you are interested in comparing yourself with from the list below. You can choose as many as you like</p>
  <p style="color: red; margin-top: 21px;" id="count-checked-checkboxes"></p>
<form class="country_compare test" method="get" id="fommm" onsubmit="return validatecountry();" name="compare" action="">
    <input type="hidden" name="print" value="yes">
    <ul>
      <li><input type="checkbox" name="country[]" value="Argentina">Argentina</li>
      <li><input type="checkbox" name="country[]" value="Australia">Australia</li>
      <li><input type="checkbox" name="country[]" value="Austria">Austria</li>
      <li><input type="checkbox" name="country[]" value="Brazil">Brazil</li>
      <li><input type="checkbox" name="country[]" value="Canada">Canada</li>
      <li><input type="checkbox" name="country[]" value="Chile">Chile</li>
      <li><input type="checkbox" name="country[]" value="China">China</li>
      <li><input type="checkbox" name="country[]" value="Colombia">Colombia</li>
      <li><input type="checkbox" name="country[]" value="Costa Rica">Costa Rica</li>
      <li><input type="checkbox" name="country[]" value="Czech Republic">Czech Republic</li>
      <li><input type="checkbox" name="country[]" value="Denmark">Denmark</li>
      <li><input type="checkbox" name="country[]" value="El Salvador">El Salvador</li>
      <li><input type="checkbox" name="country[]" value="Finland">Finland</li>
      <li><input type="checkbox" name="country[]" value="France">France</li>
      <li><input type="checkbox" name="country[]" value="Germany">Germany</li>
      <li><input type="checkbox" name="country[]" value="Guatemala">Guatemala</li>
      <li><input type="checkbox" name="country[]" value="Honduras">Honduras</li>
      <li><input type="checkbox" name="country[]" value="Hong Kong">Hong Kong</li>
      <li><input type="checkbox" name="country[]" value="Hungary">Hungary</li>
      <li><input type="checkbox" name="country[]" value="India">India</li>
      <li><input type="checkbox" name="country[]" value="Ireland">Ireland</li>
      <li><input type="checkbox" name="country[]" value="Israel">Israel</li>
      <li><input type="checkbox" name="country[]" value="Italy">Italy</li>
      <li><input type="checkbox" name="country[]" value="Japan">Japan</li>
      <li><input type="checkbox" name="country[]" value="Kazakhstan">Kazakhstan</li>
      <li><input type="checkbox" name="country[]" value="Kenya">Kenya</li>
      <li><input type="checkbox" name="country[]" value="Malaysia">Malaysia</li>
      <li><input type="checkbox" name="country[]" value="Mexico">Mexico</li>
      <li><input type="checkbox" name="country[]" value="Netherlands">Netherlands</li>
      <li><input type="checkbox" name="country[]" value="New Zealand">New Zealand</li>
      <li><input type="checkbox" name="country[]" value="Nigeria">Nigeria</li>
      <li><input type="checkbox" name="country[]" value="Norway">Norway</li>
      <li><input type="checkbox" name="country[]" value="Philippines">Philippines</li>
      <li><input type="checkbox" name="country[]" value="Pakistan">Pakistan</li>
      <li><input type="checkbox" name="country[]" value="Peru">Peru</li>
      <li><input type="checkbox" name="country[]" value="Poland">Poland</li>
      <li><input type="checkbox" name="country[]" value="Portugal">Portugal</li>
      <li><input type="checkbox" name="country[]" value="Qatar">Qatar</li>
      <li><input type="checkbox" name="country[]" value="Romania">Romania</li>
      <li><input type="checkbox" name="country[]" value="Russia">Russia</li>
      <li><input type="checkbox" name="country[]" value="Saudi Arabia">Saudi Arabia</li>
      <li><input type="checkbox" name="country[]" value="Senegal">Senegal</li>
      <li><input type="checkbox" name="country[]" value="Singapore">Singapore</li>
      <li><input type="checkbox" name="country[]" value="South Africa">South Africa</li>
      <li><input type="checkbox" name="country[]" value="South Korea">South Korea</li>
      <li><input type="checkbox" name="country[]" value="Spain">Spain</li>
      <li><input type="checkbox" name="country[]" value="Sweden">Sweden</li>
      <li><input type="checkbox" name="country[]" value="Switzerland">Switzerland</li>
      <li><input type="checkbox" name="country[]" value="Taiwan">Taiwan</li>
      <li><input type="checkbox" name="country[]" value="Turkey">Turkey</li>
      <li><input type="checkbox" name="country[]" value="UK">UK</li>
      <li><input type="checkbox" name="country[]" value="Ukraine">Ukraine</li>
      <li><input type="checkbox" name="country[]" value="United Arab Emirates">UAE</li>
      <li><input type="checkbox" name="country[]" value="USA">USA</li>
    </ul>
    <input type="checkbox" name="is_hide_report" checked value="1">  Share with my team (We encourage you to share your cultural preferences with the members of your team.  If you uncheck the box, your cultural preferences will remain private).
   <br>
    <input type="hidden" name="checkboxcount" id="checkboxcount">
    <br/>
    <br/>
    <br/>
    <button type="quiz_Pagnxt" style="margin-left: 44%; font-size: 22px;
background-color: #0496E5;
color: #fff;
min-width: 100px;
height: 46px;
border: 0;
border-radius: 24px;
width: 150px;
">Submit</button>
</form>
<script>
jQuery(document).ready(function(){
    localStorage.removeItem('radioValueQuestions');
});

</script>
<?php
} else {
            ?>
<h1>Sorry! Please give Assessment</h1>
<?php
}
    }
}else if (isset($_GET['request'])) {

    $c_user = get_current_user_id();
    global $wpdb;
    $table_quiz_authentication = $wpdb->prefix . "quiz_authentication";
    $row                       = $wpdb->get_row("SELECT * FROM $table_quiz_authentication WHERE user_id = '$c_user' AND status=1");
    if ($row) {
        if ($row->status == 1) {

            ?>
    <div class="quiz_infornt">
        <p class="z_copy_right">The Global Coach Center – copyright, <?php echo date('Y'); ?></p>
        <?php
for ($page = 1; $page <= 17; $page++) {
                $page_active = '';
                if (isset($_GET['question'])) {
                    if ($_GET['question'] == $page) {
                        $get_question = $_GET['question'];
                        $page_active  = 'active';
                    }
                } else {
                    $get_question = 1;
                    if ($page == 1) {
                        $page_active = 'active';
                    }
                }
                ?>
        <div class="ques_ans_box <?php echo $page_active; ?>" id="page_<?php echo $page; ?>">
            <div class="progress_bar">
                <ul>
                    <?php
for ($progress = 1; $progress <= 17; $progress++) {
                    $progress_active = 'progress_active';
                    if ($get_question <= $progress) {
                        $progress_active = '';
                    }
                    if ($progress == 17) {
                        ?>
                    <li class="<?php echo $progress_active; ?>"><a href="/assessment/?request=yes&question=<?php echo $progress; ?>"></a></li>
                    <?php
} else {
                        ?>
                    <li class="<?php echo $progress_active; ?>"><a href="/assessment/?request=yes&question=<?php echo $progress; ?>"><?php // echo $progress; ?></a></li>
                    <?php
}
                }?>
                </ul>
            </div>
            <?php
if ($get_question != 17) {
                    ?>
            <div class="tag_line">
                Please choose one of the two answers for each question. There is no wrong or right answer.
                Choose the answer that applies best for you. Think about the work environment, not your
                personal life. Usually what comes up first in your mind is the best answer.
            </div>
        <?php
}
                global $wpdb;
                if ($page == 1 && $get_question == 1) {

//                    for first question
                    $question_id = 1;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box">
                        <h4><?php /*<?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                        <div class="myquestios_ans">
                            <label class="ans_inradion">
                                <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                            </label>

                            <label class="ans_inradion">
                                <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                            </label>
                        </div>
                    </div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 2;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box">
                        <h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                        <div class="myquestios_ans">
                            <label class="ans_inradion">
                                <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                            </label>

                            <label class="ans_inradion">
                                <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                            </label>
                        </div>
                    </div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 3;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box">
                        <h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                        <div class="myquestios_ans">
                            <label class="ans_inradion">
                                <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                            </label>

                            <label class="ans_inradion">
                                <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                            </label>
                        </div>
                    </div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 4;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box">
                        <h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                        <div class="myquestios_ans">
                            <label class="ans_inradion">
                                <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                            </label>

                            <label class="ans_inradion">
                                <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                            </label>
                        </div>
                    </div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 5;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box">
                        <h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                        <div class="myquestios_ans">
                            <label class="ans_inradion">
                                <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                            </label>

                            <label class="ans_inradion">
                                <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                            </label>
                        </div>
                    </div>
                    <?php
}
                        }
                    }
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';
                } else if ($page == 2 && $get_question == 2) {

//                    for first question
                    $question_id = 6;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box">
                        <h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                        <div class="myquestios_ans">
                            <label class="ans_inradion">
                                <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                            </label>

                            <label class="ans_inradion">
                                <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                            </label>
                        </div>
                    </div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 7;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box">
                        <h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                        <div class="myquestios_ans">
                            <label class="ans_inradion">
                                <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                            </label>

                            <label class="ans_inradion">
                                <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                            </label>
                        </div>
                    </div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 8;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box">
                        <h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                        <div class="myquestios_ans">
                            <label class="ans_inradion">
                                <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                            </label>

                            <label class="ans_inradion">
                                <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                            </label>
                        </div>
                    </div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 9;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box">
                        <h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                        <div class="myquestios_ans">
                            <label class="ans_inradion">
                                <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                            </label>

                            <label class="ans_inradion">
                                <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                            </label>
                        </div>
                    </div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 10;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box">
                        <h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                        <div class="myquestios_ans">
                            <label class="ans_inradion">
                                <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                            </label>

                            <label class="ans_inradion">
                                <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                            </label>
                        </div>
                    </div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=1">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 3 && $get_question == 3) {

//                    for first question
                    $question_id = 11;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box">
                        <h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                        <div class="myquestios_ans">
                            <label class="ans_inradion">
                                <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                            </label>

                            <label class="ans_inradion">
                                <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                            </label>
                        </div>
                    </div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 12;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 13;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 14;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 15;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=2">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 4 && $get_question == 4) {

//                    for first question
                    $question_id = 16;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 1) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 1;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 2;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 3;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 4;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=3">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 5 && $get_question == 5) {

//                    for first question
                    $question_id = 5;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 6;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 7;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 8;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 9;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=4">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 6 && $get_question == 6) {

//                    for first question
                    $question_id = 10;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 11;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 12;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 13;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 14;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=5">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 7 && $get_question == 7) {

//                    for first question
                    $question_id = 15;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 16;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 2) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 1;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 2;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 3;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=6">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 8 && $get_question == 8) {

//                    for first question
                    $question_id = 4;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 5;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 6;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 7;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 8;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=7">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 9 && $get_question == 9) {

//                    for first question
                    $question_id = 9;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 10;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 11;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 12;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div>
                    </div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 13;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div>
                    </div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=8">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 10 && $get_question == 10) {

//                    for first question
                    $question_id = 14;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div>
                    </div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 15;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div>
                    </div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 16;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 3) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div>
                    </div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 1;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div>
                    </div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 2;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div>
                    </div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=9">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 11 && $get_question == 11) {

//                    for first question
                    $question_id = 3;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div>
                    </div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 4;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div>
                    </div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 5;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div>
                    </div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 6;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div>
                    </div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 7;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div>
                    </div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=10">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 12 && $get_question == 12) {

//                    for first question
                    $question_id = 8;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                        <div class="myquestios_ans">
                            <label class="ans_inradion">
                                <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                            </label>

                            <label class="ans_inradion">
                                <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                            </label>
                        </div>
                    </div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 9;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 10;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 11;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 12;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=11">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 13 && $get_question == 13) {

//                    for first question
                    $question_id = 13;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 14;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 15;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 16;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 4) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 1;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=12">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 14 && $get_question == 14) {

//                    for first question
                    $question_id = 2;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 3;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 4;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 5;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 6;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div> </div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=13">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 15 && $get_question == 15) {

//                    for first question
                    $question_id = 7;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 8;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    third question
                    $question_id = 9;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 10;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 11;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
                    echo '<a class="back_button" href="/assessment/?request=yes&question=14">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 16 && $get_question == 16) {

//                    for first question
                    $question_id = 12;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>1)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_1" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    second question
                    $question_id = 13;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>2)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_2" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
// third question
                    $question_id = 14;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>3)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_3" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    forth question
                    $question_id = 15;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>4)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_4" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
}
                        }
                    }
//                    fifth question
                    $question_id = 16;
                    $table_name  = $wpdb->prefix . "questions";
                    $get_data    = $wpdb->get_row("SELECT * FROM $table_name WHERE id=$question_id");
                    if ($get_data) {
                        $table_question_ans = $wpdb->prefix . "question_answers";
                        $get_ans            = $wpdb->get_results("SELECT * FROM $table_question_ans WHERE question_id=$question_id ORDER BY id ASC");
                        foreach ($get_ans as $n => $g_ans) {
                            $n = $n + 1;
                            if ($n == 5) {
                                ?>
                    <div class="quiz_question-box"> 
<h4><?php /*<strong>5)</strong> */?> <?php echo $get_data->name; ?></h4>
                    <div class="myquestios_ans">
                        <label class="ans_inradion">
                            <input type="radio" required value="1" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_one ?>
                        </label>

                        <label class="ans_inradion">
                            <input type="radio" required value="2" id="id_<?php echo $g_ans->id ?>" class="ansClss_<?php echo $g_ans->id ?>" name="select_ans_5" > <?php echo $g_ans->ans_two; ?>
                        </label>
                    </div></div>
                    <?php
                    }
                        }
                    }

                    echo '<a class="back_button" href="/assessment/?request=yes&question=15">Back</a>';
                    echo '<input type="submit" value="Next" class="quiz_Pagnxt" style="min-width: 200px; margin-left: 40%;">';

                } else if ($page == 17 && $get_question == 17) {
                    ?>
                    <h1 style="padding-top: 30px; padding-bottom: 30px; text-align: center; font-size: 32px;">Thank you!</h1>
                    <p style="margin: 10px 0; padding-bottom: 30px; font-size: 22px; text-align: center; ">Submit to print or view your results</p>
                    <a href="?result=submit" class="quiz_Pagnxt" 
                        style="text-align: center;
                                margin-left: 42%;
                                width: 150px;
                                display: block;
                                padding-top: 10;
                                font-size: 22px;
                            height: 46px;">Submit</a>

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
} else {
            ?>
<div class="request_exam">
    <div class="welcome_note_section">
        <h2>Welcome to the ICBI™, the Individual Cultural Blueprint Indicator.</h2>
        <h3>Your request to access the assessment has been received. You will get notified when you are approved</h3>
    </div>

</div>

<?php
}
    } else {?>
<div class="request_exam">
    <div class="welcome_note_section">
        <h2>Welcome to the ICBI™, the Individual Cultural Blueprint Indicator.</h2>
        <h3>Your request to access the assessment has been received. You will get notified when you are approved</h3>
    </div>
</div>
<script>
</script>
<?php

    }
}else {
    ?>

<div class="request_exam">
    <div class="welcome_note_section">

<div class="row" style="background: #0496e4; height: 100px;">
<div class="xs-text-center text-right col-sm-6 col-xs-12 col-sm-push-6">
    <img style="padding-top: 16px;" src="/wp-content/themes/power/images/ICBI_Logo_White_Globe190.png" width="111" />
</div>

<div class="col-sm-6 col-xs-12 col-sm-pull-6">
    <h1 class="xs-text-center" style="color: white; padding: 20px 0; font-size: 32px; font-weight: normal; text-align: left;">Introduction</h1>
</div>
</div>


 <p/>
 <p/>
            <div style="text-align:left;font-weight: bold; font-size: 16px;padding-bottom: 30px;">
            <p>
            The ICBI™ is a behavior, habit, and belief-based assessment that empowers you to learn about
your cultural preferences and how they compare with the median cultural preferences of
other national cultures. 
</p>
<p>
After completing the assessment, you will be prompted to select the countries with which you’d like to compare your personal cultural profile. The countries that you select may include: your home country, the country you’ll be relocating to, the countries of your colleagues and clients, the countries with which your company interacts the most (virtually or in-person), or any country that you would like to understand deeper.
            </p>
<p>
            Please note that the ICBI™ is one of many frameworks to understand a culture, and should not be used 
as the only framework from which to inform decisions. The research behind the ICBI™ does not 
necessarily reflect all individual experiences. 
    </p>
            </div>


    <p>If you have any questions, please contact <a href="mailto:info@culturalbusinessconsulting.com">info@culturalbusinessconsulting.com</a>.</p>
    <ul>
        <li><a class="request_button" style="width:200px;color: white; text-decoration:none;" href="?request=yes&question=1&">Complete the ICBI</a></li>


        <li>  </li>
        <li>
            <a href="/account">View Account</a>
        </li>
    
    </ul>



</div>

</div>
<?php
}
    }else {
        ?>
        <div class="request_exam">
            <div class="welcome_note_section">
            <!--h2>Welcome to the ICBI™, the Individual Cultural Blueprint Indicator.</h2-->
            <div class="row" style="background: #0496e4; height: 100px;">
<div class="xs-text-center text-right col-sm-6 col-xs-12 col-sm-push-6"><img style="padding-top: 16px;" src="/wp-content/themes/power/images/ICBI_Logo_White_Globe190.png" width="111" /></div>
<div class="col-sm-6 col-xs-12 col-sm-pull-6">
<h1 class="xs-text-center" style="color: white; padding: 20px 0; font-size: 32px; font-weight: normal; text-align: left;">Introduction</h1>
</div>
</div>
            <p>
            <p/>
            <div style="text-align:left;font-weight: bold; font-size: 16px;padding-bottom: 30px;">
            <p>
            The ICBI™ is a behavior, habit, and belief-based assessment that empowers you to learn about
your cultural preferences and how they compare with the median cultural preferences of
other national cultures. 
</p>
<p>
After completing the assessment, you will be prompted to select the countries with which you’d like to compare your personal cultural profile. The countries that you select may include: your home country, the country you’ll be relocating to, the countries of your colleagues and clients, the countries with which your company interacts the most (virtually or in-person), or any country that you would like to understand deeper.
            </p>
<p>
            Please note that the ICBI™ is one of many frameworks to understand a culture, and should not be used 
as the only framework from which to inform decisions. The research behind the ICBI™ does not 
necessarily reflect all individual experiences. 
    </p>
            </div>
             <ul>   
                 <li>
                    
                    <a id="bext_button" class="bext_button" style="width:200px;color: white; text-decoration:none;" href="../register/" onclick="location.href='../register';">Register</a>

                 </li>               
                <li>                    
                    <a class="bext_button" style="width:200px;color: white; text-decoration:none;"  href="../login" onclick="location.href='../login';"  style="color: white; text-decoration:none;">Login</a>
                  
                </li>

            </ul>
           
        </div>

        </div>
    <?php } ?>
    </div>
</div>
<script type="text/javascript">
   jQuery(document).ready(function(){
       var current_question_page = localStorage.getItem('current_question_page');
       if(current_question_page != null && current_question_page > 0)
       {
         jQuery(".request_button").attr('href', '?request=yes&question=' + current_question_page);  
       }
       
       
       jQuery(document).on('click','.quiz_Pagnxt',function(e){
            var question = jQuery(this).parent().attr('id');
            var next_question = jQuery("#"+question).next().attr('id');
            var next_question_id = next_question.replace("page_", "");
            
            if(next_question_id > 1)
            {
              localStorage.setItem('current_question_page', next_question_id - 1);
            }
            
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
                        window.location.replace ("<?php echo site_url(); ?>/assessment/?request=yes&question="+next_question_id);
                    }
                });

            }
            return false;
        });
    });

 jQuery(document).ready(function(){
    var jQuerycheckboxes = jQuery('#fommm input[type="checkbox"]');

    jQuerycheckboxes.change(function(){
        var countCheckedCheckboxes = jQuerycheckboxes.filter(':checked').length;
        document.getElementById("checkboxcount").value = countCheckedCheckboxes;
    });
    
    var savedRadioValueQuestions = JSON.parse(localStorage.getItem('radioValueQuestions'));
    if (savedRadioValueQuestions!=null){
        jQuery('input[type=radio]').each(function(){
            
            if(this.value == savedRadioValueQuestions[jQuery(this).attr('id')]){
                this.checked = true;
            }
        });
    }

    jQuery('input[type=radio]').on('change', function(){
        var radioValueQuestions = JSON.parse(localStorage.getItem('radioValueQuestions'));      

        if (radioValueQuestions==null){
            radioValueQuestions = {};
        }
        radioValueQuestions[jQuery(this).attr('id')]=jQuery(this).val();
        localStorage.setItem('radioValueQuestions', JSON.stringify(radioValueQuestions));
    });
});


function validatecountry(){
  var checkboxcount = jQuery('#checkboxcount').val();
  if(checkboxcount < 2){
       jQuery('#count-checked-checkboxes').text('Please select at least 2 country');
       return false;
  }
}
</script>
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
        font-size: 16px;
        color: #888;
        padding-bottom: 20px;
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
<?php }
// session_write_close();
add_shortcode('my_exmeShow_by_shortcode_updated', 'shortCodeMy_exame_updated');
?>