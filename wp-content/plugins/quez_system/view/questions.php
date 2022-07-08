<?php echo "<center><h1>QUESTIONS</h1></center>"; ?>
<div class="wrap">
    <?php
    class  user_questions{
    function fun_user_questions(){
         if(isset($_GET['do'])){
             $do = $_GET['do'];
            if($do == 'add'){
               $this->add();
            }
            elseif ($do == 'edit'){
                    $this->edit();             
            }
            elseif ($do=='delete'){
                    $this->delete();
            }

    } else {     
            global $wpdb;
                $table_name = $wpdb->prefix . "questions";
                $query =  "SELECT * FROM $table_name ORDER BY id DESC" ;
                $get_data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC" ); //  !call any where
                $total_query = "SELECT COUNT(*) FROM $table_name";
                $total = $wpdb->get_var( $total_query );
                $items_per_page = 20;
                $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
                $offset = ( $page * $items_per_page ) - $items_per_page;
                $latestposts = $wpdb->get_results( $query . " LIMIT $offset, $items_per_page" );
    ?>
        <div class="wrap">
            <div class="add_questions">
                <h3 class="wp-heading-inline">Add Question</h3>
                <a href="?page=questions&do=add" class="page-title-action">Add New</a>
                <a href="?page=country-answer&do=add" class="page-title-action">Add Country Answer</a>
            </div>
            <table class="wp-list-table widefat fixed striped posts">
            <tr>
             <th><b>ID</b></th>
             <th><b>Name</b></th>
             <th><b>description</b></th> 
             <th><b>status</b></th> 
             <th><b>Action</b></th>
            </tr>
    <?php
         foreach ( $latestposts as $data )
        {
    ?>
            <tr>
                <td><?php echo $data->id ?></td>
                <td><?php echo $data->name ?></td>
                <td><?php echo $data->description ?></td>
                <td>
                    <?php
                    if($data->status==1){
                        echo 'Active';
                    }else{
                        echo 'De Active';
                    }
                    ?>
                </td>
                <td>
                    <a href="?page=questions&do=edit&id=<?php echo $data->id ?>">Edit</a> | 
                    <a name="delete" href="?page=questions&do=delete&id=<?php echo $data->id ?>">Delete</a>
                </td>
            </tr>
    <?php
        }

    ?>
          </table>
       </div>
    <div class='user_Qpagination'>
    <?php
        echo paginate_links( array(
        'base' => add_query_arg( 'cpage', '%#%' ),
        'format' => '',
        'prev_text' => __('&laquo;'),
        'next_text' => __('&raquo;'),
        'total' => ceil($total / $items_per_page),
        'current' => $page
    ));
        ?>
    </div>
    <?php
     }
   }
   public function add(){
      
       if(isset($_POST['submit'])){
            global $wpdb;
            $table_questions = $wpdb->prefix . "questions";
            $count = $wpdb->get_results( "SELECT * FROM $table_questions" );
            $count = count($count);
            $name = $_POST['qs_name'];
            $first_text = $_POST['first_text'];
            $last_text = $_POST['last_text'];
            $first_text_description = $_POST['first_text_description'];
            $last_text_description = $_POST['last_text_description'];
            $veriable = $_POST['veriable'];
            $desc = $_POST['description_id'];
            $status = $_POST['status'];
            $id = $count+1;
                $wpdb->insert(
                $table_questions, 
                array(
                    'id'=>$id,
                    'name' => $name,
                    'first_text' => $first_text,
                    'last_text' => $last_text,
                    'first_text_description'=> $first_text_description,
                    'last_text_description'=> $last_text_description,
                    'veriable' => $veriable,
                    'description'=> $desc,
                    'status'=> $status
                )
            );
            
                $table_questions = $wpdb->prefix . "questions";
                $row = $wpdb->get_row("SELECT * FROM $table_questions WHERE id = $id");
                
                $table_question_ans = $wpdb->prefix . "question_answers";
                    $question_id =  $row->id;
                    $ans_one = $_POST['first_ans_1'];
                    $ans_two = $_POST['second_ans_1'];
                    $ane_true = $_POST['tr_fls_1'];
                        $wpdb->insert(
                        $table_question_ans, 
                        array(
                            'ans_one' => $ans_one,
                            'ans_two'=> $ans_two,
                            'ane_true'=> $ane_true,
                            'question_id'=> $question_id,
                        )
                    );
                        
                   
                $table_questions = $wpdb->prefix . "questions";
                $row = $wpdb->get_row("SELECT * FROM $table_questions WHERE id = $id");
                
                $table_question_ans = $wpdb->prefix . "question_answers";
                    $question_id = $row->id;
                    $ans_one = $_POST['first_ans_2'];
                    $ans_two = $_POST['second_ans_2'];
                    $ane_true = $_POST['tr_fls_2'];
                        $wpdb->insert(
                        $table_question_ans, 
                        array(
                            'ans_one' => $ans_one,
                            'ans_two'=> $ans_two,
                            'ane_true'=> $ane_true,
                            'question_id'=> $question_id,
                        )
                    );
                        
                       
                    $table_questions = $wpdb->prefix . "questions";
                    $row = $wpdb->get_row("SELECT * FROM $table_questions WHERE id = $id");    
                        
                    $table_question_ans = $wpdb->prefix . "question_answers";
                    $question_id = $row->id;
                    $ans_one = $_POST['first_ans_3'];
                    $ans_two = $_POST['second_ans_3'];
                    $ane_true = $_POST['tr_fls_3'];
                        $wpdb->insert(
                        $table_question_ans, 
                        array(
                            'ans_one' => $ans_one,
                            'ans_two'=> $ans_two,
                            'ane_true'=> $ane_true,
                            'question_id'=> $question_id,
                        )
                    );
                      
                     
                    $table_questions = $wpdb->prefix . "questions";
                    $row = $wpdb->get_row("SELECT * FROM $table_questions WHERE id = $id"); 
                    
                    $table_question_ans = $wpdb->prefix . "question_answers";
                    $question_id = $row->id;
                    $ans_one = $_POST['first_ans_4'];
                    $ans_two = $_POST['second_ans_4'];
                    $ane_true = $_POST['tr_fls_4'];
                        $wpdb->insert(
                        $table_question_ans, 
                        array(
                            'ans_one' => $ans_one,
                            'ans_two'=> $ans_two,
                            'ane_true'=> $ane_true,
                            'question_id'=> $question_id,
                        )
                    );
                      
                       
                    $table_questions = $wpdb->prefix . "questions";
                    $row = $wpdb->get_row("SELECT * FROM $table_questions WHERE id = $id");  
                    
                    $table_question_ans = $wpdb->prefix . "question_answers";
                    $question_id = $row->id;
                    $ans_one = $_POST['first_ans_5'];
                    $ans_two = $_POST['second_ans_5'];
                    $ane_true = $_POST['tr_fls_5'];
                        $wpdb->insert(
                        $table_question_ans, 
                        array(
                            'ans_one' => $ans_one,
                            'ans_two'=> $ans_two,
                            'ane_true'=> $ane_true,
                            'question_id'=> $question_id,
                        )
                    );
              ?>
            <script type="text/javascript">
                window.location.href = "?page=questions";
            </script>
        <?php
            echo "<b> your Question \"{$name}\" - is successfully added</b>";
       }
       ?>
    <div id="quiz_system" style="position: relative;">
        <form method="post" id="my_qzquestions">
            <div id="quesn_result"></div>
            <div class="question_name">
                <input name="qs_name" required size="30" value="" placeholder="Enter Name here" id="qs_name"  type="text"><br>
            </div><br><br>
            
            <div class="question_name">
                <input name="first_text" required size="30" value="" placeholder="Enter First Text" id="first_text"  type="text"><br>
            </div><br>
            
            <div class="question_description required">
                <textarea rows="3" name="first_text_description" required placeholder="Enter First Text Description" id="first_text_description"  type="text"><?php echo $row->first_text_description ?></textarea><br>
            </div><br>

            <div class="question_name">
                <input name="last_text" required size="30" value="" placeholder="Enter Last Text" id="last_text"  type="text"><br>
            </div><br>

            <div class="question_description required">
                <textarea rows="3" name="last_text_description" required size="30" placeholder="Enter Last Text Description" id="last_text_description"  type="text"><?php echo $row->last_text_description ?></textarea><br>
            </div><br>
            
            <div class="question_name">
                <input name="veriable" required size="30" value="" placeholder="Enter Veriable" id="veriable"  type="text"><br>
            </div><br>
            
            <div class="question_description required">
                <?php wp_editor("","description_id"); ?>
            </div><br>
            
            <select class="status" id="status" name="status">
                <option value="1">active</option>
                <option value="0">de active</option>
            </select><br><br><br>
            <b>Question Answers</b>
            
            <br><br>
            <div class="Qanser_table">
                <table>
                    <tr>
                        <th align="left"><b>First Answer</b></th>
                        <th align="left"><b>second Answer</b></th>
                        <th align="left"><b>True / False</b></th>
                    </tr>
                    <tr>
                        <td><input type="text" name="first_ans_1" value="" id="first_ans"></td>
                        <td><input type="text" name="second_ans_1" value="" id="second_ans"></td>
                        <td> 
                            <select id="tr_fls" name="tr_fls_1">
                                <option value="1">First True</option>
                                <option value="0">Second True</option>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" name="first_ans_2" value="" id="first_ans"></td>
                        <td><input type="text" name="second_ans_2" value="" id="second_ans"></td>
                        <td> 
                            <select id="tr_fls" name="tr_fls_2">
                               <option value="1">First True</option>
                                <option value="0">Second True</option>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" name="first_ans_3" value="" id="first_ans"></td>
                        <td><input type="text" name="second_ans_3" value="" id="second_ans"></td>
                        <td> 
                            <select id="tr_fls" name="tr_fls_3">
                                <option value="1">First True</option>
                                <option value="0">Second True</option>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" name="first_ans_4" value="" id="first_ans"></td>
                        <td><input type="text" name="second_ans_4" value="" id="second_ans"></td>
                        <td> 
                            <select id="tr_fls" name="tr_fls_4">
                                <option value="1">First True</option>
                                <option value="0">Second True</option>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td><input type="text" name="first_ans_5" value="" id="first_ans"></td>
                        <td><input type="text" name="second_ans_5" value="" id="second_ans"></td>
                        <td> 
                            <select id="tr_fls" name="tr_fls_5">
                                <option value="1">First True</option>
                                <option value="0">Second True</option>
                            </select> 
                        </td>
                    </tr>
                </table>
            </div>
            <br><br>
            <input type="submit" name="submit" value="submit" class="button button-primary button-large">
        </form>
    </div>
    <?php
   }
    public function delete(){
            if(isset($_GET['id'])){
              $id = $_GET['id'];
            }
            global $wpdb;
                $table_questions = $wpdb->prefix . "questions";
                $wpdb->delete( $table_questions, array( 'id' => $id ), array( '%d' ) );
                
                $table_question_ans = $wpdb->prefix . "question_answers";
                $wpdb->delete( $table_question_ans, array( 'question_id' => $id ), array( '%d' ) );
            ?>
            <script type="text/javascript">
                window.location.href = "?page=questions";
            </script>
         <?php
    }
      
    public function edit(){
           if(isset($_GET['id'])){
           $id = $_GET['id'];
           }
        
           global $wpdb;
           $table_questions = $wpdb->prefix . "questions";
           $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_questions WHERE id = %d", $id));
           
           $table_question_ans = $wpdb->prefix . "question_answers";
           $rowans = $wpdb->get_results( "SELECT * FROM $table_question_ans WHERE question_id = $id ORDER BY id ASC");
           
           
           
            if(isset($_POST['submit'])){
                $name = $_POST['qs_name'];
                $first_text = $_POST['first_text'];
                $last_text = $_POST['last_text'];
                $veriable = $_POST['veriable'];
                $desc = $_POST['description_id'];
                $first_text_description = $_POST['first_text_description'];
                $last_text_description = $_POST['last_text_description'];
                $status = $_POST['status'];
                $wpdb->update(
                $table_questions, 
                   array( 
                        'name' => $name,
                       'first_text' => $first_text,
                       'last_text' => $last_text,
                       'veriable' => $veriable,
                        'description'=> $desc,
                        'first_text_description'=> $first_text_description,
                        'last_text_description'=> $last_text_description,
                        'status'=> $status
               ),
                array( 'id' => $id )
             ); 
                foreach ($rowans as $k=>$rowan){
                    $k = $k+1;
                    $ans_one = $_POST['first_ans_'.$k];
                    $ans_two = $_POST['second_ans_'.$k];
                    $ane_true = $_POST['tr_fls_'.$k];
                    $table_question_ans = $wpdb->prefix . "question_answers";
                    $wpdb->update(
                        $table_question_ans, 
                           array(
                                'ans_one' => $ans_one,
                                'ans_two'=> $ans_two,
                                'ane_true'=> $ane_true,
                            ),
                        array( 'id' => $rowan->id )
                     );
           }
             ?>
            <script type="text/javascript">
                window.location.href = "?page=questions";
            </script>
            <?php
           }
    ?>
    <br><br>
          <div id="quiz_system" style="position: relative; margin: 0">
            <form method="post" id="my_qzquestions">
                <div id="quesn_result"></div>
                <div class="question_name">
                    <input name="qs_name" required size="30" value="<?php echo $row->name ?>" placeholder="Enter Name here" id="qs_name"  type="text"><br>
                </div><br><br>
                
                <div class="question_name">
                    <input name="first_text" required size="30" value="<?php echo $row->first_text ?>" placeholder="Enter First Text" id="first_text"  type="text"><br>
                </div><br>

                <div class="question_description required">
                    <textarea rows="3" name="first_text_description" required placeholder="Enter First Text Description" id="first_text_description"  type="text"><?php echo $row->first_text_description ?></textarea><br>
                </div><br>

                <div class="question_name">
                    <input name="last_text" required value="<?php echo $row->last_text ?>" placeholder="Enter Last Text" id="last_text"  type="text"><br>
                </div><br>

                <div class="question_description required">
                    <textarea rows="3" name="last_text_description" required size="30" placeholder="Enter Last Text Description" id="last_text_description"  type="text"><?php echo $row->last_text_description ?></textarea><br>
                </div><br>

                <div class="question_name">
                    <input name="veriable" required size="30" value="<?php echo $row->veriable ?>" placeholder="Enter Veriable" id="veriable"  type="text"><br>
                </div><br>

                <div class="question_description required">
                    <?php wp_editor( $row->description, "description_id"); ?>
                </div><br><br>

                <select class="status" id="status" name="status">
                    <option value="1" <?php if($row->status==1){ echo 'selected'; } ?>>active</option>
                    <option value="0" <?php if($row->status==0){ echo 'selected'; } ?>>de active</option>
                </select><br><br><br>
                
            <b>Question Answers</b>
            <br><br>
            <div class="Qanser_table">
                <table>
                    <tr>
                        <th align="left"><b>First Answer</b></th>
                        <th align="left"><b>second Answer</b></th>
                        <th align="left"><b>True / False</b></th>
                    </tr>
                    <?php
//                    $rowans = array_reverse($rowans);
                        foreach ($rowans as $k=>$rowan){
                            ?>
                    <tr>
                        <td><input type="text" name="first_ans_<?php echo $k+1; ?>" value="<?php echo $rowan->ans_one; ?>" id="first_ans"></td>
                        <td><input type="text" name="second_ans_<?php echo $k+1; ?>" value="<?php echo $rowan->ans_two; ?>" id="second_ans"></td>
                        <td> 
                            <select id="tr_fls" name="tr_fls_<?php echo $k+1; ?>">
                                <option value="1" <?php if($rowan->ane_true==1){ echo 'selected'; } ?>>First True</option>
                                <option value="0" <?php if($rowan->ane_true==0){ echo 'selected'; } ?>>Second True</option>
                            </select> 
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                </table>
            </div>
            <br><br>
                <input type="submit" name="submit" value="submit" class="button button-primary button-large">
            </form>
        </div>
    
    
        <?php
        }
    }
   $user_obj = new user_questions();
   $user_obj->fun_user_questions();
    ?>
</div>
