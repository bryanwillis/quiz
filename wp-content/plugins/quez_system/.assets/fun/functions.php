<?php
include_once PLUGIN_DIR_PATH . '../inc/enqu.php';
add_action( 'wp_ajax_nopriv_my_exame_responses', 'my_exame_responses' );
add_action("wp_ajax_my_exame_responses", "my_exame_responses");
function my_exame_responses(){
   session_start();
   
   $first_question_id = $_POST['first_question_id'];
   $first_question_value = $_POST['first_question_value'];
   
   $second_question_id = $_POST['second_question_id'];
   $second_question_value = $_POST['second_question_value'];
   
   $third_question_id = $_POST['third_question_id'];
   $third_question_value = $_POST['third_question_value'];
   
   $fourth_question_id = $_POST['fourth_question_id'];
   $fourth_question_value = $_POST['fourth_question_value'];
   
   $fifth_question_id = $_POST['fifth_question_id'];
   $fifth_question_value = $_POST['fifth_question_value'];
   
//   $data[$question_id] = array(array($id_1st_ans, $select_ans_1), array($id_2nd_ans, $select_ans_2), array($id_3rd_ans, $select_ans_3), array($id_4th_ans, $select_ans_4), array($id_5th_ans, $select_ans_5));
//   $data[] = array($question_id => array($id_1st_ans, $select_ans_1)),array($id_1st_ans, $select_ans_1), array($id_2nd_ans, $select_ans_2), array($id_3rd_ans, $select_ans_3), array($id_4th_ans, $select_ans_4), array($id_5th_ans, $select_ans_5);
//   $data[] = array($question_id => array($id_1st_ans=>$select_ans_1, $id_2nd_ans=>$select_ans_2, $id_3rd_ans=>$select_ans_3, $id_4th_ans=>$select_ans_4, $id_5th_ans=>$select_ans_5));
   
   $first_data[] = array($first_question_id=>$first_question_value);
   $second_data[] = array($second_question_id=>$second_question_value);
   $third_data[] = array($third_question_id=>$third_question_value);
   $fourth_data[] = array($fourth_question_id=>$fourth_question_value);
   $fifth_data[] = array($fifth_question_id=>$fifth_question_value);
   
   
    if(isset($_SESSION['quiz'])){
        $_SESSION['quiz'] = array_merge($_SESSION['quiz'],$first_data,$second_data,$third_data,$fourth_data,$fifth_data);
    }else{
        $_SESSION['quiz'] = array_merge($first_data,$second_data,$third_data,$fourth_data,$fifth_data);
    }
    echo 'success!';
    die();
}

add_action( 'wp_ajax_nopriv_my_exame_responses_development', 'my_exame_responses_development' );
add_action("wp_ajax_my_exame_responses_development", "my_exame_responses_development");
function my_exame_responses_development(){
   session_start();
   
   $question_id = $_POST['question_id'];
   $id_1st_ans = $_POST['id_1st_ans'];
   $select_ans_1 = $_POST['select_ans_1'];
   $id_2nd_ans = $_POST['id_2nd_ans'];
   $select_ans_2 = $_POST['select_ans_2'];
   $id_3rd_ans = $_POST['id_3rd_ans'];
   $select_ans_3 = $_POST['select_ans_3'];
   $id_4th_ans = $_POST['id_4th_ans'];
   $select_ans_4 = $_POST['select_ans_4'];
   $id_5th_ans = $_POST['id_5th_ans'];
   $select_ans_5 = $_POST['select_ans_5'];
   
//   $data[$question_id] = array(array($id_1st_ans, $select_ans_1), array($id_2nd_ans, $select_ans_2), array($id_3rd_ans, $select_ans_3), array($id_4th_ans, $select_ans_4), array($id_5th_ans, $select_ans_5));
//   $data[] = array($question_id => array($id_1st_ans, $select_ans_1)),array($id_1st_ans, $select_ans_1), array($id_2nd_ans, $select_ans_2), array($id_3rd_ans, $select_ans_3), array($id_4th_ans, $select_ans_4), array($id_5th_ans, $select_ans_5);
   $data[] = array($question_id => array($id_1st_ans=>$select_ans_1, $id_2nd_ans=>$select_ans_2, $id_3rd_ans=>$select_ans_3, $id_4th_ans=>$select_ans_4, $id_5th_ans=>$select_ans_5));
   
    if(isset($_SESSION['quiz'])){
        $_SESSION['quiz'] = array_merge($_SESSION['quiz'],$data);
    }else{
        $_SESSION['quiz'] = $data;
    }
    print_r($_SESSION['quiz']);
    die();
}


