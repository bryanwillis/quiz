<?php
include_once PLUGIN_DIR_PATH . '../inc/enqu.php';
add_action('wp_ajax_nopriv_my_exame_responses', 'my_exame_responses');
add_action("wp_ajax_my_exame_responses", "my_exame_responses");
function my_exame_responses() {
    session_start();

    $first_question_id    = $_POST['first_question_id'];
    $first_question_value = $_POST['first_question_value'];

    $second_question_id    = $_POST['second_question_id'];
    $second_question_value = $_POST['second_question_value'];

    $third_question_id    = $_POST['third_question_id'];
    $third_question_value = $_POST['third_question_value'];

    $fourth_question_id    = $_POST['fourth_question_id'];
    $fourth_question_value = $_POST['fourth_question_value'];

    $fifth_question_id    = $_POST['fifth_question_id'];
    $fifth_question_value = $_POST['fifth_question_value'];


    $first_data[]  = array($first_question_id => $first_question_value);
    $second_data[] = array($second_question_id => $second_question_value);
    $third_data[]  = array($third_question_id => $third_question_value);
    $fourth_data[] = array($fourth_question_id => $fourth_question_value);
    $fifth_data[]  = array($fifth_question_id => $fifth_question_value);

    if (isset($_SESSION['quiz'])) {
        $_SESSION['quiz'] = array_merge($_SESSION['quiz'], $first_data, $second_data, $third_data, $fourth_data, $fifth_data);
    } else {
        $_SESSION['quiz'] = array_merge($first_data, $second_data, $third_data, $fourth_data, $fifth_data);
    }
    echo 'success!';
    die();
}

add_action('wp_ajax_nopriv_my_exame_responses_development', 'my_exame_responses_development');
add_action("wp_ajax_my_exame_responses_development", "my_exame_responses_development");
function my_exame_responses_development() {
    session_start();

    $question_id  = $_POST['question_id'];
    $id_1st_ans   = $_POST['id_1st_ans'];
    $select_ans_1 = $_POST['select_ans_1'];
    $id_2nd_ans   = $_POST['id_2nd_ans'];
    $select_ans_2 = $_POST['select_ans_2'];
    $id_3rd_ans   = $_POST['id_3rd_ans'];
    $select_ans_3 = $_POST['select_ans_3'];
    $id_4th_ans   = $_POST['id_4th_ans'];
    $select_ans_4 = $_POST['select_ans_4'];
    $id_5th_ans   = $_POST['id_5th_ans'];
    $select_ans_5 = $_POST['select_ans_5'];

    $data[] = array($question_id => array($id_1st_ans => $select_ans_1, $id_2nd_ans => $select_ans_2, $id_3rd_ans => $select_ans_3, $id_4th_ans => $select_ans_4, $id_5th_ans => $select_ans_5));

    if (isset($_SESSION['quiz'])) {
        $_SESSION['quiz'] = array_merge($_SESSION['quiz'], $data);
    } else {
        $_SESSION['quiz'] = $data;
    }
    print_r($_SESSION['quiz']);
    die();
}

