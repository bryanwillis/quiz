<?php
    function shortCodeMy_exame(){
    ?>

    <div class="quiz_wrapper">
        <div class="quiz_container">
            <div class="quiz_infornt">
                <?php
                   global $wpdb;
                   $table_name = $wpdb->prefix . "questions";
                   $get_data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id ASC" );
                   $count = 0;
                   foreach ($get_data as $k=>$data){
                       $k = $k+1;
                       $active = '';
                       if($k==1){
                           $active = 'active';
                       }
                       ?>
                    <div class="ques_ans_box <?php echo $active ?>" id="question_<?php echo $k; ?>">
                       <h4><?php echo $data->name; ?></h4>
                    <?php
                   $table_question_ans = $wpdb->prefix . "question_answers";
                   $get_ans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id=$data->id ORDER BY id ASC");
                   foreach ($get_ans as $n=>$g_ans){
                       $n = $n+1;
                           ?>
                        <div class="myquestios_ans">
                            
                            <div class="ans_inradion">
                                <input type="radio" required value="1" id="" class="ansClss_<?php echo $count ?>" name="select_ans_<?php echo $n ?>" > <?php echo $g_ans->ans_one ?>
                            </div>
                            
                            <div class="ans_inradion">
                                <input type="radio" required value="2" id=" " class="ansClss_<?php echo $count ?>" name="select_ans_<?php echo $n ?>" > <?php echo $g_ans->ans_two; ?>
                            </div>
                            
                        </div>
                        <?php
                         $count++;
                       }
                     ?>
                      
                           <input type="submit" value="NEXT" class="quiz_Pagnxt">
                   
                    </div>
                    <?php
                   }
                ?>
                <div class="ques_ans_box">
                    <h1>Congrats!</h1>
                    <p>Please submit your exam</p>
                    <a href="#">Submit</a>
                </div>
            </div>
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
</style>
<script type="text/javascript">
   jQuery(document).ready(function(){
       jQuery(document).on('click','.quiz_Pagnxt',function(){
            var question = jQuery(this).parent().attr('id');
            var question_id = question.replace("question_", "");
            var select_ans_1 = document.querySelector('#'+question+' input[name="select_ans_1"]:checked').value;
            var select_ans_2 = document.querySelector('#'+question+' input[name="select_ans_2"]:checked').value;
            var select_ans_3 = document.querySelector('#'+question+' input[name="select_ans_3"]:checked').value;
            var select_ans_4 = document.querySelector('#'+question+' input[name="select_ans_4"]:checked').value;
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
                jQuery("#"+question).removeClass('active').next().addClass('active');
            }
//            alert(select_ans_1);
//            jQuery(this).removeClass('active').next().addClass('active');
       });
     
       
        
//        jQuery(document).on('click','.ques_ans_box',function(){
//           
//            if (jQuery("input[type='radio']:checked").length  > 0){
//                   
//                }
//
//            else{
//                 jQuery(this).removeClass('active').next().addClass('active');
//            }
//            
//        });
    });
</script>


    <?php
}
add_shortcode('my_exmeShow_by_shortcode', 'shortCodeMy_exame');