function generate_pdf(){
    session_start();
    $_SESSION['c_user_id'] = get_current_user_id();
    $result = '<style></style>';
    $result .= '<style>@page { margin: 100px 50px; }.page_break { page-break-before: always; }#footer ul{margin:0; padding: 0;}#footer ul li{list-style: none; display: inline-block; margin: 0 100px 0 0} #footer {display: inline-block; position: fixed; left: 0px; bottom: -100px; right: 0px; height: 50px;}.left_text{float: left; font-size: 20px; width: 70%; padding: 15px 0 0 0;}.logo_right{float: right; width: 25%}#header {display: inline-block; position: fixed; left: 0px; top: -100px; right: 0px; height: 50px;}.left_right{width: 90%; display: inline-block; clear:both; margin: 5px 0 0 0; height: 30px;}.left_right .left{width: 50%; float: left; text-align:left;}.left_right .right{width: 40%; float: right; text-align:right; }.good{color: red} .question_no h1{font-size: 24px; margin-bottom:0;} table{padding: 0 0 15px 0; width:100%; border-collapse: collapse;} table, th, td {border: 1px solid #555; width: 13%;text-align: center; font-size: 10px; padding: 3px 0;} td.active{background-color: blue;}th.active{background-color: #00ff03;}</style>';
    $result .='<style>.question_content{font-size: 16px;}</style>';
    $result .= '<div id="header"><div class="left_text">Your Individual Cultural Blueprint Indicator Report</div><div class="logo_right"><img src="http://assessment.globalcoachcenter.com/wp-content/themes/power/images/logo_right.png" alt="ICBI"></div></div>
    <div id="footer"><ul><li>© Cultural Business Consulting, '.date('Y').'</li><li>www.globalcoachcenter.com</li></ul></div>
    ';
    $user_info = get_userdata(get_current_user_id());
    $first_name = $user_info->first_name;
    $last_name = $user_info->last_name;
    $result .='<h1 style="margin-bottom: 20px; text-align:center; width: 100%; font-size: 30px;" class="result_title">ICBI Report for '.$user_info->first_name.' '.$user_info->last_name.'</h1>';
        $result .='<p style="margin-bottom: 20px;">Your ICBI™ (Individual Cultural Blueprint Indicator) report consists of 16 orientations of cultural differences. Based on your answers, your scores represent different points on a scale from 1 to 5 on each of the 16 orientations. There are no wrong or right answers. The report will help you understand where you fall on each of the orientations and what the gap is between you and the countries you selected for comparison. </p>';
        $result .='<p style="margin-bottom: 20px;">You will note that your profile is quite different from the typical profile of your home country. Please keep that in mind as you look at the other country profiles. The results are based on research from Geert Hofstede and Fons Trompenaars. While cultures and countries have a typical profile, individuals rarely have a typical profile. Use this report as a guideline for building self- and other awareness, but not as an absolute truth. </p>';
        $result .='<p style="margin-bottom: 20px;">On the first page you will find your personal cultural blueprint of all 16 orientations. On the following pages, you will find an explanation of the orientation and some more detailed information followed by a table with your country comparison. Your preference is indicated in green. The preference of the majority of the people in the country (countries) you are comparing yourself to is plotted in the row(s) below yours. Circle or note significant gaps between your personal cultural preferences and other country preferences. With your coach or trainer brainstorm strategies on how to bridge the gaps.</p>';
        $result .='<div style="height: 350px; width: 100%; display: block"></div>';
        $result .='<h2 style="margin-bottom: 20px; text-align:center; width: 100%; font-size: 18px;" >Personal Cultural Blueprint for '.$user_info->first_name.' '.$user_info->last_name.'</h2>';
        global $wpdb;
        $table_questions = $wpdb->prefix . "questions";
        $query =  "SELECT * FROM $table_questions ORDER BY id DESC" ;
        $get_data = $wpdb->get_results( "SELECT * FROM $table_questions ORDER BY id DESC" );
//        first table start
        $result .= '<div class="question_ans_box">';
        $result .= '<table class="f_question_ans_box" style="width:100%">';
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
            
            $result .='<style>'
                    . '.f_first_last td{background-color: #fff;}.f_question_results td{background-color: #fff;}.f_question_results .active{background-color:#00ff03;}'
                    . ''
                    . '</style>';
            $result .= '<tr class="f_veriable">';
                $result .= '<td colspan="6" style="text-align:left; font-weight: 700; padding: 1px 0 1px 10px; background-color: #e4e4e4">'.$k.'. '.$data->veriable.'</td>';
            $result .= '</tr>';
            $result .= '<tr class="f_first_last">';
                $result .= '<td colspan="3" style="text-align: left; padding: 1px 0 1px 10px">'.$data->first_text.'</td>';
                $result .= '<td colspan="3" style="text-align: right; padding: 1px 10px 1px 0px">'.$data->last_text.'</td>';
            $result .= '</tr>';
            $result .= '<tr class="f_question_results">';
                $result .= '<td class="'.$total_0.'" style="padding: 1px 10px 1px 0px">0</td>';
                $result .= '<td class="'.$total_1.'" style="padding: 1px 10px 1px 0px">1</td>';
                $result .= '<td class="'.$total_2.'" style="padding: 1px 10px 1px 0px">2</td>';
                $result .= '<td class="'.$total_3.'" style="padding: 1px 10px 1px 0px">3</td>';
                $result .= '<td class="'.$total_4.'" style="padding: 1px 10px 1px 0px">4</td>';
                $result .= '<td class="'.$total_5.'" style="padding: 1px 10px 1px 0px">5</td>';
            $result .= '</tr>';
            
            
        }
        $result .= '</table>';
        $result .= '</div><div class="page_break"></div>';
        