function generate_pdf_shortcode() {
    session_start();
    $team_user_id          = (isset($_GET['report_type']) && $_GET['report_type'] == 'team') ? $_GET['userresult'] : 0;
    $_SESSION['c_user_id'] = ($team_user_id) ? $team_user_id : get_current_user_id();

    $result .= '<style>
	
	@page { margin: 100px 50px; }
	.page_break { page-break-before: always; }
	#footer ul{margin:0; padding: 0;}
	#footer ul li{list-style: none;margin: 0 0 0 0;text-align:center;font-size:15px} 
	#footer {display: inline-block; position: fixed; left: 0px; bottom: -100px; right: 0px; height: 50px;}
	.left_text{color: white; float: left; font-size: 20px; width: 60%; padding: 35px 0 0 50px;}
	.logo_right{float: right; width: 20%}
	#header {display: inline-block; position: fixed; left: -50px; top: -100px; right: -40px; height: 65px; width: 105%;}
	.left_right{width: 100%; display: inline-block; clear:both; margin: 5px 0 0 0; height: 30px;}
	.left_right .left{width: 50%; float: left; text-align:left;}
	.left_right .right{width: 40%; float: right; text-align:right; }
	.good{color: red} 
	.question_no h1{font-size: 24px; margin-bottom:0;} 
	table{padding: 0 0 15px 0; width:100%; border-collapse: collapse;} 
	table, th, td {border: 1px solid #555; width: 13%;text-align: center; font-size: 10px; padding: 3px 0;} 
	td.active{background-color: #606060;}th.active{background-color: #0496e4;}
	
	.question_description_box {
		background-color: #ececec;
		border-radius: 20px;		
		padding: 5px 20px 5px 20px;
		font-size:12px;
	}
	
	</style>';
    $result .= '<style>.question_content{font-size: 16px;}</style>';

	$result .= '<div id="header" style="background: #0496e4; height: 90px;">
		<div class="left_text">Your Individual Cultural Blueprint Indicator Report</div><div class="logo_right"><img style="padding-top: 16px;" src="https://assessment.globalcoachcenter.com/wp-content/themes/power/images/ICBI_Logo_White_Globe190.png"  width="111" alt="ICBI"></div>
	</div>
    <div id="footer"><ul><li>Individual Cultural Blueprint Indicator™, All Rights Reserved</li></ul></div>
    ';
	
	// Start of intro page
    $user_info  = get_userdata(($team_user_id) ? $team_user_id : get_current_user_id());
    $first_name = $user_info->first_name;
    $last_name  = $user_info->last_name;
	$result .= '<h1 style="margin-bottom: 20px; padding-top:50px; text-align:left; width: 100%; font-size: 28px;" class="result_title">Introduction</h1>';
    $result .= '<p style="margin-bottom: 20px;">Your ICBI™(Individual Cultural Blueprint Indicator) report consists of 16 orientations of cultural differences.  Based on your answers, your scores represent different points on a scale from 0 to 5 on each of the orientations.  There are no right or wrong answers.  The report will help you understand where you have gaps between other team members and countries you selected for comparison. </p>';
    $result .= '<p style="margin-bottom: 20px;">Please note that your profile might be quite different from the typical profile of your home country. Please keep that difference in mind as you look at the other member and country profiles. While cultures and countries have a typical profile, individuals rarely have a typical profile. Use this report as a guideline for building self-awareness and other cultural awareness, but not as an absolute truth. The ICBI™ is designed to help individuals and teams to increase productivity, improve relationships, and promote efficient communications within multi-cultural environments.</p>';
    $result .= '<p style="margin-bottom: 20px;">The preference of the majority of the people in the country (countries) you are comparing yourself to is plotted in the row(s) below your preference.  Circle or note significant gaps between your personal cultural preferences and other preferences.  With others or a coach/trainer, brainstorm strategies on how to bridge the gaps. </p>';
    $result .= '<div style="height: 350px; width: 100%; display: block"></div>';
    $result .= '<div class="page_break"></div>';
	// End of intro page
	
	
    $result .= '<h2 style="margin-bottom: 20px; text-align:center; width: 100%; font-size: 18px;" >Personal Cultural Blueprint for ' . $user_info->first_name . ' ' . $user_info->last_name . '</h2>';

    $result .= '<style>'
                    . '.f_first_last td{background-color: #fff;}.f_question_results td{background-color: #fff;}.f_question_results .active{background-color:#0496e4; color:white;}.yellow_bg .active{background-color: #282359 !important; color:white;}'
                    . ''
                    . '</style>';
    
    global $wpdb;
    $table_questions = $wpdb->prefix . "questions";
    $query           = "SELECT * FROM $table_questions ORDER BY id DESC";
    $get_data        = $wpdb->get_results("SELECT * FROM $table_questions ORDER BY id DESC");
    
    //        first table start
    $result .= '<div class="question_ans_box">';
    $result .= '<table class="f_question_ans_box" style="width:100%">';
    foreach ($get_data as $k => $data) {
        $k                      = $k + 1;
        $c_user                 = ($team_user_id) ? $team_user_id : get_current_user_id();
        $table_question_answers = $wpdb->prefix . "question_answers";
        $query                  = "SELECT * FROM $table_question_answers ORDER BY id DESC";
        $question_answers       = $wpdb->get_results("SELECT * FROM $table_question_answers WHERE question_id=$data->id");
        $correct                = 0;
        foreach ($question_answers as $question_answer) {
            $table_user_quiz = $wpdb->prefix . "user_quiz";
            $user_quiz       = $wpdb->get_row("SELECT * FROM $table_user_quiz WHERE question_ans_id = $question_answer->id AND user_id=$c_user ORDER BY id DESC");
            if ($user_quiz) {
                if ($user_quiz->answer == 1 && $question_answer->ane_true == 1) {
                    $correct = $correct + 1;
                } else if ($user_quiz->answer == 0 && $question_answer->ane_true == 0) {
                    $correct = $correct + 1;
                }
            } else {
        //  echo 'not found';
            }
        }
        $total_0 = '';
        $total_1 = '';
        $total_2 = '';
        $total_3 = '';
        $total_4 = '';
        $total_5 = '';
        if ($correct == 0) {
            $total_0 = 'active';
        } else if ($correct == 1) {
            $total_1 = 'active';
        } else if ($correct == 2) {
            $total_2 = 'active';
        } else if ($correct == 3) {
            $total_3 = 'active';
        } else if ($correct == 4) {
            $total_4 = 'active';
        } else if ($correct == 5) {
            $total_5 = 'active';
        } else {
            $total_0 = 'active';
        }

        $result .= '<style>'
            . '.f_first_last td{background-color: #fff;}.f_question_results td{background-color: #fff;}.f_question_results .active{background-color:#0496e4; color:white;}'
            . ''
            . '</style>';
        $result .= '<tr class="f_veriable">';
        $result .= '<td colspan="6" style="text-align:left; font-weight: 700; padding: 1px 0 1px 10px; background-color: #e4e4e4;"><a style="text-decoration: none; color: #000000; display: block;" href="#' . str_replace(' ', '', strtolower($data->veriable)) . '"><div>' . $k . '. <span style="text-decoration: underline; color: #0496e4;">' . $data->veriable . '</span></div></a></td>';
        $result .= '</tr>';
        $result .= '<tr class="f_first_last">';
        $result .= '<td colspan="3" style="text-align: left; padding: 1px 0 1px 10px">' . $data->first_text . '</td>';
        $result .= '<td colspan="3" style="text-align: right; padding: 1px 10px 1px 0px">' . $data->last_text . '</td>';
        $result .= '</tr>';
        $result .= '<tr class="f_question_results">';
        $result .= '<td class="' . $total_0 . '" style="padding: 1px 10px 1px 0px">0</td>';
        $result .= '<td class="' . $total_1 . '" style="padding: 1px 10px 1px 0px">1</td>';
        $result .= '<td class="' . $total_2 . '" style="padding: 1px 10px 1px 0px">2</td>';
        $result .= '<td class="' . $total_3 . '" style="padding: 1px 10px 1px 0px">3</td>';
        $result .= '<td class="' . $total_4 . '" style="padding: 1px 10px 1px 0px">4</td>';
        $result .= '<td class="' . $total_5 . '" style="padding: 1px 10px 1px 0px">5</td>';
        $result .= '</tr>';
    }
    $result .= '</table>';
    $result .= '</div><div class="page_break"></div>';

//        first table end


// start of question description and results table
    $total_count = count($get_data);
    foreach ($get_data as $k => $data) {

        $k                      = $k + 1;
        $c_user                 = ($team_user_id) ? $team_user_id : get_current_user_id();
        $table_question_answers = $wpdb->prefix . "question_answers";
        $query                  = "SELECT * FROM $table_question_answers ORDER BY id DESC";

        $question_answers = $wpdb->get_results("SELECT * FROM $table_question_answers WHERE question_id=$data->id");
        $correct          = 0;
        foreach ($question_answers as $question_answer) {
            $table_user_quiz = $wpdb->prefix . "user_quiz";
            $user_quiz       = $wpdb->get_row("SELECT * FROM $table_user_quiz WHERE question_ans_id = $question_answer->id AND user_id=$c_user ORDER BY id DESC");

            if ($user_quiz) {
                if ($user_quiz->answer == 1 && $question_answer->ane_true == 1) {
                    $correct = $correct + 1;
                } else if ($user_quiz->answer == 0 && $question_answer->ane_true == 0) {
                    $correct = $correct + 1;
                }
            } else {
        //  echo 'not found';
            }
        }
        $total_0 = '';
        $total_1 = '';
        $total_2 = '';
        $total_3 = '';
        $total_4 = '';
        $total_5 = '';
        if ($correct == 0) {
            $total_0 = 'active';
        } else if ($correct == 1) {
            $total_1 = 'active';
        } else if ($correct == 2) {
            $total_2 = 'active';
        } else if ($correct == 3) {
            $total_3 = 'active';
        } else if ($correct == 4) {
            $total_4 = 'active';
        } else if ($correct == 5) {
            $total_5 = 'active';
        } else {
            $total_0 = 'active';
        }

        $result .= '<div class="question_ans_box">';
        $result .= '<div class="question_no">';

        $result .= '<h1 style="font-size: 18px" id="' . str_replace(' ', '', strtolower($data->veriable)) . '"><strong>' . $k . '. </strong>' . $data->veriable . '</h1>';

        $result .= '<div class="question_content">' . $data->description . '</div>';
        $result .= '</div>';
        $result .= '<table style="width:100%">';
        $result .= '<tbody>';
        $result .= '<tr class="f_veriable">';
        $result .= '<td colspan="7" style="text-align:left; font-weight: 700; padding: 1px 0 1px 10px; background-color: #e4e4e4">' . $k . '. ' . $data->veriable . '</td>';
        $result .= '</tr>';
        $result .= '<tr class="f_first_last">';
        $result .= '<td></td>';
        $result .= '<td colspan="3" style="text-align: left; padding: 1px 0 1px 10px">' . $data->first_text . '</td>';
        $result .= '<td colspan="3" style="text-align: right; padding: 1px 10px 1px 0px">' . $data->last_text . '</td>';
        $result .= '</tr>';
        $result .= '</tbody>';
        $result .= '<thead>';
        $result .= '<tr>';
        $result .= '<th class="country" style="padding: 1px 10px 1px 0px">' . $user_info->first_name . ' ' . $user_info->last_name . '</th>';
        $result .= '<th class="' . $total_0 . '" style="padding: 1px 10px 1px 0px">0</th>';
        $result .= '<th class="' . $total_1 . '" style="padding: 1px 10px 1px 0px">1</th>';
        $result .= '<th class="' . $total_2 . '" style="padding: 1px 10px 1px 0px">2</th>';
        $result .= '<th class="' . $total_3 . '" style="padding: 1px 10px 1px 0px">3</th>';
        $result .= '<th class="' . $total_4 . '" style="padding: 1px 10px 1px 0px">4</th>';
        $result .= '<th class="' . $total_5 . '" style="padding: 1px 10px 1px 0px">5</th>';
        $result .= '</tr>';
        $result .= '</thead>';

        /*Start Team*/
        //$question_answers = $wpdb->get_results("SELECT * FROM $table_question_answers WHERE question_id=$data->id");
        $correct          = 0;
        $c_user           = ($team_user_id) ? $team_user_id : get_current_user_id();
        $um               = get_user_meta($c_user);
        $tn               = get_user_meta($c_user, 'team_name');
        $table_team = $wpdb->prefix . "team";
        $data_team  = $wpdb->get_results("SELECT * FROM $table_team WHERE team_code='$tn[0]'");
        $individual_report = $data_team[0]->individual_report;

        if ($tn && !$individual_report) {
            $users = get_users(array(
                'meta_key'   => 'team_name',
                'meta_value' => $tn[0],
                'exclude'    => $c_user,
            ));
            $un = 0;

            foreach ($users as $key => $user) {
                $correct = 0;
                foreach ($question_answers as $question_answer) {
                    $c_user    = $user->data->ID;
                    $user_quiz = $wpdb->get_row("SELECT * FROM $table_user_quiz WHERE question_ans_id = $question_answer->id AND user_id=$c_user ORDER BY id DESC");
                    if ($user_quiz) {
                        if ($user_quiz->answer == 1 && $question_answer->ane_true == 1) {
                            $correct = $correct + 1;
                        } else if ($user_quiz->answer == 0 && $question_answer->ane_true == 0) {
                            $correct = $correct + 1;
                        }
                    }

                    $total_0 = '';
                    $total_1 = '';
                    $total_2 = '';
                    $total_3 = '';
                    $total_4 = '';
                    $total_5 = '';
                    if ($correct == 0) {
                        $total_0 = 'active';
                    } else if ($correct == 1) {
                        $total_1 = 'active';
                    } else if ($correct == 2) {
                        $total_2 = 'active';
                    } else if ($correct == 3) {
                        $total_3 = 'active';
                    } else if ($correct == 4) {
                        $total_4 = 'active';
                    } else if ($correct == 5) {
                        $total_5 = 'active';
                    } else {
                        $total_0 = 'active';
                    }
                }

                if (get_user_meta($user->data->ID, 'is_hide_report', true) == 1) {
                    $un = $un + 1;
                    $result .= '<tbody>';
                    $result .= '<tr class="f_question_results yellow_bg">';
                    $result .= '<td class="country" style="padding: 1px 10px 1px 0px"> Team Member ' . $un . '</td>';
                    $result .= '<td class="' . $total_0 . '" style="padding: 1px 10px 1px 0px">0</td>';
                    $result .= '<td class="' . $total_1 . '" style="padding: 1px 10px 1px 0px">1</td>';
                    $result .= '<td class="' . $total_2 . '" style="padding: 1px 10px 1px 0px">2</td>';
                    $result .= '<td class="' . $total_3 . '" style="padding: 1px 10px 1px 0px">3</td>';
                    $result .= '<td class="' . $total_4 . '" style="padding: 1px 10px 1px 0px">4</td>';
                    $result .= '<td class="' . $total_5 . '" style="padding: 1px 10px 1px 0px">5</td>';
                    $result .= '</tr>';
                    $result .= '</tbody>';
                }
            }
        }
        /*Team end*/
        
        global $wpdb;
        $table_name = $wpdb->prefix . "country_answer";
        $query      = "SELECT * FROM $table_name ORDER BY id DESC";
        if (isset($_GET['country'])) {
            $country_list = $_GET['country'];
            $get_data     = $wpdb->get_results("SELECT * FROM $table_name WHERE question_id=$data->id AND country_name IN ( '" . implode("', '", $country_list) . "' ) ORDER BY id DESC");
            foreach ($get_data as $data) {
                $total_0 = '';
                $total_1 = '';
                $total_2 = '';
                $total_3 = '';
                $total_4 = '';
                $total_5 = '';
                if ($data->total == 0) {
                    $total_0 = 'active';
                } else if ($data->total == 1) {
                    $total_1 = 'active';
                } else if ($data->total == 2) {
                    $total_2 = 'active';
                } else if ($data->total == 3) {
                    $total_3 = 'active';
                } else if ($data->total == 4) {
                    $total_4 = 'active';
                } else if ($data->total == 5) {
                    $total_5 = 'active';
                } else {
                    $total_0 = 'active';
                }
                $result .= '<tbody>';
                $result .= '<tr>';
                if ($data->country_name == "United Arab Emirates") {
                    $country_name = 'UAE';
                } else {
                    $country_name = $data->country_name;
                }

                $result .= '<td class="country" style="padding: 1px 10px 1px 0px">' . $country_name . '</td>';
                $result .= '<td class="' . $total_0 . '" style="padding: 1px 10px 1px 0px">0</td>';
                $result .= '<td class="' . $total_1 . '" style="padding: 1px 10px 1px 0px">1</td>';
                $result .= '<td class="' . $total_2 . '" style="padding: 1px 10px 1px 0px">2</td>';
                $result .= '<td class="' . $total_3 . '" style="padding: 1px 10px 1px 0px">3</td>';
                $result .= '<td class="' . $total_4 . '" style="padding: 1px 10px 1px 0px">4</td>';
                $result .= '<td class="' . $total_5 . '" style="padding: 1px 10px 1px 0px">5</td>';
                $result .= '</tr>';
                $result .= '</tbody>';

            }
        }
        $result .= '</table>';

        $result .= '</div>';
        if ($k < $total_count) {
            $result .= '<div class="page_break"></div>';
        }
    }
    return $result;
}
add_shortcode('generate_pdf_shortcode', 'generate_pdf_shortcode');

function user_authneticate_approval($user_id, $args) {
    global $wpdb;
    $team_code = get_user_meta($user_id, 'team_name', true);
    if ($team_code) {
        $check_approval = $wpdb->get_var("SELECT team_approval FROM {$wpdb->prefix}team WHERE team_code='" . $team_code . "'");
        if ($check_approval == 1) {
            $wpdb->query("INSERT INTO {$wpdb->prefix}quiz_authentication(user_id,status)VALUES($user_id,1)");
        }
    }
}

add_action('um_registration_complete', 'user_authneticate_approval', 10, 2);

function generate_pdf_team_report_shortcode() {
    global $wpdb;

    $team_code = $_GET['team_code'];
    $result                = '<style></style>';
    $result .= '<style>
		
	@page { margin: 100px 50px; }
	.page_break { page-break-before: always; }
	#footer ul{margin:0; padding: 0;}
	#footer ul li{list-style: none;margin: 0 0 0 0;text-align:center;font-size:15px} 
	#footer {display: inline-block; position: fixed; left: 0px; bottom: -100px; right: 0px; height: 50px;}
	.left_text{color: white; float: left; font-size: 20px; width: 60%; padding: 35px 0 0 50px;}
	.logo_right{float: right; width: 20%}
	#header {display: inline-block; position: fixed; left: -50px; top: -100px; right: -40px; height: 65px; width: 105%;}
	.left_right{width: 100%; display: inline-block; clear:both; margin: 5px 0 0 0; height: 30px;}
	.left_right .left{width: 50%; float: left; text-align:left;}
	.left_right .right{width: 40%; float: right; text-align:right; }
	.good{color: red} 
	.question_no h1{font-size: 24px; margin-bottom:0;} 
	table{padding: 0 0 15px 0; width:100%; border-collapse: collapse;} 
	table, th, td {border: 1px solid #555; width: 13%;text-align: center; font-size: 10px; padding: 3px 0;} 
	td.active{background-color: #606060;}th.active{background-color: #0496e4;}
	
	.table-two-page, .table-two-page th, .table-two-page td {border:none;text-align:left;width:auto;font-size:11px;padding: 0 2px;}.table-two-page .first-td{padding-left: 15px;width: 70px;}
	
	</style>';
    $result .= '<style>.question_content{font-size: 16px;}</style>';
  
  
	$result .= '<div id="header" style="background: #0496e4; height: 90px;">
		<div class="left_text">Team Cultural Blueprint Comparison Report</div><div class="logo_right"><img style="padding-top: 16px;" src="https://assessment.globalcoachcenter.com/wp-content/themes/power/images/ICBI_Logo_White_Globe190.png" width="111" alt="ICBI"></div>
	</div>
    <div id="footer"><ul><li>Individual Cultural Blueprint Indicator™, All Rights Reserved</li></ul></div>
    ';
    
    
    $table_team = $wpdb->prefix . "team";
    $data_team  = $wpdb->get_results("SELECT * FROM $table_team WHERE team_code='$team_code'");

    $result .= '<h1 style="margin-bottom: 20px; text-align:center; width: 100%; font-size: 30px;" class="result_title">ICBI Team Report for ' . $data_team[0]->team_name . '</h1>';
    $result .= '<p style="margin-bottom: 20px;">The ICBI™ team report is a behavior, habit, and belief-based assessment that empowers individuals to learn about their cultural preferences and compare them with other team members to learn about the different communication and workstyles on the team. The report consists of 16 orientations of cultural differences. There are no right or wrong answers.</p>';
    $result .= '<p style="margin-bottom: 20px;">The ICBI™ is designed to help individuals and teams to increase productivity, improve relationships, and promote efficient communications within multi-cultural environments. The goal is to create awareness of each team member’s unique styles, so the team can leverage strengths, build a more inclusive environment, and address challenges proactively from a place of knowledge and respect. With a trained facilitator or team leader, brainstorm strategies and create agreements to form cultural alliances within your team.</p>';

    $result .= '<div style="height: 350px; width: 100%; display: block"></div>';
    $result .= '<div class="page_break"></div>';

    $result .= '<style>'
                    . '.f_first_last td{background-color: #fff;}.f_question_results td{background-color: #fff;}.f_question_results td.active{background-color:#282359;color:white; }.yellow_bg .active{background-color: #282359 !important; color:white;}'
                    . ''
                    . '</style>';

    $table_questions = $wpdb->prefix . "questions";
    $query           = "SELECT * FROM $table_questions ORDER BY id DESC";
    $get_data        = $wpdb->get_results("SELECT * FROM $table_questions ORDER BY id DESC");

    $result .= two_page_team_report();
    $result .= '<div class="page_break"></div>';

//        first table end
    $total_count = count($get_data);
    foreach ($get_data as $k => $data) {

        $k                      = $k + 1;
        $table_question_answers = $wpdb->prefix . "question_answers";
        $query                  = "SELECT * FROM $table_question_answers ORDER BY id DESC";

        $question_answers = $wpdb->get_results("SELECT * FROM $table_question_answers WHERE question_id=$data->id");


        $dom = new DOMDocument;
        $dom->loadHTML($data->description);
        $p = $dom->getElementsByTagName('p');

        $short_desc='<p>'.getInner($p->item(0)).'</p><p>'.getInner($p->item(1)).'</p><p>'.getInner($p->item(2)).'</p>';
    
        $result .= '<div class="question_ans_box">';
        $result .= '<div class="question_no">';

        $result .= '<h1 style="font-size: 18px" id="' . str_replace(' ', '', strtolower($data->veriable)) . '"><strong>' . $k . '. </strong>' . $data->veriable . '</h1>';

        $result .= '<div class="question_content">' . $short_desc . '</div>';
        $result .= '</div>';
        $result .= '<table style="width:100%">';
        $result .= '<tbody>';
        $result .= '<tr class="f_veriable">';
        $result .= '<td colspan="7" style="text-align:left; font-weight: 700; padding: 1px 0 1px 10px; background-color: #e4e4e4">' . $k . '. ' . $data->veriable . '</td>';
        $result .= '</tr>';
        $result .= '<tr class="f_first_last">';
        $result .= '<td></td>';
        $result .= '<td colspan="3" style="text-align: left; padding: 1px 0 1px 10px">' . $data->first_text . '</td>';
        $result .= '<td colspan="3" style="text-align: right; padding: 1px 10px 1px 0px">' . $data->last_text . '</td>';
        $result .= '</tr>';
        $result .= '</tbody>';

        /*Start Team*/
        if ($team_code) {
            $table_user_quiz = $wpdb->prefix . "user_quiz";
            $users = get_users(array(
                'meta_key'   => 'team_name',
                'meta_value' => $team_code
            ));
            foreach ($users as $key => $user) {
                $correct = 0;
                foreach ($question_answers as $question_answer) {
                    $c_user    = $user->data->ID;
                    $user_quiz = $wpdb->get_row("SELECT * FROM $table_user_quiz WHERE question_ans_id = $question_answer->id AND user_id=$c_user ORDER BY id DESC");
                    if ($user_quiz) {
                        if ($user_quiz->answer == 1 && $question_answer->ane_true == 1) {
                            $correct = $correct + 1;
                        } else if ($user_quiz->answer == 0 && $question_answer->ane_true == 0) {
                            $correct = $correct + 1;
                        }
                    }
                    $total_0 = '';
                    $total_1 = '';
                    $total_2 = '';
                    $total_3 = '';
                    $total_4 = '';
                    $total_5 = '';
                    if ($correct == 0) {
                        $total_0 = 'active';
                    } else if ($correct == 1) {
                        $total_1 = 'active';
                    } else if ($correct == 2) {
                        $total_2 = 'active';
                    } else if ($correct == 3) {
                        $total_3 = 'active';
                    } else if ($correct == 4) {
                        $total_4 = 'active';
                    } else if ($correct == 5) {
                        $total_5 = 'active';
                    } else {
                        $total_0 = 'active';
                    }
                }

                if (get_user_meta($user->data->ID, 'is_hide_report', true) == 1) {
                    $result .= '<tbody>';
                    $result .= '<tr class="f_question_results yellow_bg">';
                    $result .= '<td class="country" style="padding: 1px 10px 1px 0px">' . ucwords($user->data->display_name) . '</td>';
                    $result .= '<td class="' . $total_0 . '" style="padding: 1px 10px 1px 0px">0</td>';
                    $result .= '<td class="' . $total_1 . '" style="padding: 1px 10px 1px 0px">1</td>';
                    $result .= '<td class="' . $total_2 . '" style="padding: 1px 10px 1px 0px">2</td>';
                    $result .= '<td class="' . $total_3 . '" style="padding: 1px 10px 1px 0px">3</td>';
                    $result .= '<td class="' . $total_4 . '" style="padding: 1px 10px 1px 0px">4</td>';
                    $result .= '<td class="' . $total_5 . '" style="padding: 1px 10px 1px 0px">5</td>';
                    $result .= '</tr>';
                    $result .= '</tbody>';
                }
            }
        }
        /*Team end*/

        $result .= '</table>';

        $result .= '</div>';
        if ($k < $total_count) {
            $result .= '<div class="page_break"></div>';
        }
    }
    return $result;
}
    
add_shortcode('generate_pdf_team_report_shortcode', 'generate_pdf_team_report_shortcode');




function generate_pdf_team_nonames_report_shortcode() {
    global $wpdb;

    $team_code = $_GET['team_code'];
    $result = '<style></style>';
    $result .= '<style>@page{margin: 100px 50px}.page_break{page-break-before: always}#footer ul{margin: 0;padding: 0}#footer ul li{list-style: none;margin: 0 0 0 0;text-align: center;font-size: 15px}#footer{display: inline-block;position: fixed;left: 0px;bottom: -100px;right: 0px;height: 50px}.left_text{color: white;float: left;font-size: 20px;width: 60%;padding: 35px 0 0 50px}.logo_right{float: right;width: 20%}#header{display: inline-block;position: fixed;left: -50px;top: -100px;right: -40px;height: 65px;width: 105%}.left_right{width: 100%;display: inline-block;clear: both;margin: 5px 0 0 0;height: 30px}.left_right .left{width: 50%;float: left;text-align: left}.left_right .right{width: 40%;float: right;text-align: right}.good{color: red}.question_no h1{font-size: 24px;margin-bottom: 0}table{padding: 0 0 15px 0;width: 100%;border-collapse: collapse}table,th,td{border: 1px solid #555;width: 13%;text-align: center;font-size: 10px;padding: 3px 0}td.active{background-color: #606060}th.active{background-color: #0496e4}.table-two-page,.table-two-page th,.table-two-page td{border: none;text-align: left;width: auto;font-size: 11px;padding: 0 2px}.table-two-page .first-td{padding-left: 15px;width: 70px}</style>';
    $result .= '<style>.question_content{font-size: 16px;}</style>';
$result .= '<div id="header" style="background: #0496e4; height: 90px;"><div class="left_text">Team Cultural Blueprint Comparison Report (Anonymous)</div><div class="logo_right"><img style="padding-top: 16px;" src="https://assessment.globalcoachcenter.com/wp-content/themes/power/images/ICBI_Logo_White_Globe190.png"  width="111" alt="ICBI"></div></div>
    <div id="footer"><ul><li>Individual Cultural Blueprint Indicator™, All Rights Reserved</li></ul></div>
    ';

    $table_team = $wpdb->prefix . "team";
    $data_team  = $wpdb->get_results("SELECT * FROM $table_team WHERE team_code='$team_code'");

    $result .= '<h1 style="margin-bottom: 20px; text-align:center; width: 100%; font-size: 30px;" class="result_title">ICBI Team Report for ' . $data_team[0]->team_name . '</h1>';
    $result .= '<p style="margin-bottom: 20px;">The ICBI™ team report is a behavior, habit, and belief-based assessment that empowers individuals to learn about their cultural preferences and compare them with other team members to learn about the different communication and workstyles on the team. The report consists of 16 orientations of cultural differences. There are no right or wrong answers.</p>';
    $result .= '<p style="margin-bottom: 20px;">The ICBI™ is designed to help individuals and teams to increase productivity, improve relationships, and promote efficient communications within multi-cultural environments. The goal is to create awareness of each team member’s unique styles, so the team can leverage strengths, build a more inclusive environment, and address challenges proactively from a place of knowledge and respect. With a trained facilitator or team leader, brainstorm strategies and create agreements to form cultural alliances within your team.</p>';

    $result .= '<div style="height: 350px; width: 100%; display: block"></div>';
    $result .= '<div class="page_break"></div>';

    $result .= '<style>'
                    . '.f_first_last td{background-color: #fff;}.f_question_results td{background-color: #fff;}.f_question_results td.active{background-color:#282359;color:white;}.yellow_bg .active{background-color: #282359 !important;color:white;}'
                    . ''
                    . '</style>';

    $table_questions = $wpdb->prefix . "questions";
    $query           = "SELECT * FROM $table_questions ORDER BY id DESC";
    $get_data        = $wpdb->get_results("SELECT * FROM $table_questions ORDER BY id DESC");

    $result .= two_page_team_report();
    $result .= '<div class="page_break"></div>';

//        first table end
    $total_count = count($get_data);
    foreach ($get_data as $k => $data) {

        $k                      = $k + 1;
        $table_question_answers = $wpdb->prefix . "question_answers";
        $query                  = "SELECT * FROM $table_question_answers ORDER BY id DESC";

        $question_answers = $wpdb->get_results("SELECT * FROM $table_question_answers WHERE question_id=$data->id");

        $dom = new DOMDocument;
        $dom->loadHTML($data->description);
        $p = $dom->getElementsByTagName('p');

        $short_desc='<p>'.getInner($p->item(0)).'</p><p>'.getInner($p->item(1)).'</p><p>'.getInner($p->item(2)).'</p>';
       
        $result .= '<div class="question_ans_box">';
        $result .= '<div class="question_no">';

        $result .= '<h1 style="font-size: 18px" id="' . str_replace(' ', '', strtolower($data->veriable)) . '"><strong>' . $k . '. </strong>' . $data->veriable . '</h1>';

        $result .= '<div class="question_content">' . $short_desc . '</div>';

        $result .= '</div>';
        $result .= '<table style="width:100%">';
        $result .= '<tbody>';
        $result .= '<tr class="f_veriable">';
        $result .= '<td colspan="7" style="text-align:left; font-weight: 700; padding: 1px 0 1px 10px; background-color: #e4e4e4">' . $k . '. ' . $data->veriable . '</td>';
        $result .= '</tr>';
        $result .= '<tr class="f_first_last">';
        $result .= '<td></td>';
        $result .= '<td colspan="3" style="text-align: left; padding: 1px 0 1px 10px">' . $data->first_text . '</td>';
        $result .= '<td colspan="3" style="text-align: right; padding: 1px 10px 1px 0px">' . $data->last_text . '</td>';
        $result .= '</tr>';
        $result .= '</tbody>';


        /*Start Team*/
        if ($team_code) {
            $table_user_quiz = $wpdb->prefix . "user_quiz";
            $users = get_users(array(
                'meta_key'   => 'team_name',
                'meta_value' => $team_code
            ));
            $un = 0;

            foreach ($users as $user) {
                $correct = 0;
                foreach ($question_answers as $question_answer) {
                    $c_user    = $user->data->ID;
                    $user_quiz = $wpdb->get_row("SELECT * FROM $table_user_quiz WHERE question_ans_id = $question_answer->id AND user_id=$c_user ORDER BY id DESC");
                    if ($user_quiz) {
                        if ($user_quiz->answer == 1 && $question_answer->ane_true == 1) {
                            $correct = $correct + 1;
                        } else if ($user_quiz->answer == 0 && $question_answer->ane_true == 0) {
                            $correct = $correct + 1;
                        }
                    }

                    $total_0 = '';
                    $total_1 = '';
                    $total_2 = '';
                    $total_3 = '';
                    $total_4 = '';
                    $total_5 = '';
                    if ($correct == 0) {
                        $total_0 = 'active';
                    } else if ($correct == 1) {
                        $total_1 = 'active';
                    } else if ($correct == 2) {
                        $total_2 = 'active';
                    } else if ($correct == 3) {
                        $total_3 = 'active';
                    } else if ($correct == 4) {
                        $total_4 = 'active';
                    } else if ($correct == 5) {
                        $total_5 = 'active';
                    } else {
                        $total_0 = 'active';
                    }
                }

                
                if (get_user_meta($user->data->ID, 'is_hide_report', true) == 1) {
                    $un += 1;
                    $result .= '<tbody>';
                    $result .= '<tr class="f_question_results yellow_bg">';
                    $result .= '<td class="country" style="padding: 1px 10px 1px 0px"> Team Member '. $un . '</td>';
                    $result .= '<td class="' . $total_0 . '" style="padding: 1px 10px 1px 0px">0</td>';
                    $result .= '<td class="' . $total_1 . '" style="padding: 1px 10px 1px 0px">1</td>';
                    $result .= '<td class="' . $total_2 . '" style="padding: 1px 10px 1px 0px">2</td>';
                    $result .= '<td class="' . $total_3 . '" style="padding: 1px 10px 1px 0px">3</td>';
                    $result .= '<td class="' . $total_4 . '" style="padding: 1px 10px 1px 0px">4</td>';
                    $result .= '<td class="' . $total_5 . '" style="padding: 1px 10px 1px 0px">5</td>';
                    $result .= '</tr>';
                    $result .= '</tbody>';
                }
            }
        }
        /*Team end*/

        $result .= '</table>';

        $result .= '</div>';
        if ($k < $total_count) {
            $result .= '<div class="page_break"></div>';
        }
    }
    return $result;
}
    
add_shortcode('generate_pdf_team_nonames_report_shortcode', 'generate_pdf_team_nonames_report_shortcode');


function getInner(DOMElement $node) {
    $tmp = "";
    foreach($node->childNodes as $c) {
        $tmp .= $c->ownerDocument->saveXML($c);
    }
    return $tmp;
}



function two_page_team_report(){
    return '<div style="border:1px solid #000000;font-size: 11px;line-height: 14px;background-color: #ffffff;">
            <a style="text-decoration: none; color: #000000;display: block; border:1px solid #000000;" href="#thinking"><div style="padding-left:3px;background-color: #ececec;"><strong>1. <span style="text-decoration: underline; color: #0496e4;">Thinking</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">inductive / linear</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Values reasoning based on experiences and experimentation and emphasizes analysis and segmentation of issues.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">deductive / systemic</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Environments expects and rewards an emphasis on theory, principles, concepts, and abstract logic. The emphasis is on synthesis, holism, and the big picture.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#change"><div style="padding-left:3px;background-color: #ececec;"> <strong>2. <span style="text-decoration: underline; color: #0496e4;">Change</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">flexibility</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Environments that improvisations exhibit a flexibility orientation and tend to reward risk-taking, tolerate ambiguity and value innovation.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">order</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Environments that value adherence to rules, procedures, and regulations are order oriented and prefer predictability and minimization of risk.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#motivation"><div style="padding-left:3px;background-color: #ececec;"> <strong>3. <span style="text-decoration: underline; color: #0496e4;">Motivation</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">competitive</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Emphasis on personal achievement, individual assertiveness, and success indicate a competitive orientations.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">cooperative</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Valuing quality of life, interdependence, and relationships indicate a cooperative orientation.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#rules"><div style="padding-left:3px;background-color: #ececec;"> <strong>4. <span style="text-decoration: underline; color: #0496e4;">Rules</span</strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">universalistic</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Indicated by exhibiting a value of standards, processes, procedures, rules, and laws to govern situations equally.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">particularistic</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Indicated by placing value on the uniqueness, difference, and situational context to determine the way in which issues should be handled.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#selfidentity"><div style="padding-left:3px;background-color: #ececec;"> <strong>5. <span style="text-decoration: underline; color: #0496e4;">Self Identity</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">individualistic</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">An emphasis on individual motivation, personal independence, and achievement indicate an individualistic orientation.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">collectivistic</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">An emphasis on affiliation and subordination of individual interests to that of a group, community, company, or organization indicates a collectivistic orientation.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#power"><div style="padding-left:3px;background-color: #ececec;"> <strong>6. <span style="text-decoration: underline; color: #0496e4;">Power</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">equality</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Indicated by showing little tolerance for differential power relationships and a minimization of social stratification.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">hierarchy</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Indicated by having a high degree of acceptability of different power relationships and social stratification.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#space"><div style="padding-left:3px;background-color: #ececec;"> <strong>7. <span style="text-decoration: underline; color: #0496e4;">Space</strong></span></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">private</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Tends to put a greater distance between you and others. You share information on a need to know basis.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">public</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Tends to require less distance between you and others.  You share information on a nice to know basis.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#communication-protocol"><div style="padding-left:3px;background-color: #ececec;"> <strong>8. <span style="text-decoration: underline; color: #0496e4;">Communication - Protocol</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">informal</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">An emphasis on dispensing with ceremony and protocol indicates an informal orientation.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">formal</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">An emphasis on protocol, customs, and set processes indicates a formal orientation.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#communication-conflictmanagement"><div style="padding-left:3px;background-color: #ececec;"> <strong>9. <span style="text-decoration: underline; color: #0496e4;">Communication - Conflict Management</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">direct</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Indicated by a perceived value of conflict and a preference for its direct and explicit handling.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">indirect</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Use of implicit modes or third parties in conflict situations and, in general, avoids conflicts.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#communication-expression"><div style="padding-left:3px;background-color: #ececec;"> <strong>10. <span style="text-decoration: underline; color: #0496e4;">Communication - Expression</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">instrumental</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Characterized by valuing factual, detached, and dispassionate interactions and communication styles.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">expressive</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">Emphasizing and displaying emotions and eloquence indicate an expressive orientation.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#communication-context"><div style="padding-left:3px;background-color: #ececec;"> <strong>11. <span style="text-decoration: underline; color: #0496e4;">Communication - Context</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">low-context</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">A low-context communicator thrives on explicit, verbal communication.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">high-context</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">An emphasis on implicit communication and reliance on non-verbal cues indicates a high-context orientation.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#action"><div style="padding-left:3px;background-color: #ececec;"> <strong>12. <span style="text-decoration: underline; color: #0496e4;">Action</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">doing</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">A focus on task accomplishment and quick action indicates a doing orientation.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">being</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">An emphasis on establishing and building relationships before accomplishing tasks indicates a being relationship.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#time-orientation"><div style="padding-left:3px;background-color: #ececec;"> <strong>13. <span style="text-decoration: underline; color: #0496e4;">Time - Orientation</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">future</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">A future orientation focuses on long-term results.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">past/present</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">A past orientation pays much attention to events that happens in the past.  A present orientation places value on short-term and quick results.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#time-nature"><div style="padding-left:3px;background-color: #ececec;"> <strong>14. <span style="text-decoration: underline; color: #0496e4;">Time - Nature</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">fixed</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">A fixed orientation focuses on exact measurements of time.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">fluid</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">A fluid orientation is indicated by disregarding the exact measurement of time.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#time-focus"><div style="padding-left:3px;background-color: #ececec;"> <strong>15. <span style="text-decoration: underline; color: #0496e4;">Time - Focus</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">single-focus</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">A single-focused orientation is indicated by the preference of focusing on one task/relationship at a time.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">multi-focus</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">A multi-focused orientation thrives on attending to multiple things simultaneously.</div>
            </div>
            <a style="text-decoration: none; color: #000000;display: block;border:1px solid #000000;" href="#environment"><div style="padding-left:3px;background-color: #ececec;"> <strong>16. <span style="text-decoration: underline; color: #0496e4;">Environment</span></strong></div></a>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">control</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">A control orientation is indicated by a strong attitude that the environment can and should be changed to fit your needs.</div>
            </div>
            <div style="margin-left:15px;">
                <div style="display: inline-block;width: 11%;vertical-align: top;text-transform: capitalize;">harmony / constraint</div>
                <div style="display: inline-block;width: 88%;vertical-align: top;">A harmony orientation is characterized by the need to balance interests and build consensus. A constraint orientation is characterized by a need to act within clearly set parameters defined by external forces.</div>
            </div>
        </div>';
}