//        first table end
        $total_count = count($get_data);
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
           
        $result .= '<div class="question_ans_box">';
            $result .= '<div class="question_no">';
                
                $result .= '<h1 style="font-size: 18px"><strong>'.$k.'. </strong>'.$data->veriable.'</h1>';
                
                $result .= '<div class="question_content">'.$data->description.'</div>';
//                $result .= '<div class="left_right"><div class="left">'.$data->first_text.'</div><div class="right">'.$data->last_text.'</div></div>';
            $result .= '</div>';
//            <div class="page_break"></div>
//            $result .= '<div class="question_results">';
//                $result .= '<ul>';
//                    $result .= '<li class="country">You</li>';
//                    $result .= '<li class="'.$total_0.'">0</li>';
//                    $result .= '<li class="'.$total_1.'">1</li>';
//                    $result .= '<li class="'.$total_2.'">2</li>';
//                    $result .= '<li class="'.$total_3.'">3</li>';
//                    $result .= '<li class="'.$total_4.'">4</li>';
//                    $result .= '<li class="'.$total_5.'">5</li>';
//                $result .= '</ul>';
            $result .= '<table style="width:100%">';
            $result .= '<tbody>';
            $result .= '<tr class="f_veriable">';
                $result .= '<td colspan="7" style="text-align:left; font-weight: 700; padding: 1px 0 1px 10px; background-color: #e4e4e4">'.$k.'. '.$data->veriable.'</td>';
            $result .= '</tr>';
            $result .= '<tr class="f_first_last">';
                $result .= '<td></td>';
                $result .= '<td colspan="3" style="text-align: left; padding: 1px 0 1px 10px">'.$data->first_text.'</td>';
                $result .= '<td colspan="3" style="text-align: right; padding: 1px 10px 1px 0px">'.$data->last_text.'</td>';
            $result .= '</tr>';
            $result .= '</tbody>';
            $result .= '<thead>';
            $result .= '<tr>';
            $result .= '<th class="country" style="padding: 1px 10px 1px 0px">You</th>';
            $result .= '<th class="'.$total_0.'" style="padding: 1px 10px 1px 0px">0</th>';
            $result .= '<th class="'.$total_1.'" style="padding: 1px 10px 1px 0px">1</th>';
            $result .= '<th class="'.$total_2.'" style="padding: 1px 10px 1px 0px">2</th>';
            $result .= '<th class="'.$total_3.'" style="padding: 1px 10px 1px 0px">3</th>';
            $result .= '<th class="'.$total_4.'" style="padding: 1px 10px 1px 0px">4</th>';
            $result .= '<th class="'.$total_5.'" style="padding: 1px 10px 1px 0px">5</th>';
            $result .= '</tr>';
            $result .= '</thead>';
                global $wpdb;
                $table_name = $wpdb->prefix . "country_answer";
                $query =  "SELECT * FROM $table_name ORDER BY id DESC" ;
                if(isset($_GET['country'])){
                    $country_list = $_GET['country'];
                    $get_data = $wpdb->get_results( "SELECT * FROM $table_name WHERE question_id=$data->id AND country_name IN ( '" . implode( "', '" , $country_list ) . "' ) ORDER BY id DESC" );
                    foreach ($get_data as $data){
                        $total_0 = '';
                        $total_1 = '';
                        $total_2 = '';
                        $total_3 = '';
                        $total_4 = '';
                        $total_5 = '';
                        if($data->total==0){
                            $total_0 = 'active';
                        }else if($data->total==1){
                            $total_1 = 'active';
                        }else if($data->total==2){
                            $total_2 = 'active';
                        }else if($data->total==3){
                            $total_3 = 'active';
                        }else if($data->total==4){
                            $total_4 = 'active';
                        }else if($data->total==5){
                            $total_5 = 'active';
                        }else{
                            $total_0 = 'active';
                        }
                        $result .= '<tbody>';
                        $result .= '<tr>';
                        if($data->country_name=="United Arab Emirates"){
                            $country_name = 'UAE';
                        }else{
                            $country_name = $data->country_name;
                        }

                        $result .= '<td class="country" style="padding: 1px 10px 1px 0px">'.$country_name.'</td>';
                        $result .= '<td class="'.$total_0.'" style="padding: 1px 10px 1px 0px">0</td>';
                        $result .= '<td class="'.$total_1.'" style="padding: 1px 10px 1px 0px">1</td>';
                        $result .= '<td class="'.$total_2.'" style="padding: 1px 10px 1px 0px">2</td>';
                        $result .= '<td class="'.$total_3.'" style="padding: 1px 10px 1px 0px">3</td>';
                        $result .= '<td class="'.$total_4.'" style="padding: 1px 10px 1px 0px">4</td>';
                        $result .= '<td class="'.$total_5.'" style="padding: 1px 10px 1px 0px">5</td>';
                        $result .= '</tr>';
                        $result .= '</tbody>';
    //                    $result .= '<ul class="country_answer">';
    //                        $result .= '<li class="country">'.$data->country_name.'</li>';
    //                        $result .= '<li class="'.$total_0.'">0</li>';
    //                        $result .= '<li class="'.$total_1.'">1</li>';
    //                        $result .= '<li class="'.$total_2.'">2</li>';
    //                        $result .= '<li class="'.$total_3.'">3</li>';
    //                        $result .= '<li class="'.$total_4.'">4</li>';
    //                        $result .= '<li class="'.$total_5.'">5</li>';
    //                    $result .= '</ul>';
                    }
                }
                
                
//            $result .= '</div>';
                $result .= '</table>';
            
        $result .= '</div>';
            if($k<$total_count){
                $result .= '<div class="page_break"></div>';
            }
        }
    return $result;
}
add_shortcode('generate_pdf', 'generate_pdf');
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